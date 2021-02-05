ALTER TABLE tb_usuarios
  ADD advertencia INT DEFAULT 0 NOT NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (39);