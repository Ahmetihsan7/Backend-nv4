<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LeveringController extends Controller
{
    public function show($productId): View
    {
        $product = DB::table('Product')->where('Id', $productId)->first();
        $magazijn = DB::table('Magazijn')->where('ProductId', $productId)->first();

        $leveringen = DB::table('ProductPerLeverancier')
            ->join('Leverancier', 'ProductPerLeverancier.LeverancierId', '=', 'Leverancier.Id')
            ->where('ProductPerLeverancier.ProductId', $productId)
            ->select(
                'ProductPerLeverancier.*',
                'Leverancier.Naam as LeverancierNaam',
                'Leverancier.ContactPersoon',
                'Leverancier.LeverancierNummer',
                'Leverancier.Mobiel'
            )
            ->orderBy('ProductPerLeverancier.DatumLevering', 'asc')
            ->get();

        $leverancier = $leveringen->first();

        $heeftVoorraad = true;
        $geenVoorraadMelding = '';

        if (! $magazijn || $magazijn->AantalAanwezig === null || $magazijn->AantalAanwezig == 0) {
            $heeftVoorraad = false;
            $geenVoorraadMelding = 'Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is: 30-04-2023';
        }

        return view('levering.show', [
            'product' => $product,
            'magazijn' => $magazijn,
            'leveringen' => $leveringen,
            'leverancier' => $leverancier,
            'heeftVoorraad' => $heeftVoorraad,
            'geenVoorraadMelding' => $geenVoorraadMelding,
        ]);
    }

    public function create(int $leverancierId, int $productId): View
    {
        $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();
        $product = DB::table('Product')->where('Id', $productId)->first();

        if (! $leverancier || ! $product) {
            abort(404, 'Leverancier of product niet gevonden.');
        }

        $productLeverancierExists = DB::table('ProductPerLeverancier')
            ->where('LeverancierId', $leverancierId)
            ->where('ProductId', $productId)
            ->exists();

        if (! $productLeverancierExists) {
            abort(404, 'De gekozen leverancier levert dit product niet.');
        }

        $magazijn = DB::table('Magazijn')->where('ProductId', $productId)->first();

        return view('levering.create', [
            'leverancier' => $leverancier,
            'product' => $product,
            'magazijn' => $magazijn,
            'datumLevering' => now()->toDateString(),
        ]);
    }

    public function store(Request $request, int $leverancierId, int $productId): RedirectResponse
    {
        $validated = $request->validate([
            'datum_eerst_volgende_levering' => ['required', 'date', 'after_or_equal:today'],
            'aantal' => ['required', 'integer', 'min:1', 'max:4294967295'],
        ], [
            'datum_eerst_volgende_levering.after_or_equal' => 'De eerstvolgende leverdatum moet vandaag of later zijn.',
            'aantal.integer' => 'Het aantal moet een positief geheel getal zijn.',
            'aantal.min' => 'Het aantal moet minimaal 1 zijn.',
        ]);

        $datumLevering = now()->toDateString();
        $connection = DB::connection();

        $result = $connection->transaction(function () use ($connection, $leverancierId, $productId, $validated, $datumLevering): int {
            $pdo = $connection->getPdo();
            $statement = $pdo->prepare('CALL sp_RegistreerLevering(?, ?, ?, ?, ?, ?, @p_result)');
            $statement->bindValue(1, $leverancierId, \PDO::PARAM_INT);
            $statement->bindValue(2, $productId, \PDO::PARAM_INT);
            $statement->bindValue(3, $datumLevering, \PDO::PARAM_STR);
            $statement->bindValue(4, (int) $validated['aantal'], \PDO::PARAM_INT);
            $statement->bindValue(5, $validated['datum_eerst_volgende_levering'], \PDO::PARAM_STR);
            $statement->bindValue(6, null, \PDO::PARAM_NULL);
            $statement->execute();

            while ($statement->nextRowset()) {
                // Clear stored procedure result sets before reading its OUT parameter.
            }

            $statement->closeCursor();
            $result = $pdo->query('SELECT @p_result')->fetchColumn();

            return (int) $result;
        });

        if ($result === 3) {
            $product = DB::table('Product')->where('Id', $productId)->first();
            $leverancier = DB::table('Leverancier')->where('Id', $leverancierId)->first();

            if (! $product || ! $leverancier) {
                abort(404, 'Leverancier of product niet gevonden.');
            }

            return back()
                ->withInput()
                ->with('inactive_product_error', "Het product {$product->Naam} van de leverancier {$leverancier->Naam} wordt niet meer geproduceerd");
        }

        if ($result !== 1) {
            $message = match ($result) {
                2 => 'De gekozen leverancier levert dit product niet.',
                4 => 'Er is geen magazijnregel gevonden voor dit product.',
                5 => 'Deze levering voor dit product is vandaag al geregistreerd.',
                6 => 'De voorraad kan niet verder worden verhoogd.',
                default => 'De levering kon niet worden verwerkt.',
            };

            return back()->withErrors(['levering' => $message])->withInput();
        }

        return redirect()->route('leverancier.producten', $leverancierId)
            ->with('success', 'Levering succesvol geregistreerd.');
    }
}
