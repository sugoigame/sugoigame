ALTER TABLE tb_wanted_log
  ADD fa_anterior_vencedor INT UNSIGNED NULL;
ALTER TABLE tb_wanted_log
  ADD fa_anterior_perdedor INT UNSIGNED NULL;
ALTER TABLE tb_combate_log
  MODIFY reputacao_anterior_vencedor INT(10) UNSIGNED NULL;
ALTER TABLE tb_combate_log
  MODIFY reputacao_anterior_perdedor INT(10) UNSIGNED NULL;
ALTER TABLE tb_combate_log
  MODIFY reputacao_mensal_anterior_vencedor INT(10) UNSIGNED NULL;
ALTER TABLE tb_combate_log
  MODIFY reputacao_mensal_anterior_perdedor INT(10) UNSIGNED NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (52);