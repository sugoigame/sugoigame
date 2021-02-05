TRUNCATE tb_evento_recompensa;

UPDATE tb_mapa SET zona = 22 WHERE x = 6 AND y = 4;
UPDATE tb_mapa SET zona = 22 WHERE x = 26 AND y = 7;
UPDATE tb_mapa SET zona = 22 WHERE x = 57 AND y = 26;
UPDATE tb_mapa SET zona = 22 WHERE x = 72 AND y = 6;
UPDATE tb_mapa SET zona = 22 WHERE x = 59 AND y = 31;

UPDATE tb_mapa SET zona = 22 WHERE x = 113 AND y = 19;
UPDATE tb_mapa SET zona = 22 WHERE x = 133 AND y = 30;
UPDATE tb_mapa SET zona = 22 WHERE x = 156 AND y = 31;
UPDATE tb_mapa SET zona = 22 WHERE x = 182 AND y = 6;
UPDATE tb_mapa SET zona = 22 WHERE x = 188 AND y = 35;

UPDATE tb_mapa SET zona = 22 WHERE x = 107 AND y = 92;
UPDATE tb_mapa SET zona = 22 WHERE x = 134 AND y = 77;
UPDATE tb_mapa SET zona = 22 WHERE x = 181 AND y = 68;
UPDATE tb_mapa SET zona = 22 WHERE x = 188 AND y = 92;
UPDATE tb_mapa SET zona = 22 WHERE x = 164 AND y = 93;

UPDATE tb_mapa SET zona = 22 WHERE x = 4 AND y = 79;
UPDATE tb_mapa SET zona = 22 WHERE x = 33 AND y = 85;
UPDATE tb_mapa SET zona = 22 WHERE x = 16 AND y = 95;
UPDATE tb_mapa SET zona = 22 WHERE x = 69 AND y = 93;
UPDATE tb_mapa SET zona = 22 WHERE x = 91 AND y = 68;

UPDATE tb_mapa SET zona = 22 WHERE x = 33 AND y = 41;
UPDATE tb_mapa SET zona = 22 WHERE x = 92 AND y = 60;
UPDATE tb_mapa SET zona = 22 WHERE x = 81 AND y = 55;

UPDATE tb_mapa SET zona = 22 WHERE x = 106 AND y = 55;

CREATE EVENT `atualiza_hp_cocofox`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 1000000 WHERE real_boss_id = 9;
  END;

ALTER TABLE tb_combate_personagens ADD img INT UNSIGNED NULL;
ALTER TABLE tb_combate_personagens ADD skin_r INT UNSIGNED NULL;
ALTER TABLE tb_combate_personagens ADD skin_c INT UNSIGNED NULL;

CREATE PROCEDURE `respawna_nps_fantasma`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE c INT;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 1 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 1 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 2 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 2 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 3 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 3 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 4 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 4 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 5 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 5 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 6 AND contem.nps_id = 11);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 11 FROM tb_mapa mapa WHERE mar = 6 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

  END;

CREATE EVENT `respawna_nps_fantasma`
  ON SCHEDULE EVERY 5 MINUTE
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL respawna_nps_fantasma();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (101);