ALTER TABLE tb_conta
  ALTER COLUMN gold SET DEFAULT '25';

INSERT INTO tb_migrations (cod_migration) VALUE (31);