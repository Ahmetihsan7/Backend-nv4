# Database Specificatie Tabel - Magazijn Jamin

**Opdracht:** BE-opdracht 01 en 02
**Auteur:** KadhimH  
**Klas:** IO-SD-2509*  
**Actieve database gecontroleerd:** `laravel`
**Nieuwe installatie:** database `Jamin`, via `db/createscript.sql` (alleen een lege database)

---

## 1. Stamtabellen

### 1.1 Tabel: `Product`
Beschrijving: Bevat de basisgegevens van alle snoepgoedproducten.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier van het product |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het snoepgoedproduct |
| `Barcode` | VARCHAR | 13 | Nee | - | - | Streepjescode (EAN) |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

### 1.2 Tabel: `Leverancier`
Beschrijving: Bevat de contact- en identificatiegegevens van leveranciers.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier van de leverancier |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het leveranciersbedrijf |
| `ContactPersoon` | VARCHAR | 100 | Nee | - | - | Naam van de vaste contactpersoon |
| `LeverancierNummer` | VARCHAR | 20 | Nee | - | - | Uniek nummer van de leverancier (bijv. L1029384719) |
| `Mobiel` | VARCHAR | 15 | Nee | - | - | Mobiel telefoonnummer contactpersoon |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

### 1.3 Tabel: `Allergeen`
Beschrijving: Bevat de lijst met bekende allergenen die in snoepgoed kunnen voorkomen.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier van het allergeen |
| `Naam` | VARCHAR | 100 | Nee | - | - | Naam van het allergeen (bijv. Gluten, Soja) |
| `Omschrijving` | VARCHAR | 250 | Nee | - | - | Beschrijving van het allergeen |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

---

## 2. Koppeltabellen en Afhankelijke Tabellen

### 2.1 Tabel: `Magazijn`
Beschrijving: Bevat de actuele voorraad en verpakkingseenheid van producten in het magazijn.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier voorraadregel |
| `ProductId` | TINYINT UNSIGNED | - | Nee | FK naar `Product(Id)` | - | Referentie naar het gekoppelde product |
| `VerpakkingsEenheid` | DECIMAL | 8,3 | Nee | - | - | Gewicht per verpakkingseenheid in kg |
| `AantalAanwezig` | INT UNSIGNED | 10 | Ja | - | NULL | Aantal verpakkingen op voorraad (NULL = geen voorraad) |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_Magazijn_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.

---

### 2.2 Tabel: `ProductPerAllergeen`
Beschrijving: Koppeltabel tussen producten en allergenen (veel-op-veel relatie).

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier koppelregel |
| `ProductId` | TINYINT UNSIGNED | - | Nee | FK naar `Product(Id)` | - | Referentie naar product |
| `AllergeenId` | TINYINT UNSIGNED | - | Nee | FK naar `Allergeen(Id)` | - | Referentie naar allergeen |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_ProductPerAllergeen_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.
- `FK_ProductPerAllergeen_AllergeenId`: `AllergeenId` verwijst naar `Allergeen(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.

---

### 2.3 Tabel: `ProductPerLeverancier`
Beschrijving: Registreert leveringen van producten door leveranciers inclusief verwachte vervolglevering.

| Veldnaam | Datatype | Lengte / Precisie | Nullable | Sleutel | Standaardwaarde | Beschrijving |
|---|---|---|---|---|---|---|
| `Id` | TINYINT UNSIGNED | - | Nee | PK (Auto Increment) | - | Unieke identifier leveringsregel |
| `LeverancierId` | TINYINT UNSIGNED | - | Nee | FK naar `Leverancier(Id)` | - | Referentie naar de leverancier |
| `ProductId` | TINYINT UNSIGNED | - | Nee | FK naar `Product(Id)` | - | Referentie naar het geleverde product |
| `DatumLevering` | DATE | - | Nee | - | - | Datum van levering |
| `Aantal` | INT UNSIGNED | 10 | Nee | - | - | Aantal geleverde eenheden |
| `DatumEerstVolgendeLevering` | DATE | - | Ja | - | NULL | Verwachte datum van eerstvolgende levering |
| `IsActief` | TINYINT | 1 | Nee | - | 1 | Geeft aan of record actief is |
| `Opmerking` | VARCHAR | 250 | Ja | - | NULL | Optionele opmerkingen |
| `DatumAangemaakt` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van creatie record |
| `DatumGewijzigd` | DATETIME | 6 | Nee | - | CURRENT_TIMESTAMP(6) | Tijdstip van laatste wijziging |

**Foreign Keys:**
- `FK_ProductPerLeverancier_LeverancierId`: `LeverancierId` verwijst naar `Leverancier(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.
- `FK_ProductPerLeverancier_ProductId`: `ProductId` verwijst naar `Product(Id)` met `ON DELETE CASCADE ON UPDATE CASCADE`.

---

## BE-opdracht 02: aanvullende controle

Alle zes tabellen bevatten de systeemvelden `IsActief` (`TINYINT(1)`), `Opmerking` (`VARCHAR(250)`, nullable), `DatumAangemaakt` (`DATETIME(6)`) en `DatumGewijzigd` (`DATETIME(6)`). De datums zijn verplicht en hebben `CURRENT_TIMESTAMP(6)` als standaard; `DatumGewijzigd` wordt bij updates automatisch bijgewerkt.

De gecontroleerde actieve database bevatte:

| Tabel | Rijen |
|---|---:|
| `Product` | 13 |
| `Magazijn` | 13 |
| `Allergeen` | 5 |
| `ProductPerAllergeen` | 12 |
| `Leverancier` | 6 |
| `ProductPerLeverancier` | 17 |

De testgegevens bevatten onder meer Venco/Mintnopjes (voorraad 453), Basset/Winegums (inactief product) en Quality Street (geen geleverde producten). De database heeft primary keys op alle tabellen en foreign keys van `Magazijn.ProductId` naar `Product.Id`, `ProductPerAllergeen` naar zowel `Product` als `Allergeen`, en `ProductPerLeverancier` naar zowel `Leverancier` als `Product`.

De drie gebruikte MySQL stored procedures zijn `sp_GetLeveranciersOverzicht`, `sp_GetGeleverdeProducten` en `sp_RegistreerLevering`. De eerste twee tellen unieke producten en tonen de meest recente leveringsdatum; de laatste controleert onder andere of een product actief is voordat voorraad en leveringsrecord worden bijgewerkt. Het complete verse-installatiescript staat in `db/createscript.sql`. Voer dit script niet uit op de bestaande `laravel`-database.
