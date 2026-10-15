-- Fresh-install script for an empty Jamin database only.
-- It never drops a database, table, or procedure. Do not run it against the
-- existing laravel database or any database containing application data.
CREATE DATABASE IF NOT EXISTS Jamin;
USE Jamin;

-- Step : 01
/********************************************************************************
-- Doel : Maak een nieuwe database aan heet Jamin.
-- ******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		Veilige lege database-initialisatie
*********************************************************************************/

-- ==============================================================================
-- DEEL 1: STAMTABELLEN (Alleen Primary Key, geen Foreign Keys)
-- ==============================================================================

-- Step : 02.1
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Product.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE Product gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Product 
(
     Id                  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,Barcode             VARCHAR(13) NOT NULL
    ,IsActief            TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- Step : 02.2
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Leverancier.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE Leverancier gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Leverancier 
(
     Id                  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,ContactPersoon      VARCHAR(100) NOT NULL
    ,LeverancierNummer   VARCHAR(20) NOT NULL
    ,Mobiel              VARCHAR(15) NOT NULL
    ,IsActief            TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- Step : 02.3
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Allergeen.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE Allergeen gemaakt (Stamtabel)	 
**********************************************************************************/ 
CREATE TABLE Allergeen 
(
     Id                  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,Naam                VARCHAR(100) NOT NULL
    ,Omschrijving        VARCHAR(250) NOT NULL
    ,IsActief            TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
);


-- ==============================================================================
-- DEEL 2: KOPPELTABELLEN & AFHANKELIJKE TABELLEN (Met Foreign Keys)
-- ==============================================================================

-- Step : 03.1
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet Magazijn.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE Magazijn gemaakt met FK naar Product	 
**********************************************************************************/ 
CREATE TABLE Magazijn 
(
     Id                  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId           TINYINT UNSIGNED NOT NULL
    ,VerpakkingsEenheid  DECIMAL(8,3) NOT NULL
    ,AantalAanwezig      INT UNSIGNED NULL DEFAULT NULL
    ,IsActief            TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_Magazijn_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Step : 03.2
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet ProductPerAllergeen.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE ProductPerAllergeen gemaakt met FK naar Product en Allergeen	 
**********************************************************************************/ 
CREATE TABLE ProductPerAllergeen 
(
     Id                  TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,ProductId           TINYINT UNSIGNED NOT NULL
    ,AllergeenId         TINYINT UNSIGNED NOT NULL
    ,IsActief            TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking           VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt     DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd      DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_ProductPerAllergeen_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
    ,CONSTRAINT FK_ProductPerAllergeen_AllergeenId FOREIGN KEY (AllergeenId) REFERENCES Allergeen(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- Step : 03.3
/*********************************************************************************
-- Doel : Maak een nieuwe tabel aan heet ProductPerLeverancier.
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		CREATE TABLE ProductPerLeverancier gemaakt met FK naar Leverancier en Product	 
**********************************************************************************/ 
CREATE TABLE ProductPerLeverancier 
(
     Id                          TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    ,LeverancierId               TINYINT UNSIGNED NOT NULL
    ,ProductId                   TINYINT UNSIGNED NOT NULL
    ,DatumLevering               DATE NOT NULL
    ,Aantal                      INT UNSIGNED NOT NULL
    ,DatumEerstVolgendeLevering  DATE NULL DEFAULT NULL
    ,IsActief                    TINYINT(1) NOT NULL DEFAULT 1
    ,Opmerking                   VARCHAR(250) NULL DEFAULT NULL
    ,DatumAangemaakt             DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6)
    ,DatumGewijzigd              DATETIME(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
    ,CONSTRAINT FK_ProductPerLeverancier_LeverancierId FOREIGN KEY (LeverancierId) REFERENCES Leverancier(Id) ON DELETE CASCADE ON UPDATE CASCADE
    ,CONSTRAINT FK_ProductPerLeverancier_ProductId FOREIGN KEY (ProductId) REFERENCES Product(Id) ON DELETE CASCADE ON UPDATE CASCADE
);


-- ==============================================================================
-- DEEL 3: DATA INVOEREN (INSERT STATEMENTS)
-- ==============================================================================

-- Step : 04.1
/*********************************************************************************
-- Doel : Voeg 13 rijen toe met INSERT aan tabel Product
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		13 rijen gevoegd met insert aan tabel Product 
**********************************************************************************/ 
INSERT INTO Product
(
     Id
    ,Naam
    ,Barcode
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Mintnopjes', '8719587231278', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Schoolkrijt', '8719587326713', 1, NULL, NOW(6), NOW(6))
      ,(3, 'Honingdrop', '8719587327836', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Zure Beren', '8719587321441', 1, NULL, NOW(6), NOW(6))
      ,(5, 'Cola Flesjes', '8719587321237', 1, NULL, NOW(6), NOW(6))
      ,(6, 'Turtles', '8719587322245', 1, NULL, NOW(6), NOW(6))
      ,(7, 'Witte Muizen', '8719587328256', 1, NULL, NOW(6), NOW(6))
      ,(8, 'Reuzen Slangen', '8719587325641', 1, NULL, NOW(6), NOW(6))
      ,(9, 'Zoute Rijen', '8719587322739', 1, NULL, NOW(6), NOW(6))
      ,(10, 'Winegums', '8719587327527', 0, NULL, NOW(6), NOW(6))
      ,(11, 'Drop Munten', '8719587322345', 1, NULL, NOW(6), NOW(6))
      ,(12, 'Kruis Drop', '8719587322265', 1, NULL, NOW(6), NOW(6))
      ,(13, 'Zoute Ruitjes', '8719587323256', 1, NULL, NOW(6), NOW(6));


-- Step : 04.2
/*********************************************************************************
-- Doel : Voeg 6 rijen toe met INSERT aan tabel Leverancier
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		5 rijen gevoegd met insert aan tabel Leverancier 
**********************************************************************************/ 
INSERT INTO Leverancier
(
     Id
    ,Naam
    ,ContactPersoon
    ,LeverancierNummer
    ,Mobiel
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Venco', 'Bert van Linge', 'L1029384719', '06-28493827', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Astra Sweets', 'Jasper del Monte', 'L1029284315', '06-39398734', 1, NULL, NOW(6), NOW(6))
      ,(3, 'Haribo', 'Sven Stalman', 'L1029324748', '06-24383291', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Basset', 'Joyce Stelterberg', 'L1023845773', '06-48293823', 1, NULL, NOW(6), NOW(6))
      ,(5, 'De Bron', 'Remco Veenstra', 'L1023857736', '06-34291234', 1, NULL, NOW(6), NOW(6))
      ,(6, 'Quality Street', 'Johan Nooij', 'L1029234586', '06-23458456', 1, NULL, NOW(6), NOW(6));


-- Step : 04.3
/*********************************************************************************
-- Doel : Voeg 5 rijen toe met INSERT aan tabel Allergeen
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		5 rijen gevoegd met insert aan tabel Allergeen 
**********************************************************************************/ 
INSERT INTO Allergeen
(
     Id
    ,Naam
    ,Omschrijving
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 'Gluten', 'Dit product bevat gluten', 1, NULL, NOW(6), NOW(6))
      ,(2, 'Gelatine', 'Dit product bevat gelatine', 1, NULL, NOW(6), NOW(6))
      ,(3, 'AZO-Kleurstof', 'Dit product bevat AZO-kleurstoffen', 1, NULL, NOW(6), NOW(6))
      ,(4, 'Lactose', 'Dit product bevat lactose', 1, NULL, NOW(6), NOW(6))
      ,(5, 'Soja', 'Dit product bevat soja', 1, NULL, NOW(6), NOW(6));


-- Step : 04.4
/*********************************************************************************
-- Doel : Voeg 13 rijen toe met INSERT aan tabel Magazijn
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		13 rijen gevoegd met insert aan tabel Magazijn (rij 10 heeft NULL voorraad) 
**********************************************************************************/ 
INSERT INTO Magazijn
(
     Id
    ,ProductId
    ,VerpakkingsEenheid
    ,AantalAanwezig
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 5.0, 453, 1, NULL, NOW(6), NOW(6))
      ,(2, 2, 2.5, 400, 1, NULL, NOW(6), NOW(6))
      ,(3, 3, 5.0, 1, 1, NULL, NOW(6), NOW(6))
      ,(4, 4, 1.0, 800, 1, NULL, NOW(6), NOW(6))
      ,(5, 5, 3.0, 234, 1, NULL, NOW(6), NOW(6))
      ,(6, 6, 2.0, 345, 1, NULL, NOW(6), NOW(6))
      ,(7, 7, 1.0, 795, 1, NULL, NOW(6), NOW(6))
      ,(8, 8, 10.0, 233, 1, NULL, NOW(6), NOW(6))
      ,(9, 9, 2.5, 123, 1, NULL, NOW(6), NOW(6))
      ,(10, 10, 3.0, NULL, 1, NULL, NOW(6), NOW(6))
      ,(11, 11, 2.0, 367, 1, NULL, NOW(6), NOW(6))
      ,(12, 12, 1.0, 467, 1, NULL, NOW(6), NOW(6))
      ,(13, 13, 5.0, 20, 1, NULL, NOW(6), NOW(6));


-- Step : 04.5
/*********************************************************************************
-- Doel : Voeg 12 rijen toe met INSERT aan koppeltabel ProductPerAllergeen
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		12 rijen gevoegd met insert aan tabel ProductPerAllergeen 
**********************************************************************************/ 
INSERT INTO ProductPerAllergeen
(
     Id
    ,ProductId
    ,AllergeenId
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 2, 1, NULL, NOW(6), NOW(6))
      ,(2, 1, 1, 1, NULL, NOW(6), NOW(6))
      ,(3, 1, 3, 1, NULL, NOW(6), NOW(6))
      ,(4, 3, 4, 1, NULL, NOW(6), NOW(6))
      ,(5, 6, 5, 1, NULL, NOW(6), NOW(6))
      ,(6, 9, 2, 1, NULL, NOW(6), NOW(6))
      ,(7, 9, 5, 1, NULL, NOW(6), NOW(6))
      ,(8, 10, 2, 1, NULL, NOW(6), NOW(6))
      ,(9, 12, 4, 1, NULL, NOW(6), NOW(6))
      ,(10, 13, 1, 1, NULL, NOW(6), NOW(6))
      ,(11, 13, 4, 1, NULL, NOW(6), NOW(6))
      ,(12, 13, 5, 1, NULL, NOW(6), NOW(6));


-- Step : 04.6
/*********************************************************************************
-- Doel : Voeg 17 rijen toe met INSERT aan koppeltabel ProductPerLeverancier
-- *******************************************************************************
-- Versie     Datum          Auteur			Omschrijving
-- ******     **********     **********		**************
-- 01         13-09-2026     KadhimH		17 rijen gevoegd met insert aan tabel ProductPerLeverancier 
**********************************************************************************/ 
INSERT INTO ProductPerLeverancier
(
     Id
    ,LeverancierId
    ,ProductId
    ,DatumLevering
    ,Aantal
    ,DatumEerstVolgendeLevering
    ,IsActief
    ,Opmerking
    ,DatumAangemaakt
    ,DatumGewijzigd
)
VALUES (1, 1, 1, '2024-10-09', 23, '2024-10-16', 1, NULL, NOW(6), NOW(6))
      ,(2, 1, 1, '2024-10-18', 21, '2024-10-25', 1, NULL, NOW(6), NOW(6))
      ,(3, 1, 2, '2024-10-09', 12, '2024-10-16', 1, NULL, NOW(6), NOW(6))
      ,(4, 1, 3, '2024-10-10', 11, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(5, 2, 4, '2024-10-14', 16, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(6, 2, 4, '2024-10-21', 23, '2024-10-28', 1, NULL, NOW(6), NOW(6))
      ,(7, 2, 5, '2024-10-14', 45, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(8, 2, 6, '2024-10-14', 30, '2024-10-21', 1, NULL, NOW(6), NOW(6))
      ,(9, 3, 7, '2024-10-12', 12, '2024-10-19', 1, NULL, NOW(6), NOW(6))
      ,(10, 3, 7, '2024-10-19', 23, '2024-10-26', 1, NULL, NOW(6), NOW(6))
      ,(11, 3, 8, '2024-10-10', 12, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(12, 3, 9, '2024-10-11', 1, '2024-10-18', 1, NULL, NOW(6), NOW(6))
      ,(13, 4, 10, '2024-10-16', 24, '2024-10-30', 1, NULL, NOW(6), NOW(6))
      ,(14, 5, 11, '2024-10-10', 47, '2024-10-17', 1, NULL, NOW(6), NOW(6))
      ,(15, 5, 11, '2024-10-19', 60, '2024-10-26', 1, NULL, NOW(6), NOW(6))
      ,(16, 5, 12, '2024-10-11', 45, NULL, 1, NULL, NOW(6), NOW(6))
      ,(17, 5, 13, '2024-10-12', 23, NULL, 1, NULL, NOW(6), NOW(6));


DELIMITER $$

CREATE PROCEDURE sp_GetLeveranciersOverzicht()
BEGIN
    SELECT
        l.Id,
        l.Naam,
        l.ContactPersoon,
        l.LeverancierNummer,
        l.Mobiel,
        COUNT(DISTINCT pl.ProductId) AS AantalProducten
    FROM Leverancier l
    LEFT JOIN ProductPerLeverancier pl
        ON pl.LeverancierId = l.Id
    GROUP BY
        l.Id,
        l.Naam,
        l.ContactPersoon,
        l.LeverancierNummer,
        l.Mobiel
    ORDER BY AantalProducten DESC, l.Naam ASC;
END$$

CREATE PROCEDURE sp_GetGeleverdeProducten(IN p_leverancier_id INT)
BEGIN
    SELECT
        p.Id AS ProductId,
        p.Naam,
        m.AantalAanwezig,
        m.VerpakkingsEenheid,
        MAX(pl.DatumLevering) AS LaatsteLevering
    FROM ProductPerLeverancier pl
    JOIN Product p
        ON p.Id = pl.ProductId
    LEFT JOIN Magazijn m
        ON m.ProductId = p.Id
    WHERE pl.LeverancierId = p_leverancier_id
    GROUP BY
        p.Id,
        p.Naam,
        m.AantalAanwezig,
        m.VerpakkingsEenheid
    ORDER BY m.AantalAanwezig DESC, p.Naam ASC;
END$$

CREATE PROCEDURE sp_RegistreerLevering(
    IN p_leverancierId INT,
    IN p_productId INT,
    IN p_datumLevering DATE,
    IN p_aantal INT UNSIGNED,
    IN p_datumEerstVolgendeLevering DATE,
    IN p_opmerking VARCHAR(250),
    OUT p_result INT
)
proc: BEGIN
    DECLARE v_magazijn_id INT UNSIGNED;
    DECLARE v_huidige_voorraad INT UNSIGNED;
    DECLARE v_is_actief BIT;
    DECLARE v_relation_exists INT DEFAULT 0;
    DECLARE v_duplicate_id INT DEFAULT NULL;

    SET p_result = 0;

    IF p_aantal IS NULL OR p_aantal < 1 THEN
        SET p_result = 7;
        LEAVE proc;
    END IF;

    SELECT COUNT(*) INTO v_relation_exists
    FROM ProductPerLeverancier
    WHERE LeverancierId = p_leverancierId
      AND ProductId = p_productId;

    IF v_relation_exists = 0 THEN
        SET p_result = 2;
        LEAVE proc;
    END IF;

    SET v_is_actief = (SELECT IsActief FROM Product WHERE Id = p_productId);

    IF COALESCE(v_is_actief, 0) = 0 THEN
        SET p_result = 3;
        LEAVE proc;
    END IF;

    SET v_magazijn_id = NULL;
    SET v_huidige_voorraad = NULL;

    SELECT Id, AantalAanwezig INTO v_magazijn_id, v_huidige_voorraad
    FROM Magazijn
    WHERE ProductId = p_productId
    FOR UPDATE;

    IF v_magazijn_id IS NULL THEN
        SET p_result = 4;
        LEAVE proc;
    END IF;

    SELECT Id INTO v_duplicate_id
    FROM ProductPerLeverancier
    WHERE LeverancierId = p_leverancierId
      AND ProductId = p_productId
      AND DatumLevering = p_datumLevering
    LIMIT 1
    FOR UPDATE;

    IF v_duplicate_id IS NOT NULL THEN
        SET p_result = 5;
        LEAVE proc;
    END IF;

    IF CAST(COALESCE(v_huidige_voorraad, 0) AS UNSIGNED) + p_aantal > 4294967295 THEN
        SET p_result = 6;
        LEAVE proc;
    END IF;

    UPDATE Magazijn
    SET AantalAanwezig = COALESCE(v_huidige_voorraad, 0) + p_aantal,
        DatumGewijzigd = NOW(6)
    WHERE Id = v_magazijn_id;

    INSERT INTO ProductPerLeverancier (
        LeverancierId,
        ProductId,
        DatumLevering,
        Aantal,
        DatumEerstVolgendeLevering,
        IsActief,
        Opmerking,
        DatumAangemaakt,
        DatumGewijzigd
    ) VALUES (
        p_leverancierId,
        p_productId,
        p_datumLevering,
        p_aantal,
        p_datumEerstVolgendeLevering,
        1,
        p_opmerking,
        NOW(6),
        NOW(6)
    );

    SET p_result = 1;
END proc$$

DELIMITER ;
