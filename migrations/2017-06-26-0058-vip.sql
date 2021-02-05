ALTER TABLE tb_vip
  ADD conhecimento INT(1) DEFAULT 0 NULL;
ALTER TABLE tb_vip
  ADD conhecimento_duracao DOUBLE DEFAULT 0 NULL;

ALTER TABLE tb_vip
  ADD coup_de_burst INT(1) DEFAULT 0 NULL;
ALTER TABLE tb_vip
  ADD coup_de_burst_duracao DOUBLE DEFAULT 0 NULL;

ALTER TABLE tb_usuarios
  ADD coup_de_burst_usado TINYINT DEFAULT 0 NULL;

CREATE PROCEDURE `reseta_coup_de_burst`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  UPDATE tb_vip
  SET coup_de_burst = 5
  WHERE coup_de_burst_duracao > 0;


CREATE EVENT `reseta_coup_de_burst`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL reseta_coup_de_burst();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (58);