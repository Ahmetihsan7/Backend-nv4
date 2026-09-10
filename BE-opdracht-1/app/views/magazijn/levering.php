<?php
/** @var array<string, mixed> $product */
/** @var int $voorraad */
/** @var array<int, array<string, mixed>> $leveringen */
?>
<!DOCTYPE html>
<html lang="nl">

<head>

    <meta charset="UTF-8">

    <title>Levering Informatie</title>

    <?php if ($voorraad <= 0): ?>

        <meta
            http-equiv="refresh"
            content="4;url=index.php?page=magazijn"
        >

    <?php endif; ?>


    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        table {
            border-collapse: collapse;
            width: 900px;
        }

        th,
        td {
            border: 1px solid black;
            padding: 10px;
        }

        th {
            background-color: #eeeeee;
        }

        .melding {
            background-color: #f2f2f2;
            padding: 20px;
            width: 850px;
        }

    </style>

</head>


<body>


<h1>Levering Informatie</h1>


<h2>
    <?= htmlspecialchars($product['Naam']) ?>
</h2>


<?php if ($voorraad <= 0): ?>


    <div class="melding">

        Er is van dit product op dit moment geen voorraad aanwezig,
        de verwachte eerstvolgende levering is: 30-04-2023

    </div>


    <p>
        U wordt over 4 seconden teruggestuurd naar Overzicht Magazijn Jamin.
    </p>


<?php elseif (!empty($leveringen)): ?>


    <?php $leverancier = $leveringen[0]; ?>


    <p>
        <strong>Naam leverancier:</strong>
        <?= htmlspecialchars($leverancier['LeverancierNaam']) ?>
    </p>


    <p>
        <strong>Contactpersoon leverancier:</strong>
        <?= htmlspecialchars($leverancier['ContactPersoon']) ?>
    </p>


    <p>
        <strong>Leveranciernummer:</strong>
        <?= htmlspecialchars($leverancier['LeverancierNummer']) ?>
    </p>


    <p>
        <strong>Mobiel:</strong>
        <?= htmlspecialchars($leverancier['Mobiel']) ?>
    </p>


    <table>

        <thead>

        <tr>

            <th>Datum laatste levering</th>

            <th>Aantal</th>

            <th>Verwachte eerstvolgende levering</th>

        </tr>

        </thead>


        <tbody>

        <?php foreach ($leveringen as $levering): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($levering['DatumLevering']) ?>
                </td>


                <td>
                    <?= htmlspecialchars($levering['Aantal']) ?>
                </td>


                <td>

                    <?= htmlspecialchars(
                        $levering['DatumEerstVolgendeLevering']
                        ?? 'Onbekend'
                    ) ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>


<?php else: ?>

    <p>
        Geen leveringsinformatie gevonden.
    </p>

<?php endif; ?>


<br><br>


<a href="index.php?page=magazijn">
    Terug naar Overzicht Magazijn Jamin
</a>


</body>

</html>