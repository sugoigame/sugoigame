
CREATE PROCEDURE `respawna_nps_tesouro`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE c INT;

    SET c = 20 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 1 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 1 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 2 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 2 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 3 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 3 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 4 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 4 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 5 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 5 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 6 AND contem.nps_id = 8);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 8 FROM tb_mapa mapa WHERE mar = 6 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

  END;

CREATE EVENT `respawna_nps_tesouro`
  ON SCHEDULE EVERY 5 MINUTE
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL respawna_nps_tesouro();
  END;

DROP PROCEDURE respawna_nps;

CREATE PROCEDURE `respawna_nps`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE c INT;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 1 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(1 + rand() * 3) FROM tb_mapa mapa WHERE mar = 1 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 2 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(1 + rand() * 3) FROM tb_mapa mapa WHERE mar = 2 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 3 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(1 + rand() * 3) FROM tb_mapa mapa WHERE mar = 3 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 4 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(1 + rand() * 3) FROM tb_mapa mapa WHERE mar = 4 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 5 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(4 + rand() * 2) FROM tb_mapa mapa WHERE mar = 5 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 150 - (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 6 AND contem.nps_id IS NOT NULL);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y,  FLOOR(6 + rand() * 2) FROM tb_mapa mapa WHERE mar = 6 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (75);