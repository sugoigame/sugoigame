ALTER TABLE tb_personagens_skil
  ADD special_effect INT UNSIGNED NULL;
ALTER TABLE tb_personagens_skil
  ADD special_target INT UNSIGNED NULL;
ALTER TABLE tb_personagens_skil
  ADD special_apply_type INT UNSIGNED NULL;

CREATE TABLE tb_combate_special_effect
(
  id             BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  combate_id     BIGINT UNSIGNED             NOT NULL,
  tripulacao_id  INT UNSIGNED ZEROFILL       NOT NULL,
  personagem_id  INT UNSIGNED ZEROFILL       NOT NULL,
  special_effect INT                         NOT NULL,
  duracao        INT UNSIGNED                NOT NULL
);

ALTER TABLE tb_combate_special_effect
  ADD CONSTRAINT tb_combate_special_effect_tb_combate_combate_fk
FOREIGN KEY (combate_id) REFERENCES tb_combate (combate)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
ALTER TABLE tb_combate_special_effect
  ADD CONSTRAINT tb_combate_special_effect_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;
ALTER TABLE tb_combate_special_effect
  ADD CONSTRAINT tb_combate_special_effect_tb_personagens_cod_fk
FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

INSERT INTO tb_migrations (cod_migration) VALUE (80);