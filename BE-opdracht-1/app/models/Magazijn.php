<?php

class Magazijn
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function getProducten(): array
    {
        $sql = "
            SELECT
                p.Id,
                p.Naam,
                p.Barcode,
                m.VerpakkingsEenheidInKilogram,
                m.AantalAanwezig
            FROM Product p
            LEFT JOIN Magazijn m
                ON p.Id = m.ProductId
            WHERE p.IsActief = 1
            ORDER BY p.Barcode ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProduct(int $productId): array|false
    {
        $sql = "
            SELECT
                Id,
                Naam,
                Barcode
            FROM Product
            WHERE Id = :productId
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':productId' => $productId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getLeveringen(int $productId): array
    {
        $sql = "
            SELECT
                l.Naam AS LeverancierNaam,
                l.ContactPersoon,
                l.LeverancierNummer,
                l.Mobiel,
                ppl.DatumLevering,
                ppl.Aantal,
                ppl.DatumEerstVolgendeLevering
            FROM ProductPerLeverancier ppl
            INNER JOIN Leverancier l
                ON ppl.LeverancierId = l.Id
            WHERE ppl.ProductId = :productId
            AND ppl.IsActief = 1
            ORDER BY ppl.DatumLevering ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':productId' => $productId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getVoorraad(int $productId): int
    {
        $sql = "
            SELECT COALESCE(AantalAanwezig, 0) AS Voorraad
            FROM Magazijn
            WHERE ProductId = :productId
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':productId' => $productId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 0;
        }

        return (int) $result['Voorraad'];
    }


    public function getAllergenen(int $productId): array
    {
        $sql = "
            SELECT
                a.Id,
                a.Naam,
                a.Omschrijving
            FROM ProductPerAllergeen ppa
            INNER JOIN Allergeen a
                ON ppa.AllergeenId = a.Id
            WHERE ppa.ProductId = :productId
            AND ppa.IsActief = 1
            AND a.IsActief = 1
            ORDER BY a.Naam ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':productId' => $productId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}