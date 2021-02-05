ALTER TABLE tb_personagens
  ADD excelencia_lvl INT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE tb_personagens
  ADD excelencia_xp INT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_personagens
  ADD excelencia_xp_max INT UNSIGNED DEFAULT 97500 NOT NULL;

ALTER TABLE tb_personagens
  ADD haki_hdr INT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_personagens
  MODIFY COLUMN haki_hdr INT UNSIGNED NOT NULL DEFAULT 0
  AFTER haki_cri;

INSERT INTO tb_skil_atk (cod_skil, consumo, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_classe, requisito_prof, dano, alcance, area, espera, categoria)
VALUES
  (3, 30, 0, 0, 0, 0, 1, 0, 0, 0, 40, 1, 1, 1, 0),
  (4, 30, 0, 0, 0, 0, 1, 0, 0, 0, 40, 1, 2, 2, 0),
  (5, 30, 0, 0, 0, 0, 1, 0, 0, 0, 40, 1, 3, 3, 0),
  (6, 30, 0, 0, 0, 0, 1, 0, 0, 0, 40, 1, 4, 4, 0),
  (7, 30, 0, 0, 0, 0, 1, 0, 0, 0, 45, 1, 5, 5, 0),
  (8, 30, 0, 0, 0, 0, 1, 0, 0, 0, 50, 1, 6, 6, 0),
  (9, 30, 0, 0, 0, 0, 1, 0, 0, 0, 55, 1, 7, 7, 0),
  (10, 30, 0, 0, 0, 0, 1, 0, 0, 0, 60, 1, 8, 8, 0),
  (11, 30, 0, 0, 0, 0, 1, 0, 0, 0, 65, 1, 9, 9, 0),
  (12, 30, 0, 0, 0, 0, 1, 0, 0, 0, 70, 1, 10, 10, 0),
  (13, 30, 0, 0, 0, 0, 1, 0, 0, 0, 75, 1, 11, 11, 0),
  (14, 30, 0, 0, 0, 0, 1, 0, 0, 0, 80, 1, 12, 12, 0);

INSERT INTO tb_migrations (cod_migration) VALUE (89);