ALTER TABLE tb_personagens
  ADD ativo TINYINT UNSIGNED DEFAULT 1 NOT NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (20);