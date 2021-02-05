ALTER TABLE tb_usuarios
  ADD free_reset_atributos INT UNSIGNED DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (104);