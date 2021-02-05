CREATE TABLE tb_evento_amizade_recompensa
(
  tripulacao_id INT(10) UNSIGNED ZEROFILL NOT NULL,
  recompensa_id INT(10) UNSIGNED          NOT NULL,
  CONSTRAINT tb_evento_amizade_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_evento_amizade_tb_usuarios_id_fk
  ON tb_evento_recompensa (tripulacao_id);

CREATE TABLE tb_evento_amizade_brindes
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED,
  CONSTRAINT tb_evento_amizade_brindes_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (76);