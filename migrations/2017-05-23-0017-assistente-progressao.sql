ALTER TABLE tb_usuarios
  ADD progress INT UNSIGNED DEFAULT 0 NOT NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (17);