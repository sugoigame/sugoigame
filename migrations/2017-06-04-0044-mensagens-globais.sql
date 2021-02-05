CREATE TABLE tb_mensagens_globais
(
  id       INT UNSIGNED PRIMARY KEY            NOT NULL AUTO_INCREMENT,
  assunto  VARCHAR(255)                        NOT NULL,
  mensagem TEXT                                NOT NULL,
  data     TIMESTAMP DEFAULT current_timestamp NOT NULL
);

CREATE TABLE tb_mensagens_globais_lidas
(
  id            INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  mensagem_id   INT UNSIGNED             NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL    NOT NULL,
  data_leitura  TIMESTAMP                         DEFAULT current_timestamp,
  CONSTRAINT tb_mensagens_globais_lidas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_mensagens_globais_lidas_tb_mensagens_globais_id_fk FOREIGN KEY (mensagem_id) REFERENCES tb_mensagens_globais (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (42);