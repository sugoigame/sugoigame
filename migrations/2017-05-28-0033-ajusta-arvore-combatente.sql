UPDATE tb_skil_atk
SET dano = 40, consumo = 2, espera = 0
WHERE cod_skil = 155;

UPDATE tb_skil_atk
SET dano = 50, consumo = 3, espera = 1
WHERE cod_skil = 156;

DELETE FROM tb_skil_atk
WHERE requisito_prof = 9 AND requisito_lvl >= 3;

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 3, 5000, 9, 8, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 4, 10000, 9, 7, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 5, 20000, 9, 4, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 6, 50000, 9, 6, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 7, 100000, 9, 3, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 8, 200000, 9, 5, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 9, 500000, 9, 2, 5);

INSERT INTO tb_skil_passiva
(categoria, requisito_classe, requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt, requisito_lvl, requisito_berries, requisito_prof, bonus_atr, bonus_atr_qnt)
VALUES (0, 0, 0, 0, 0, 0, 10, 1000000, 9, 1, 5);

INSERT INTO tb_migrations (cod_migration) VALUE (33);