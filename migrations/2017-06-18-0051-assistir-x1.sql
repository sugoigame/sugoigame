ALTER TABLE tb_combate
  ADD permite_apostas_1 TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_combate
  ADD permite_apostas_2 TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_combate
  ADD premio_apostas INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_combate
  ADD preco_apostas INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_combate
  ADD fim_apostas TINYINT DEFAULT 0 NULL;

CREATE TABLE tb_combate_apostas
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  combate_id    BIGINT UNSIGNED ZEROFILL    NOT NULL,
  aposta        INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_combate_apostas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_combate_apostas_tb_combate_combate_fk FOREIGN KEY (combate_id) REFERENCES tb_combate (combate)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (51);