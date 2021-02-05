CREATE TABLE tb_missoes_caca_diario
(
  id             BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id  INT UNSIGNED ZEROFILL       NOT NULL,
  missao_caca_id INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_missoes_caca_diario_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE PROCEDURE `reseta_missao_caca`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  TRUNCATE TABLE tb_missoes_caca_diario;


CREATE EVENT `reseta_missao_caca`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL reseta_missao_caca();
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (54);