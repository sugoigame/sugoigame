ALTER TABLE tb_afilhados
  ADD berries_ganhos TINYINT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_afilhados
  ADD medalha_ganha TINYINT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_afilhados
  ADD bau_ganho TINYINT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_conta
  ADD medalhas_recrutamento INT UNSIGNED DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (68);