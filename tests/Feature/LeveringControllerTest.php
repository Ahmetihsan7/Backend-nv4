<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LeveringControllerTest extends TestCase
{
    public function test_levering_form_redirects_guests_to_login(): void
    {
        $this->get(route('levering.create', [1, 1]))
            ->assertRedirect(route('login'));
    }

    public function test_levering_form_shows_the_supplier_product_and_two_required_inputs(): void
    {
        $this->createSupplierProductTables();

        $response = $this->actingAs(User::factory()->make())
            ->get(route('levering.create', [1, 1]));

        $response->assertStatus(200);
        $response->assertSee('Levering product');
        $response->assertSee('Mintnopjes');
        $response->assertSee('Venco');
        $response->assertSee('Bert van Linge');
        $response->assertSee('06-12345678');
        $response->assertSee('name="aantal"', false);
        $response->assertSee('name="datum_eerst_volgende_levering"', false);
        $response->assertSee('Sla op');
        $response->assertSee('Terug');
        $response->assertSee('Home');
    }

    public function test_levering_form_returns_not_found_for_an_unrelated_supplier_product_pair(): void
    {
        $this->createSupplierProductTables();

        $this->actingAs(User::factory()->make())
            ->get(route('levering.create', [2, 1]))
            ->assertNotFound();
    }

    public function test_inactive_product_form_shows_the_error_and_returns_to_supplier_products_after_four_seconds(): void
    {
        $this->createSupplierProductTables();

        DB::table('Leverancier')->insert([
            'Id' => 3,
            'Naam' => 'Basset',
            'ContactPersoon' => 'Basset contact',
            'Mobiel' => '06-00000000',
        ]);
        DB::table('Product')->insert([
            'Id' => 2,
            'Naam' => 'Winegums',
            'IsActief' => false,
        ]);
        DB::table('ProductPerLeverancier')->insert([
            'LeverancierId' => 3,
            'ProductId' => 2,
            'DatumLevering' => '2026-10-01',
            'Aantal' => 50,
        ]);

        $response = $this->actingAs(User::factory()->make())
            ->withSession([
                'inactive_product_error' => 'Het product Winegums van de leverancier Basset wordt niet meer geproduceerd',
            ])
            ->get(route('levering.create', [3, 2]));

        $response->assertSee('Het product Winegums van de leverancier Basset wordt niet meer geproduceerd');
        $response->assertSee('name="aantal"', false);
        $response->assertSee('name="datum_eerst_volgende_levering"', false);
        $response->assertSee('setTimeout');
        $response->assertSee('}, 4000);');
        $response->assertSee(route('leverancier.producten', 3), false);
    }

    public function test_levering_is_rejected_when_the_quantity_is_not_a_positive_integer(): void
    {
        $response = $this->actingAs(User::factory()->make())
            ->from(route('levering.create', [1, 1]))
            ->post(route('levering.store', [1, 1]), [
                'aantal' => 0,
                'datum_eerst_volgende_levering' => now()->toDateString(),
            ]);

        $response->assertRedirect(route('levering.create', [1, 1]));
        $response->assertSessionHasErrors([
            'aantal' => 'Het aantal moet minimaal 1 zijn.',
        ]);
    }

    public function test_levering_is_rejected_when_the_next_delivery_date_is_in_the_past(): void
    {
        $response = $this->actingAs(User::factory()->make())
            ->from(route('levering.create', [1, 1]))
            ->post(route('levering.store', [1, 1]), [
                'aantal' => 1,
                'datum_eerst_volgende_levering' => now()->subDay()->toDateString(),
            ]);

        $response->assertRedirect(route('levering.create', [1, 1]));
        $response->assertSessionHasErrors([
            'datum_eerst_volgende_levering' => 'De eerstvolgende leverdatum moet vandaag of later zijn.',
        ]);
    }

    private function createSupplierProductTables(): void
    {
        Schema::create('Leverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->string('ContactPersoon');
            $table->string('Mobiel');
        });

        Schema::create('Product', function (Blueprint $table): void {
            $table->increments('Id');
            $table->string('Naam');
            $table->boolean('IsActief')->default(true);
        });

        Schema::create('Magazijn', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('ProductId');
            $table->unsignedInteger('AantalAanwezig')->nullable();
        });

        Schema::create('ProductPerLeverancier', function (Blueprint $table): void {
            $table->increments('Id');
            $table->unsignedInteger('LeverancierId');
            $table->unsignedInteger('ProductId');
            $table->date('DatumLevering');
            $table->unsignedInteger('Aantal');
        });

        DB::table('Leverancier')->insert([
            ['Id' => 1, 'Naam' => 'Venco', 'ContactPersoon' => 'Bert van Linge', 'Mobiel' => '06-12345678'],
            ['Id' => 2, 'Naam' => 'Andere leverancier', 'ContactPersoon' => 'Test contact', 'Mobiel' => '06-87654321'],
        ]);
        DB::table('Product')->insert([
            'Id' => 1,
            'Naam' => 'Mintnopjes',
            'IsActief' => true,
        ]);
        DB::table('Magazijn')->insert([
            'ProductId' => 1,
            'AantalAanwezig' => 453,
        ]);
        DB::table('ProductPerLeverancier')->insert([
            'LeverancierId' => 1,
            'ProductId' => 1,
            'DatumLevering' => '2026-10-01',
            'Aantal' => 50,
        ]);
    }
}
