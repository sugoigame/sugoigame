ALTER TABLE tb_conta
  ALTER COLUMN gold SET DEFAULT '50';

UPDATE tb_conta
SET gold = gold + 25;

INSERT INTO tb_migrations (cod_migration) VALUE (36);