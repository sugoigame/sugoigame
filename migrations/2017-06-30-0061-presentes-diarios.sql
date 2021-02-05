ALTER TABLE tb_usuarios
  ADD presente_diario_obtido TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_usuarios
  ADD presente_diario_count INT UNSIGNED DEFAULT 0 NULL;

CREATE PROCEDURE `reseta_presente_diario`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    UPDATE tb_usuarios
    SET presente_diario_count = 0
    WHERE presente_diario_obtido = 0;

    UPDATE tb_usuarios
    SET presente_diario_obtido = 0;

    UPDATE tb_usuarios
    SET presente_diario_count = 0
    WHERE presente_diario_count >= 30;
  END;

CREATE EVENT `reseta_presente_diario`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL reseta_presente_diario();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (61);