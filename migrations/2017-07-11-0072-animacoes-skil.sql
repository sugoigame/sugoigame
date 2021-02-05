CREATE TABLE tb_tripulacao_animacoes_skills
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  effect        VARCHAR(255)                NOT NULL,
  quant         INT UNSIGNED                         DEFAULT 0
);

ALTER TABLE tb_tripulacao_animacoes_skills
  ADD CONSTRAINT tb_tripulacao_animacoes_skills_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_personagens_skil
  ADD effect VARCHAR(255) DEFAULT 'Atingir fisicamente' NULL;

ALTER TABLE tb_relatorio
  ADD effect VARCHAR(255) NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (72);