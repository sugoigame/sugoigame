CREATE TABLE tb_incursao_recompensa_recebida
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  nivel         INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_incursa_recompensa_recebida_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

DROP EVENT reset_incursoes;
CREATE EVENT `reset_incursoes`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-11 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    TRUNCATE tb_incursao_progresso;
    TRUNCATE tb_incursao_recompensa_recebida;
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (83);