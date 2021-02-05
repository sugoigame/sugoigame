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

UPDATE tb_mapa SET zona = 5 WHERE x = 7 AND y = 60;
UPDATE tb_mapa SET zona = 5 WHERE x = 33 AND y = 41;
UPDATE tb_mapa SET zona = 5 WHERE x = 6 AND y = 50;
UPDATE tb_mapa SET zona = 5 WHERE x = 81 AND y = 55;

UPDATE tb_mapa SET zona = 15 WHERE x = 157 AND y = 59;
UPDATE tb_mapa SET zona = 16 WHERE x = 183 AND y = 44;
UPDATE tb_mapa SET zona = 17 WHERE x = 103 AND y = 60;

CREATE EVENT `atualiza_hp_figaro`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 23:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 6000000 WHERE real_boss_id = 1;
  END;

CREATE EVENT `atualiza_hp_raposa`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 05:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 6000000 WHERE real_boss_id = 2;
  END;

CREATE EVENT `atualiza_hp_popota`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 11:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 6000000 WHERE real_boss_id = 3;
  END;

CREATE EVENT `atualiza_hp_espetinho`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 17:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 6000000 WHERE real_boss_id = 4;
  END;

ALTER TABLE tb_alianca_missoes ADD boss_id INT UNSIGNED NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (55);