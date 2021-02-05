UPDATE tb_mapa SET zona = 3 WHERE x = 6 AND y = 4;
UPDATE tb_mapa SET zona = 3 WHERE x = 26 AND y = 7;
UPDATE tb_mapa SET zona = 3 WHERE x = 57 AND y = 26;
UPDATE tb_mapa SET zona = 3 WHERE x = 72 AND y = 6;
UPDATE tb_mapa SET zona = 3 WHERE x = 59 AND y = 31;

UPDATE tb_mapa SET zona = 3 WHERE x = 113 AND y = 19;
UPDATE tb_mapa SET zona = 3 WHERE x = 133 AND y = 30;
UPDATE tb_mapa SET zona = 3 WHERE x = 156 AND y = 31;
UPDATE tb_mapa SET zona = 3 WHERE x = 182 AND y = 6;
UPDATE tb_mapa SET zona = 3 WHERE x = 188 AND y = 35;

UPDATE tb_mapa SET zona = 3 WHERE x = 107 AND y = 92;
UPDATE tb_mapa SET zona = 3 WHERE x = 134 AND y = 77;
UPDATE tb_mapa SET zona = 3 WHERE x = 181 AND y = 68;
UPDATE tb_mapa SET zona = 3 WHERE x = 188 AND y = 92;
UPDATE tb_mapa SET zona = 3 WHERE x = 164 AND y = 93;

UPDATE tb_mapa SET zona = 3 WHERE x = 4 AND y = 79;
UPDATE tb_mapa SET zona = 3 WHERE x = 33 AND y = 85;
UPDATE tb_mapa SET zona = 3 WHERE x = 16 AND y = 95;
UPDATE tb_mapa SET zona = 3 WHERE x = 69 AND y = 93;
UPDATE tb_mapa SET zona = 3 WHERE x = 91 AND y = 68;

UPDATE tb_mapa SET zona = 18 WHERE x = 7 AND y = 60;
UPDATE tb_mapa SET zona = 5 WHERE x = 33 AND y = 41;
UPDATE tb_mapa SET zona = 5 WHERE x = 6 AND y = 50;
UPDATE tb_mapa SET zona = 5 WHERE x = 81 AND y = 55;

UPDATE tb_mapa SET zona = 18 WHERE x = 144 AND y = 46;

DROP EVENT atualiza_hp_berroso;

CREATE EVENT `atualiza_hp_berroso`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 04:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 1000000 WHERE real_boss_id = 5;
  END;

DROP PROCEDURE atualiza_ventos_correntes;

CREATE PROCEDURE `atualiza_ventos_correntes`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    UPDATE tb_mapa
    SET tipo_vento = 0, dir_vento = 0
    WHERE tipo_vento <> 0 OR tipo_corrente <> 0;

    UPDATE tb_mapa
    SET tipo_vento = FLOOR(1 + rand() * 4), dir_vento = FLOOR(1 + rand() * 8)
    WHERE navegavel = 1 AND mar <> 7
    ORDER BY RAND()
    LIMIT 2000;

    UPDATE tb_mapa
    SET tipo_corrente = 0, dir_corrente = 0
    WHERE tipo_corrente <> 0 OR dir_corrente <> 0;

    UPDATE tb_mapa
    SET tipo_corrente = FLOOR(1 + rand() * 6), dir_corrente = FLOOR(1 + rand() * 8)
    WHERE navegavel = 1 AND mar <> 7
    ORDER BY RAND()
    LIMIT 2000;
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (75);