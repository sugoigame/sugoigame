ALTER TABLE tb_usuarios
  ADD credito_skin INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_usuarios
  ADD credito_skin_navio INT UNSIGNED DEFAULT 0 NULL;

CREATE TABLE tb_recompensa_recebida_grandes_poderes
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_recompensa_recebida_grandes_poderes_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_recompensa_recebida_era
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_recompensa_recebida_era_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_recompensa_recebida_haki
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_recompensa_recebida_haki_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (86);