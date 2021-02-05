CREATE TABLE tb_tripulacao_formacao
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  formacao_id   VARCHAR(255)                NOT NULL,
  personagem_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_tripulacao_formacao_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_tripulacao_formacao_tb_personagens_cod_fk FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_vip
  ADD formacoes INT(1) DEFAULT 0 NULL;
ALTER TABLE tb_vip
  ADD formacoes_duracao DOUBLE DEFAULT 0 NULL;

ALTER TABLE tb_titulos
  ADD nome_f VARCHAR(100) NULL;

ALTER TABLE tb_personagens
  ADD sexo INT UNSIGNED DEFAULT 0 NOT NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (92);