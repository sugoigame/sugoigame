ALTER TABLE tb_usuarios
  ADD campanha_impel_down INT UNSIGNED NULL;

ALTER TABLE tb_personagens
  ADD preso INT UNSIGNED DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (88);