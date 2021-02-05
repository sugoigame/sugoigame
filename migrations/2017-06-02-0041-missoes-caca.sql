ALTER TABLE tb_usuarios
  ADD missao_caca INT UNSIGNED DEFAULT NULL  NULL;
ALTER TABLE tb_usuarios
  ADD missao_caca_progress INT UNSIGNED DEFAULT NULL  NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (41);