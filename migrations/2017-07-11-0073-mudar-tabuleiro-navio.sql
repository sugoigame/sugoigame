ALTER TABLE tb_usuarios
  ADD skin_tabuleiro_navio INT UNSIGNED DEFAULT '0' NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (73);