DELETE FROM tb_skil_atk
WHERE requisito_classe <> 0;
DELETE FROM tb_skil_buff
WHERE requisito_classe <> 0;
DELETE FROM tb_skil_passiva
WHERE requisito_classe <> 0;

DELETE FROM tb_personagens_skil
WHERE (tipo = 1 AND cod_skil <> 1) OR tipo = 2 OR tipo = 3;

-- atk espadachim arvore 1
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    1, 30,
    1, 30,
    3, 5000, 1, 0,
    40, 1, 1, 3,
   1),
  (15,
    1, 40,
    1, 40,
    5, 10000, 1, 0,
    70, 1, 1, 3,
   1),
  (15,
    1, 60,
    1, 60,
    15, 50000, 1, 0,
    100, 1, 1, 4,
   1),
  (15,
    1, 80,
    1, 80,
    30, 200000, 1, 0,
    100, 1, 1, 4,
   1),
  (15,
    1, 100,
    1, 100,
    50, 1000000, 1, 0,
    110, 1, 1, 4,
   1);

-- atk espadachim arvore 2
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    1, 30,
    1, 30,
    3, 5000, 1, 0,
    20, 2, 1, 3,
   2),
  (15,
    1, 40,
    1, 40,
    5, 10000, 1, 0,
    50, 3, 1, 3,
   2),
  (15,
    1, 60,
    1, 60,
    15, 50000, 1, 0,
    70, 3, 1, 4,
   2),
  (15,
    1, 80,
    1, 80,
    30, 200000, 1, 0,
    70, 3, 1, 4,
   2),
  (15,
    1, 100,
    1, 100,
    50, 1000000, 1, 0,
    70, 4, 1, 4,
   2);

-- atk espadachim arvore 3
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    1, 30,
    1, 30,
    3, 5000, 1, 0,
    15, 1, 2, 3,
   3),
  (15,
    1, 40,
    1, 40,
    5, 10000, 1, 0,
    15, 1, 3, 3,
   3),
  (15,
    1, 60,
    1, 60,
    15, 50000, 1, 0,
    20, 1, 4, 4,
   3),
  (15,
    1, 80,
    1, 80,
    30, 200000, 1, 0,
    20, 1, 4, 4,
   3),
  (15,
    1, 100,
    1, 100,
    50, 1000000, 1, 0,
    20, 1, 4, 4,
   3);

-- atk lutador arvore 1
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    4, 30,
    4, 30,
    3, 5000, 2, 0,
    40, 1, 1, 3,
   1),
  (15,
    4, 40,
    4, 40,
    5, 10000, 2, 0,
    70, 1, 1, 3,
   1),
  (15,
    4, 60,
    4, 60,
    15, 50000, 2, 0,
    100, 1, 1, 4,
   1),
  (15,
    4, 80,
    4, 80,
    30, 200000, 2, 0,
    100, 1, 1, 4,
   1),
  (15,
    4, 100,
    4, 100,
    50, 1000000, 2, 0,
    100, 1, 1, 4,
   1);

-- atk lutador arvore 2
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    4, 30,
    4, 30,
    3, 5000, 2, 0,
    20, 2, 1, 3,
   2),
  (15,
    4, 60,
    4, 60,
    15, 50000, 2, 0,
    70, 3, 1, 4,
   2),
  (15,
    4, 80,
    4, 80,
    30, 200000, 2, 0,
    70, 3, 1, 4,
   2),
  (15,
    4, 100,
    4, 100,
    50, 1000000, 2, 0,
    70, 4, 1, 4,
   2);

-- atk lutador arvore 3
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    4, 30,
    4, 30,
    3, 5000, 2, 0,
    15, 1, 2, 3,
   3),
  (15,
    4, 40,
    4, 40,
    5, 10000, 2, 0,
    15, 1, 3, 3,
   3),
  (15,
    4, 80,
    4, 80,
    30, 200000, 2, 0,
    20, 1, 4, 4,
   3),
  (15,
    4, 100,
    4, 100,
    50, 1000000, 2, 0,
    20, 1, 4, 4,
   3);

-- atk atirador arvore 1
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    5, 30,
    5, 30,
    3, 5000, 3, 0,
    40, 1, 1, 3,
   1),
  (15,
    5, 40,
    5, 40,
    5, 10000, 3, 0,
    70, 1, 1, 3,
   1),
  (15,
    5, 60,
    5, 60,
    15, 50000, 3, 0,
    100, 1, 1, 4,
   1),
  (15,
    5, 80,
    5, 80,
    30, 200000, 3, 0,
    100, 1, 1, 4,
   1),
  (15,
    5, 100,
    5, 100,
    50, 1000000, 3, 0,
    100, 1, 1, 4,
   1);

-- atk atirador arvore 2
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    5, 30,
    5, 30,
    3, 5000, 3, 0,
    20, 2, 1, 3,
   2),
  (15,
    5, 40,
    5, 40,
    5, 10000, 3, 0,
    50, 4, 1, 3,
   2),
  (15,
    5, 80,
    5, 80,
    30, 200000, 3, 0,
    70, 5, 1, 4,
   2),
  (15,
    5, 100,
    5, 100,
    50, 1000000, 3, 0,
    70, 10, 1, 4,
   2);

-- atk atirador arvore 3
INSERT INTO tb_skil_atk
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 dano, alcance, area, espera,
 categoria)
VALUES
  (10,
    5, 30,
    5, 30,
    3, 5000, 3, 0,
    15, 1, 2, 3,
   3),
  (15,
    5, 60,
    5, 60,
    15, 50000, 3, 0,
    20, 1, 4, 4,
   3),
  (15,
    5, 80,
    5, 80,
    30, 200000, 3, 0,
    20, 1, 4, 4,
   3),
  (15,
    5, 100,
    5, 100,
    50, 1000000, 3, 0,
    20, 1, 4, 4,
   3);

-- passiva espadachim arvore 1
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (1, 50,
      1, 50,
      10, 20000, 1, 0,
      1, 5,
      1),
  (1, 70,
      1, 70,
      20, 100000, 1, 0,
      1, 10,
      1),
  (1, 90,
      1, 90,
      40, 500000, 1, 0,
      1, 15,
      1);

-- passiva espadachim arvore 2
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (1, 50,
      1, 50,
      10, 20000, 1, 0,
      6, 5,
      2),
  (1, 70,
      1, 70,
      20, 100000, 1, 0,
      5, 10,
      2),
  (1, 90,
      1, 90,
      40, 500000, 1, 0,
      6, 15,
      2);

-- passiva espadachim arvore 3
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (1, 50,
      1, 50,
      10, 20000, 1, 0,
      5, 5,
      3),
  (1, 70,
      1, 70,
      20, 100000, 1, 0,
      3, 10,
      3),
  (1, 90,
      1, 90,
      40, 500000, 1, 0,
      4, 15,
      3);

-- passiva lutador arvore 1
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (4, 50,
      4, 50,
      10, 20000, 2, 0,
      2, 5,
      1),
  (4, 70,
      4, 70,
      20, 100000, 2, 0,
      2, 10,
      1),
  (4, 90,
      4, 90,
      40, 500000, 2, 0,
      2, 15,
      1);

-- passiva lutador arvore 2
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (4, 50,
      4, 50,
      10, 20000, 2, 0,
      4, 5,
      2),
  (4, 70,
      4, 70,
      20, 100000, 2, 0,
      4, 10,
      2),
  (4, 90,
      4, 90,
      40, 500000, 2, 0,
      4, 15,
      2);

-- passiva lutador arvore 3
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (4, 50,
      4, 50,
      10, 20000, 2, 0,
      3, 5,
      3),
  (4, 70,
      4, 70,
      20, 100000, 2, 0,
      1, 10,
      3),
  (4, 90,
      4, 90,
      40, 500000, 2, 0,
      7, 15,
      3);

-- passiva atirador arvore 1
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (5, 50,
      5, 50,
      10, 20000, 3, 0,
      1, 5,
      1),
  (5, 70,
      5, 70,
      20, 100000, 3, 0,
      3, 10,
      1),
  (5, 90,
      5, 90,
      40, 500000, 3, 0,
      1, 15,
      1);

-- passiva atirador arvore 2
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (5, 50,
      5, 50,
      10, 20000, 3, 0,
      5, 5,
      2),
  (5, 70,
      5, 70,
      20, 100000, 3, 0,
      5, 10,
      2),
  (5, 90,
      5, 90,
      40, 500000, 3, 0,
      5, 15,
      2);

-- passiva atirador arvore 3
INSERT INTO tb_skil_passiva
(requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 categoria)
VALUES
  (5, 50,
      5, 50,
      10, 20000, 3, 0,
      6, 5,
      3),
  (5, 70,
      5, 70,
      20, 100000, 3, 0,
      4, 10,
      3),
  (5, 90,
      5, 90,
      40, 500000, 3, 0,
      6, 15,
      3);

-- buffs lutador arvore 1

-- buffs lutador arvore 2
INSERT INTO tb_skil_buff
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 duracao, alcance, area, espera,
 categoria)
VALUES
  (100,
    4, 40,
    4, 40,
    5, 10000, 2, 0,
    5, 90,
   4, 1, 1, 8,
   2);

-- buffs lutador arvore 3
INSERT INTO tb_skil_buff
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 duracao, alcance, area, espera,
 categoria)
VALUES
  (100,
    4, 60,
    4, 60,
    15, 50000, 2, 0,
    2, 100,
   4, 1, 1, 8,
   3);

-- buffs atirador arvore 1

-- buffs atirador arvore 2
INSERT INTO tb_skil_buff
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 duracao, alcance, area, espera,
 categoria)
VALUES
  (100,
    5, 60,
    5, 60,
    15, 10000, 3, 0,
    1, 100,
   4, 1, 1, 8,
   2);

-- buffs atirador arvore 3
INSERT INTO tb_skil_buff
(consumo,
 requisito_atr_1, requisito_atr_1_qnt,
 requisito_atr_2, requisito_atr_2_qnt,
 requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
 bonus_atr, bonus_atr_qnt,
 duracao, alcance, area, espera,
 categoria)
VALUES
  (100,
    5, 40,
    5, 40,
    5, 50000, 3, 0,
    3, 100,
   4, 1, 1, 8,
   3);

UPDATE tb_akuma_skil_buff
SET espera = duracao * 2, consumo = 100;

UPDATE tb_skil_buff
SET espera = duracao * 2, consumo = 100;

DELETE FROM tb_personagens_skil
WHERE cod_skil = 165 AND tipo = 1;

UPDATE tb_personagens
SET haki_pts = 25, haki_cri = 0, haki_esq = 0, haki_blo = 0;


DELETE FROM tb_personagem_titulo
WHERE titulo = 93 OR titulo = 94;

INSERT INTO tb_ilha_itens (ilha, cod_item, tipo_item) VALUES
  (29, 167, 15),
  (29, 168, 15),
  (29, 169, 15),

  (30, 167, 15),
  (30, 168, 15),
  (30, 169, 15),

  (31, 167, 15),
  (31, 168, 15),
  (31, 169, 15),

  (32, 167, 15),
  (32, 168, 15),
  (32, 169, 15),

  (33, 167, 15),
  (33, 168, 15),
  (33, 169, 15),

  (34, 167, 15),
  (34, 168, 15),
  (34, 169, 15),

  (35, 167, 15),
  (35, 168, 15),
  (35, 169, 15),

  (36, 167, 15),
  (36, 168, 15),
  (36, 169, 15),

  (37, 167, 15),
  (37, 168, 15),
  (37, 169, 15),

  (38, 167, 15),
  (38, 168, 15),
  (38, 169, 15),

  (39, 167, 15),
  (39, 168, 15),
  (39, 169, 15),

  (40, 167, 15),
  (40, 168, 15),
  (40, 169, 15),

  (41, 167, 15),
  (41, 168, 15),
  (41, 169, 15),

  (42, 167, 15),
  (42, 168, 15),
  (42, 169, 15),

  (43, 167, 15),
  (43, 168, 15),
  (43, 169, 15),

  (44, 167, 15),
  (44, 168, 15),
  (44, 169, 15),

  (45, 167, 15),
  (45, 168, 15),
  (45, 169, 15),

  (46, 167, 15),
  (46, 168, 15),
  (46, 169, 15),

  (47, 167, 15),
  (47, 168, 15),
  (47, 169, 15),

  (101, 167, 15),
  (101, 168, 15),
  (101, 169, 15);

ALTER TABLE tb_combate ADD permite_dados_1 TINYINT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_combate ADD permite_dados_2 TINYINT UNSIGNED DEFAULT 1 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (90);