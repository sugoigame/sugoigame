CREATE PROCEDURE `atualiza_ventos_correntes`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    UPDATE tb_mapa
    SET tipo_vento = 0, dir_vento = 0
    WHERE tipo_vento <> 0 OR tipo_corrente <> 0;

    UPDATE tb_mapa
    SET tipo_vento = FLOOR(1 + rand() * 4), dir_vento = FLOOR(1 + rand() * 8)
    WHERE navegavel = 1
    ORDER BY RAND()
    LIMIT 2000;

    UPDATE tb_mapa
    SET tipo_corrente = 0, dir_corrente = 0
    WHERE tipo_corrente <> 0 OR dir_corrente <> 0;

    UPDATE tb_mapa
    SET tipo_corrente = FLOOR(1 + rand() * 6), dir_corrente = FLOOR(1 + rand() * 8)
    WHERE navegavel = 1
    ORDER BY RAND()
    LIMIT 2000;
  END;

CREATE EVENT `atualiza_ventos_correntes`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL atualiza_ventos_correntes();
  END;

CREATE PROCEDURE `movimenta_nps`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    UPDATE tb_mapa_contem
    SET x = x + 1
    WHERE nps_id IS NOT NULL
    ORDER BY RAND()
    LIMIT 350;

    UPDATE tb_mapa_contem
    SET x = x - 1
    WHERE nps_id IS NOT NULL
    ORDER BY RAND()
    LIMIT 350;

    UPDATE tb_mapa_contem
    SET y = y + 1
    WHERE nps_id IS NOT NULL
    ORDER BY RAND()
    LIMIT 350;

    UPDATE tb_mapa_contem
    SET y = y - 1
    WHERE nps_id IS NOT NULL
    ORDER BY RAND()
    LIMIT 350;

    DELETE contem FROM tb_mapa_contem contem
      INNER JOIN tb_mapa mapa ON mapa.x = contem.x AND mapa.y = contem.y
    WHERE mapa.navegavel <> 1;
  END;

CREATE EVENT `movimenta_nps`
  ON SCHEDULE EVERY 5 MINUTE
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL movimenta_nps();
  END;


CREATE PROCEDURE `respawna_nps`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE c INT;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 1);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(1 + rand() * 100), FLOOR(1 + rand() * 59), FLOOR(1 + rand() * 3));

      SET c = c + 1;
    END WHILE;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 2);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(100 + rand() * 100), FLOOR(1 + rand() * 59), FLOOR(1 + rand() * 3));

      SET c = c + 1;
    END WHILE;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 3);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(1 + rand() * 100), FLOOR(50 + rand() * 50), FLOOR(1 + rand() * 3));

      SET c = c + 1;
    END WHILE;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 4);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(100 + rand() * 100), FLOOR(50 + rand() * 50), FLOOR(1 + rand() * 3));

      SET c = c + 1;
    END WHILE;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 5);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(1 + rand() * 100), FLOOR(41 + rand() * 20), FLOOR(4 + rand() * 2));

      SET c = c + 1;
    END WHILE;

    SET c = (SELECT count(contem.increment_id)
             FROM tb_mapa_contem contem INNER JOIN tb_mapa mapa ON contem.x = mapa.x AND contem.y = mapa.y
             WHERE mapa.mar = 6);

    WHILE c < 150 DO
      INSERT INTO tb_mapa_contem (x, y, nps_id)
        VALUE (FLOOR(100 + rand() * 99), FLOOR(40 + rand() * 20), FLOOR(6 + rand() * 2));

      SET c = c + 1;
    END WHILE;

    DELETE contem FROM tb_mapa_contem contem
      INNER JOIN tb_mapa mapa ON mapa.x = contem.x AND mapa.y = contem.y
    WHERE mapa.navegavel <> 1;
  END;

CREATE EVENT `respawna_nps`
  ON SCHEDULE EVERY 30 MINUTE
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL respawna_nps();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (50);