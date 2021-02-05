CREATE TABLE tb_wanted_log
(
  id           INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  vencedor_cod INT UNSIGNED             NOT NULL,
  perdedor_cod INT UNSIGNED             NOT NULL,
  fa_ganha     INT UNSIGNED DEFAULT 0   NOT NULL,
  fa_perdida   INT UNSIGNED DEFAULT 0   NOT NULL,
  vencedor_lvl INT UNSIGNED             NULL,
  perdedor_lvl INT UNSIGNED             NULL,
  data         TIMESTAMP                         DEFAULT current_timestamp,
  CONSTRAINT tb_wanted_log_tb_personagens_vencedor_cod_fk FOREIGN KEY (vencedor_cod) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_wanted_log_tb_personagens_perdedor_cod_fk FOREIGN KEY (perdedor_cod) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (40);