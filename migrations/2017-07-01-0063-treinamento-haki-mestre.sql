ALTER TABLE tb_personagens
  ADD haki_count_dias_treino INT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_personagens
  ADD haki_ultimo_dia_treino DATE NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (63);