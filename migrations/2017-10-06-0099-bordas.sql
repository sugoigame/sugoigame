ALTER TABLE tb_personagens
  ADD borda INT UNSIGNED DEFAULT 0 NULL;

CREATE TABLE tb_tripulacao_bordas
(
  id            BIGINT UNSIGNED PRIMARY KEY         NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL               NOT NULL,
  borda         INT UNSIGNED                        NOT NULL,
  momento       TIMESTAMP DEFAULT current_timestamp NOT NULL,
  CONSTRAINT tb_tripulacao_bordas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_combate_special_effect
  ADD personagem_bot_id BIGINT UNSIGNED NULL;
ALTER TABLE tb_combate_special_effect
  MODIFY tripulacao_id INT(10) UNSIGNED ZEROFILL;
ALTER TABLE tb_combate_special_effect
  MODIFY personagem_id INT(10) UNSIGNED ZEROFILL;
ALTER TABLE tb_combate_special_effect
  ADD bot_id BIGINT UNSIGNED NULL;
ALTER TABLE tb_combate_special_effect
  MODIFY combate_id BIGINT(20) UNSIGNED;

ALTER TABLE tb_personagens
  ADD time_casual TINYINT DEFAULT 0 NOT NULL;
ALTER TABLE tb_personagens
  ADD time_competitivo TINYINT DEFAULT 0 NOT NULL;

ALTER TABLE tb_coliseu_fila
  ADD busca_casual TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_coliseu_fila
  ADD busca_competitivo TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_coliseu_fila
  ADD busca_coliseu TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_coliseu_fila
  ADD desafio_tipo INT UNSIGNED NULL;

ALTER TABLE tb_missoes_chefe_ilha
  ADD recompensa_recebida TINYINT DEFAULT 0 NOT NULL;

UPDATE tb_realizacoes
SET titulo = 0
WHERE tipo = 0 AND categoria = 4;

ALTER TABLE tb_usuarios
  ADD missoes_automaticas TINYINT DEFAULT 0 NOT NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (99);