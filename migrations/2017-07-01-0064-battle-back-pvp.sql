ALTER TABLE tb_combate
  ADD battle_back INT UNSIGNED NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (64);