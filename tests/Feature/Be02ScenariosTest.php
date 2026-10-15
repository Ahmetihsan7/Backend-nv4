<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class Be02ScenariosTest extends TestCase
{
    public function test_login_dashboard_and_warehouse_overview_still_render(): void
    {
        $this->requireMySqlDatabase();
        $this->get(route('login'))
            ->assertOk();

        $this->actingAs(User::factory()->make())
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Overzicht Magazijn Jamin')
            ->assertSee('Overzicht Leveranciers');

        $this->get(route('magazijn.index'))
            ->assertOk()
            ->assertSee('8719587231278')
            ->assertSee('Mintnopjes')
            ->assertSee('453')
            ->assertSee('Allergenen Info')
            ->assertSee('Leverantie Info')
            ->assertSee(route('allergeen.show', 1), false)
            ->assertSee(route('levering.show', 1), false);
    }

    public function test_supplier_overview_lists_all_suppliers_including_quality_street(): void
    {
        $this->requireMySqlDatabase();

        $response = $this->actingAs(User::factory()->make())
            ->get(route('leverancier.index'));

        $response->assertOk();
        $response->assertSee('Overzicht Leveranciers');
        $response->assertSee('Venco');
        $response->assertSee('Astra Sweets');
        $response->assertSee('Haribo');
        $response->assertSee('Basset');
        $response->assertSee('De Bron');
        $response->assertSee('Quality Street');
        $response->assertSee('Aantal verschillende producten');
        $response->assertSeeInOrder([
            'Venco',
            'Bert van Linge',
            'L1029384719',
            '06-28493827',
            '3',
        ], false);
        $response->assertSeeInOrder([
            'Quality Street',
            'Johan Nooij',
            'L1029234586',
            '06-23458456',
            '0',
        ], false);
        $response->assertSee(route('leverancier.producten', $this->supplierId('Quality Street')), false);
    }

    public function test_quality_street_shows_the_empty_supplier_message_and_three_second_return(): void
    {
        $this->requireMySqlDatabase();
        $supplierId = $this->supplierId('Quality Street');

        $this->actingAs(User::factory()->make())
            ->get(route('leverancier.producten', $supplierId))
            ->assertOk()
            ->assertSee('Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin')
            ->assertSee('}, 3000);', false)
            ->assertSee(route('leverancier.index'), false);
    }

    public function test_mintnopjes_delivery_updates_stock_and_latest_delivery_then_rolls_back(): void
    {
        $this->requireMySqlDatabase();
        $pair = $this->supplierProduct('Venco', 'Mintnopjes');
        $supplierId = (int) $pair->LeverancierId;
        $productId = (int) $pair->ProductId;
        $today = now()->toDateString();

        $this->assertSame(1, (int) DB::table('Product')->where('Id', $productId)->value('IsActief'));
        $this->assertSame(453, (int) DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
        $this->assertSame(2, DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $supplierId)
            ->where('ProductId', $productId)
            ->count());

        if (DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $supplierId)
            ->where('ProductId', $productId)
            ->whereDate('DatumLevering', $today)
            ->exists()) {
            $this->markTestSkipped('A Venco/Mintnopjes delivery already exists for today; no duplicate test write was made.');
        }

        DB::beginTransaction();

        try {
            $this->actingAs(User::factory()->make())
                ->from(route('levering.create', [$supplierId, $productId]))
                ->post(route('levering.store', [$supplierId, $productId]), [
                    'aantal' => 25,
                    'datum_eerst_volgende_levering' => $today,
                ])
                ->assertRedirect(route('leverancier.producten', $supplierId))
                ->assertSessionHas('success', 'Levering succesvol geregistreerd.');

            $this->assertSame(478, (int) DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
            $this->assertSame(3, DB::table('ProductPerLeverancier')
                ->where('LeverancierId', $supplierId)
                ->where('ProductId', $productId)
                ->count());
            $this->assertDatabaseHas('ProductPerLeverancier', [
                'LeverancierId' => $supplierId,
                'ProductId' => $productId,
                'DatumLevering' => $today,
                'Aantal' => 25,
                'DatumEerstVolgendeLevering' => $today,
            ]);
            $this->assertSame($today, DB::table('ProductPerLeverancier')
                ->where('LeverancierId', $supplierId)
                ->where('ProductId', $productId)
                ->max('DatumLevering'));

            $this->from(route('levering.create', [$supplierId, $productId]))
                ->post(route('levering.store', [$supplierId, $productId]), [
                    'aantal' => 25,
                    'datum_eerst_volgende_levering' => $today,
                ])
                ->assertRedirect(route('levering.create', [$supplierId, $productId]))
                ->assertSessionHasErrors([
                    'levering' => 'Deze levering voor dit product is vandaag al geregistreerd.',
                ]);

            $this->assertSame(478, (int) DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
            $this->assertSame(3, DB::table('ProductPerLeverancier')
                ->where('LeverancierId', $supplierId)
                ->where('ProductId', $productId)
                ->count());
        } finally {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }

        $this->assertSame(453, (int) DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
        $this->assertSame(2, DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $supplierId)
            ->where('ProductId', $productId)
            ->count());
    }

    public function test_inactive_winegums_delivery_shows_the_exact_error_without_changing_data(): void
    {
        $this->requireMySqlDatabase();
        $pair = $this->supplierProduct('Basset', 'Winegums');
        $supplierId = (int) $pair->LeverancierId;
        $productId = (int) $pair->ProductId;
        $today = now()->toDateString();
        $stockBefore = DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig');
        $deliveryCountBefore = DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $supplierId)
            ->where('ProductId', $productId)
            ->count();

        $this->assertSame(0, (int) DB::table('Product')->where('Id', $productId)->value('IsActief'));

        DB::beginTransaction();

        try {
            $this->actingAs(User::factory()->make())
                ->from(route('levering.create', [$supplierId, $productId]))
                ->followingRedirects()
                ->post(route('levering.store', [$supplierId, $productId]), [
                    'aantal' => 30,
                    'datum_eerst_volgende_levering' => $today,
                ])
                ->assertOk()
                ->assertSee('Het product Winegums van de leverancier Basset wordt niet meer geproduceerd')
                ->assertSee('}, 4000);', false)
                ->assertSee(route('leverancier.producten', $supplierId), false);

            $this->assertSame($stockBefore, DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
            $this->assertSame($deliveryCountBefore, DB::table('ProductPerLeverancier')
                ->where('LeverancierId', $supplierId)
                ->where('ProductId', $productId)
                ->count());
        } finally {
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
        }

        $this->assertSame($stockBefore, DB::table('Magazijn')->where('ProductId', $productId)->value('AantalAanwezig'));
        $this->assertSame($deliveryCountBefore, DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $supplierId)
            ->where('ProductId', $productId)
            ->count());
    }

    private function requireMySqlDatabase(): void
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            $this->markTestSkipped('BE-opdracht 02 scenario tests require MySQL stored procedures; no data was changed.');
        }
    }

    private function supplierId(string $supplierName): int
    {
        $supplierId = DB::table('Leverancier')->where('Naam', $supplierName)->value('Id');
        $this->assertNotNull($supplierId, "The {$supplierName} supplier is required for this scenario.");

        return (int) $supplierId;
    }

    private function supplierProduct(string $supplierName, string $productName): object
    {
        $pair = DB::table('Leverancier as l')
            ->join('ProductPerLeverancier as pl', 'pl.LeverancierId', '=', 'l.Id')
            ->join('Product as p', 'p.Id', '=', 'pl.ProductId')
            ->where('l.Naam', $supplierName)
            ->where('p.Naam', $productName)
            ->select('l.Id as LeverancierId', 'p.Id as ProductId')
            ->first();

        $this->assertNotNull($pair, "{$supplierName} must be connected to {$productName} for this scenario.");

        return $pair;
    }
}
