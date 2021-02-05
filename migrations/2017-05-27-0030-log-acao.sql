CREATE TABLE tb_log_acesso
(
  conta_id      INT UNSIGNED ZEROFILL NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL,
  data          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  url           TEXT,
  CONSTRAINT tb_log_acesso_tb_conta_conta_id_fk FOREIGN KEY (conta_id) REFERENCES tb_conta (conta_id),
  CONSTRAINT tb_log_acesso_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
);


INSERT INTO tb_migrations (cod_migration) VALUE (30);