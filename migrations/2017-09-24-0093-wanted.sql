ALTER TABLE tb_personagens
  ADD fa_premio INT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_usuarios
  ADD fa_premio_unico_1 TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_combate_personagens
  ADD fa_ganha BIGINT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_combate_special_effect
  ADD momento TIMESTAMP DEFAULT current_timestamp NULL;

ALTER TABLE tb_combate
  ADD inicio TIMESTAMP DEFAULT current_timestamp NULL;

ALTER TABLE tb_combate
  ADD inicio TIMESTAMP DEFAULT current_timestamp NULL;

ALTER TABLE tb_combate_log
  ADD fim TIMESTAMP NULL;

CREATE TABLE tb_combate_log_personagem_morto
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  combate       BIGINT UNSIGNED ZEROFILL    NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL,
  momento       TIMESTAMP                            DEFAULT current_timestamp
);
ALTER TABLE tb_combate_log_personagem_morto
  ADD personagem_id INT UNSIGNED ZEROFILL NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (93);