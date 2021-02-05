CREATE TABLE tb_tripulacao_skins
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  img           INT UNSIGNED                NOT NULL,
  skin          INT UNSIGNED                NOT NULL,
  data_compra   TIMESTAMP                            DEFAULT current_timestamp
);

ALTER TABLE tb_tripulacao_skins
  ADD CONSTRAINT tb_tripulacao_skins_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_tripulacao_skins
  ADD conta_id INT UNSIGNED ZEROFILL NULL;
ALTER TABLE tb_tripulacao_skins
  ADD CONSTRAINT tb_tripulacao_skins_tb_conta_conta_id_fk
FOREIGN KEY (conta_id) REFERENCES tb_conta (conta_id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
ALTER TABLE tb_tripulacao_skins
  DROP FOREIGN KEY tb_tripulacao_skins_tb_usuarios_id_fk;
ALTER TABLE tb_tripulacao_skins
  MODIFY tripulacao_id INT(10) UNSIGNED ZEROFILL;
ALTER TABLE tb_tripulacao_skins
  ADD CONSTRAINT tb_tripulacao_skins_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE SET NULL
  ON UPDATE SET NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (59);