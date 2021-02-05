ALTER TABLE tb_item_missao
  ADD rdm_id INT UNSIGNED NULL;

CREATE PROCEDURE `respawna_nps_dia_pais`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE c INT;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 1 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 1 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 2 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 2 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 3 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 3 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 4 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 4 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 5 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 5 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

    SET c = 20 - (SELECT count(contem.increment_id)
                  FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
                  WHERE mapa.mar = 6 AND contem.nps_id = 9);

    IF c > 0 THEN
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        (SELECT mapa.x, mapa.y, 9 FROM tb_mapa mapa WHERE mar = 6 AND navegavel = 1 ORDER BY RAND() LIMIT c);
    END IF;

  END;

CREATE EVENT `respawna_nps_dia_pais`
  ON SCHEDULE EVERY 5 MINUTE
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL respawna_nps_dia_pais();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (81);