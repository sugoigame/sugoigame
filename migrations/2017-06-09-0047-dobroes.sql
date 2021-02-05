ALTER TABLE tb_conta
  ADD dobroes INT UNSIGNED DEFAULT 60 NOT NULL;
ALTER TABLE tb_conta
  ADD dobroes_criados INT UNSIGNED DEFAULT 0 NOT NULL;

CREATE TABLE tb_dobroes_oferta
(
  id             INT UNSIGNED PRIMARY KEY            NOT NULL AUTO_INCREMENT,
  tripulacao_id  INT UNSIGNED ZEROFILL               NOT NULL,
  quant          INT UNSIGNED                        NOT NULL,
  preco_unitario INT UNSIGNED                        NOT NULL,
  data           TIMESTAMP DEFAULT current_timestamp NOT NULL,
  CONSTRAINT tb_dobroes_oferta_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_dobroes_log
(
  id            INT UNSIGNED PRIMARY KEY            NOT NULL AUTO_INCREMENT,
  conta_id      INT UNSIGNED ZEROFILL               NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL               NOT NULL,
  quant         INT UNSIGNED                        NOT NULL,
  script        VARCHAR(255)                        NOT NULL,
  data          TIMESTAMP DEFAULT current_timestamp NOT NULL,
  CONSTRAINT tb_dobroes_log_tb_conta_conta_id_fk FOREIGN KEY (conta_id) REFERENCES tb_conta (conta_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_dobroes_log_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_dobroes_leilao_log
(
  id             INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  vendedor_id    INT UNSIGNED ZEROFILL    NOT NULL,
  comprador_id   INT UNSIGNED ZEROFILL    NOT NULL,
  quant          INT UNSIGNED             NOT NULL,
  preco_unitario INT UNSIGNED             NOT NULL,
  data           TIMESTAMP                         DEFAULT current_timestamp,
  CONSTRAINT tb_dobroes_leilao_log_tb_usuarios_id_fk_vendedor FOREIGN KEY (vendedor_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_dobroes_leilao_log_tb_usuarios_id_fk_comprador FOREIGN KEY (comprador_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_conta ALTER COLUMN gold SET DEFAULT 0;

INSERT INTO tb_migrations (cod_migration) VALUE (47);