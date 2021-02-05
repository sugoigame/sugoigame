ALTER TABLE tb_combate_log
  ADD vencedor INT UNSIGNED ZEROFILL NULL;
ALTER TABLE tb_combate_log
  ADD reputacao_ganha INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  ADD reputacao_perdida INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  ADD reputacao_mensal_ganha INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  ADD reputacao_mensal_perdida INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  ADD berries_ganhos INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  ADD berries_perdidos INT UNSIGNED NULL;


INSERT INTO tb_migrations (cod_migration) VALUE (37);