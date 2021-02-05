ALTER TABLE tb_usuarios
  ADD direcao_navio INT UNSIGNED DEFAULT 3 NULL;
ALTER TABLE tb_usuarios
  ADD skin_navio INT UNSIGNED DEFAULT 0 NULL;

CREATE TABLE tb_tripulacao_skin_navio
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  skin_id       INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_tripulacao_skin_navio_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_tripulacao_skin_navio
  ADD conta_id INT UNSIGNED ZEROFILL NULL;
ALTER TABLE tb_tripulacao_skin_navio
  ADD CONSTRAINT tb_tripulacao_skin_navio_tb_conta_conta_id_fk
FOREIGN KEY (conta_id) REFERENCES tb_conta (conta_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
ALTER TABLE tb_tripulacao_skin_navio
  DROP FOREIGN KEY tb_tripulacao_skin_navio_tb_usuarios_id_fk;
ALTER TABLE tb_tripulacao_skin_navio
  MODIFY tripulacao_id INT(10) UNSIGNED ZEROFILL;
ALTER TABLE tb_tripulacao_skin_navio
  ADD CONSTRAINT tb_tripulacao_skin_navio_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE SET NULL
  ON UPDATE CASCADE;
ALTER TABLE tb_tripulacao_skin_navio
  MODIFY COLUMN conta_id INT UNSIGNED ZEROFILL AFTER id;

INSERT INTO tb_migrations (cod_migration) VALUE (57);