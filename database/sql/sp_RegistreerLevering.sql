DROP PROCEDURE IF EXISTS sp_RegistreerLevering;

DELIMITER $$

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
