ALTER TABLE tb_usuarios
  ADD batalhas_criancas INT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_personagens
  ADD temporario TINYINT DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (98);