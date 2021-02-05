ALTER TABLE tb_personagens_skil
  ADD editado TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_personagens
  ADD selos_xp INT UNSIGNED DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (96);