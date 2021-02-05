TRUNCATE tb_evento_recompensa;

UPDATE tb_mapa SET zona = 21 WHERE x = 72 AND y = 6;

UPDATE tb_mapa SET zona = 21 WHERE x = 182 AND y = 6;

UPDATE tb_mapa SET zona = 21 WHERE x = 188 AND y = 92;

UPDATE tb_mapa SET zona = 21 WHERE x = 69 AND y = 93;

UPDATE tb_mapa SET zona = 21 WHERE x = 92 AND y = 60;

UPDATE tb_mapa SET zona = 21 WHERE x = 106 AND y = 55;

CREATE EVENT `atualiza_hp_baiacao`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 1000000 WHERE real_boss_id = 8;
  END;


INSERT INTO tb_migrations (cod_migration) VALUE (95);