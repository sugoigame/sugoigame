ALTER TABLE tb_usuarios
  ADD battle_points INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_usuarios
  ADD battle_lvl INT UNSIGNED DEFAULT 1 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (97);