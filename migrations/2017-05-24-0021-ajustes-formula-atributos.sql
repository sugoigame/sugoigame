ALTER TABLE tb_personagens
  ALTER COLUMN pts SET DEFAULT '69';

INSERT INTO tb_migrations (cod_migration) VALUE (21);