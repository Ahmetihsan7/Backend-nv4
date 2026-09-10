<?php
/** @var array<string, mixed> $product */
/** @var array<int, array<string, mixed>> $allergenen */
?>
<!DOCTYPE html>
<html lang="nl">

<head>

    <meta charset="UTF-8">

    <title>Overzicht Allergenen</title>


    <?php if (empty($allergenen)): ?>

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
            width: 800px;
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
            width: 750px;
        }

    </style>


</head>


<body>


<h1>Overzicht Allergenen</h1>


<p>

    <strong>Naam Product:</strong>

    <?= htmlspecialchars($product['Naam']) ?>

</p>


<p>

    <strong>Barcode:</strong>

    <?= htmlspecialchars($product['Barcode']) ?>

</p>


<?php if (empty($allergenen)): ?>


    <div class="melding">

        In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken

    </div>


    <p>
        U wordt over 4 seconden teruggestuurd naar Overzicht Magazijn Jamin.
    </p>


<?php else: ?>


    <table>

        <thead>

        <tr>

            <th>Naam</th>

            <th>Omschrijving</th>

        </tr>

        </thead>


        <tbody>

        <?php foreach ($allergenen as $allergeen): ?>

            <tr>

                <td>

                    <?= htmlspecialchars($allergeen['Naam']) ?>

                </td>


                <td>

                    <?= htmlspecialchars($allergeen['Omschrijving']) ?>

                </td>

            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>


<?php endif; ?>


<br><br>


<a href="index.php?page=magazijn">
    Terug naar Overzicht Magazijn Jamin
</a>


</body>

</html>