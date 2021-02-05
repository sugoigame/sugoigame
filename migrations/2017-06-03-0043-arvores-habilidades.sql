
-- passivas espadachim
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 6 WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 5 WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 3 WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 48;

-- passivas lutador
UPDATE tb_skil_passiva SET bonus_atr = 2 WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 8 WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 2 WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 2 WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 2 WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 3 WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 2 WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 48;

-- passivas atirador
UPDATE tb_skil_passiva SET bonus_atr = 3 WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 4 WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 3 WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 5 WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 48;

UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET bonus_atr = 6 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET bonus_atr = 1 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 48;

-- habilidades lutador
DELETE FROM tb_skil_buff WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 38;
INSERT INTO tb_skil_atk (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt,
  requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  dano, alcance, area, espera,
  categoria
)
VALUES (38,
  1, 80,
  1, 80,
  43, 500000, 2, 0,
  60, 1, 1, 5,
  1
);

DELETE FROM tb_skil_buff WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 28;
INSERT INTO tb_skil_atk (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt,
  requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  dano, alcance, area, espera,
  categoria
)
VALUES (28,
  1, 80,
  1, 80,
  28, 100000, 2, 0,
  60, 1, 1, 5,
        2
);

DELETE FROM tb_skil_buff WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 50;
INSERT INTO tb_skil_atk (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt,
  requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  dano, alcance, area, espera,
  categoria
)
VALUES (50,
  1, 80,
  1, 80,
  50, 1000000, 2, 0,
  70, 1, 2, 5,
        2
);

DELETE FROM tb_skil_buff WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 38;
INSERT INTO tb_skil_atk (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt,
  requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  dano, alcance, area, espera,
  categoria
)
VALUES (38,
  1, 80,
  1, 80,
  43, 500000, 2, 0,
  60, 1, 1, 5,
        3
);

-- habilidades atirador
DELETE FROM tb_skil_atk WHERE requisito_classe = 3 AND categoria = 1;
INSERT INTO tb_skil_buff (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt,
  requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  bonus_atr, bonus_atr_qnt, duracao, alcance, area, espera,
  categoria
)
VALUES (
  15,
  1,100,
  1,100,
  3, 5000, 3, 0,
  5, 50, 3, 1, 1, 3,
  1
),(
  18,
  1,100,
  1,100,
  8, 10000, 3, 0,
  4, 100, 3, 1, 1, 3,
  1
),(
  21,
  1,100,
  1,100,
  15, 20000, 3, 0,
  7, 100, 3, 1, 1, 3,
  1
),(
  28,
  1,100,
  1,100,
  28, 100000, 3, 0,
  6, 100, 3, 1, 1, 3,
  1
),(
  38,
  1,100,
  1,100,
  43, 500000, 3, 0,
  2, 100, 3, 1, 1, 3,
  1
),(
  50,
  1,100,
  1,100,
  50, 1000000, 3, 0,
  1, 100, 3, 1, 1, 3,
  1
);

UPDATE tb_skil_atk SET alcance= 5, area = 1, dano = 45 WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET alcance= 10, area = 2, dano = 50 WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 50;

UPDATE tb_skil_atk SET alcance= 1, area = 3, dano = 20 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET alcance= 2, area = 3, dano = 40 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET alcance= 2, area = 4, dano = 20 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET alcance= 2, area = 5, dano = 30 WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 50;

-- atributos e requisitos
-- espadachim 1
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 = 6, requisito_atr_2_qnt = 10
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 = 6, requisito_atr_2_qnt = 15
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 = 6, requisito_atr_2_qnt = 20
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 = 6, requisito_atr_2_qnt = 40
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 6, requisito_atr_2_qnt = 50
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 6, requisito_atr_2_qnt = 60
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 = 6, requisito_atr_2_qnt = 30
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 = 6, requisito_atr_2_qnt = 45
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 6, requisito_atr_2_qnt = 55
WHERE requisito_classe = 1 AND categoria = 1 AND requisito_lvl = 48;

-- espadachim 2
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 = 5, requisito_atr_2_qnt = 10
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 = 5, requisito_atr_2_qnt = 15
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 = 5, requisito_atr_2_qnt = 20
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 = 5, requisito_atr_2_qnt = 40
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 5, requisito_atr_2_qnt = 50
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 5, requisito_atr_2_qnt = 60
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 = 5, requisito_atr_2_qnt = 30
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 = 5, requisito_atr_2_qnt = 45
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 5, requisito_atr_2_qnt = 55
WHERE requisito_classe = 1 AND categoria = 2 AND requisito_lvl = 48;

-- espadachim 3
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 = 3, requisito_atr_2_qnt = 10
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 = 3, requisito_atr_2_qnt = 15
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 = 3, requisito_atr_2_qnt = 20
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 = 3, requisito_atr_2_qnt = 40
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 3, requisito_atr_2_qnt = 50
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 3, requisito_atr_2_qnt = 60
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 = 3, requisito_atr_2_qnt = 30
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 = 3, requisito_atr_2_qnt = 45
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 3, requisito_atr_2_qnt = 55
WHERE requisito_classe = 1 AND categoria = 3 AND requisito_lvl = 48;


-- lutador 1
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 20, requisito_atr_2 =  8, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 25, requisito_atr_2 =  8, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 40, requisito_atr_2 =  8, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 60, requisito_atr_2 =  8, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 8, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 120, requisito_atr_2 = 8, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 50;

UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 20, requisito_atr_2 =  8, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 3;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 25, requisito_atr_2 =  8, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 8;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 40, requisito_atr_2 =  8, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 15;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 60, requisito_atr_2 =  8, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 28;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 8, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 43;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 8, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 38;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 120, requisito_atr_2 = 8, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 50, requisito_atr_2 =  8, requisito_atr_2_qnt = 30
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 75, requisito_atr_2 =  8, requisito_atr_2_qnt = 45
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 105, requisito_atr_2 = 8, requisito_atr_2_qnt = 55
WHERE requisito_classe = 2 AND categoria = 1 AND requisito_lvl = 48;

-- lutador 2
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 =  2, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 =  2, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 =  2, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 =  2, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 2, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 2, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 50;

UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 =  2, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 3;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 =  2, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 8;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 =  2, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 15;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 =  2, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 28;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 2, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 43;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 2, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 38;
UPDATE tb_skil_buff SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 2, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 =  2, requisito_atr_2_qnt = 30
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 =  2, requisito_atr_2_qnt = 45
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 2, requisito_atr_2_qnt = 55
WHERE requisito_classe = 2 AND categoria = 2 AND requisito_lvl = 48;

-- lutador 3
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 20, requisito_atr_2 =  3, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 25, requisito_atr_2 =  3, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 40, requisito_atr_2 =  3, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 60, requisito_atr_2 =  3, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 3, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 2, requisito_atr_1_qnt = 120, requisito_atr_2 = 3, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 50;

UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 20, requisito_atr_2 =  3, requisito_atr_2_qnt = 10
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 3;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 25, requisito_atr_2 =  3, requisito_atr_2_qnt = 15
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 8;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 40, requisito_atr_2 =  3, requisito_atr_2_qnt = 20
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 15;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 60, requisito_atr_2 =  3, requisito_atr_2_qnt = 40
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 28;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 3, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 43;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 90, requisito_atr_2 = 3, requisito_atr_2_qnt = 50
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 38;
UPDATE tb_skil_buff SET requisito_atr_1 = 2, requisito_atr_1_qnt = 120, requisito_atr_2 = 3, requisito_atr_2_qnt = 60
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 50, requisito_atr_2 =  3, requisito_atr_2_qnt = 30
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 75, requisito_atr_2 =  3, requisito_atr_2_qnt = 45
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 2, requisito_atr_1_qnt = 105, requisito_atr_2 = 3, requisito_atr_2_qnt = 55
WHERE requisito_classe = 2 AND categoria = 3 AND requisito_lvl = 48;



-- atirador 1
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 20, requisito_atr_2 = 4, requisito_atr_2_qnt = 10
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 3;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 25, requisito_atr_2 = 4, requisito_atr_2_qnt = 15
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 8;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 40, requisito_atr_2 = 4, requisito_atr_2_qnt = 20
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 15;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 60, requisito_atr_2 = 4, requisito_atr_2_qnt = 40
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 28;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 90, requisito_atr_2 = 4, requisito_atr_2_qnt = 50
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 43;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 90, requisito_atr_2 = 4, requisito_atr_2_qnt = 50
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 38;
UPDATE tb_skil_buff SET requisito_atr_1 = 3, requisito_atr_1_qnt = 120, requisito_atr_2 = 4, requisito_atr_2_qnt = 60
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 3, requisito_atr_1_qnt = 50, requisito_atr_2 = 4, requisito_atr_2_qnt = 30
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 3, requisito_atr_1_qnt = 75, requisito_atr_2 = 4, requisito_atr_2_qnt = 45
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 3, requisito_atr_1_qnt = 105, requisito_atr_2 = 4, requisito_atr_2_qnt = 55
WHERE requisito_classe = 3 AND categoria = 1 AND requisito_lvl = 48;

-- atirador 2
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 =  5, requisito_atr_2_qnt = 10
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 =  5, requisito_atr_2_qnt = 15
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 =  5, requisito_atr_2_qnt = 20
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 =  5, requisito_atr_2_qnt = 40
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 5, requisito_atr_2_qnt = 50
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 5, requisito_atr_2_qnt = 60
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 =  5, requisito_atr_2_qnt = 30
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 =  5, requisito_atr_2_qnt = 45
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 5, requisito_atr_2_qnt = 55
WHERE requisito_classe = 3 AND categoria = 2 AND requisito_lvl = 48;

-- atirador 3
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 20, requisito_atr_2 =  6, requisito_atr_2_qnt = 10
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 3;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 25, requisito_atr_2 =  6, requisito_atr_2_qnt = 15
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 8;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 40, requisito_atr_2 =  6, requisito_atr_2_qnt = 20
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 15;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 60, requisito_atr_2 =  6, requisito_atr_2_qnt = 40
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 28;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 90, requisito_atr_2 = 6, requisito_atr_2_qnt = 50
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 43;
UPDATE tb_skil_atk SET requisito_atr_1 = 1, requisito_atr_1_qnt = 120, requisito_atr_2 = 6, requisito_atr_2_qnt = 60
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 50;

UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 50, requisito_atr_2 =  6, requisito_atr_2_qnt = 30
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 23;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 75, requisito_atr_2 =  6, requisito_atr_2_qnt = 45
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 33;
UPDATE tb_skil_passiva SET requisito_atr_1 = 1, requisito_atr_1_qnt = 105, requisito_atr_2 = 6, requisito_atr_2_qnt = 55
WHERE requisito_classe = 3 AND categoria = 3 AND requisito_lvl = 48;

-- buffs do musico

UPDATE tb_skil_buff SET consumo = 40, requisito_berries = 10000, bonus_atr = 5, bonus_atr_qnt = 50, duracao= 4, area = 1, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 1;
UPDATE tb_skil_buff SET consumo = 40, requisito_berries = 10000, bonus_atr = 2, bonus_atr_qnt = 50, duracao= 4, area = 1, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 2;
UPDATE tb_skil_buff SET consumo = 40, requisito_berries = 10000, bonus_atr = 1, bonus_atr_qnt = 50, duracao= 4, area = 1, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 3;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 7, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 4;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 4, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 5;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 5, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 6;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 6, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 7;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 3, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 8;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 2, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 9;
UPDATE tb_skil_buff SET consumo = 70, requisito_berries = 50000, bonus_atr = 1, bonus_atr_qnt = 100, duracao= 4, area = 2, alcance = 1, espera = 4 WHERE requisito_prof = 8 AND requisito_lvl = 10;

INSERT INTO tb_skil_buff (
  consumo,
  requisito_atr_1, requisito_atr_1_qnt, requisito_atr_2, requisito_atr_2_qnt,
  requisito_lvl, requisito_berries, requisito_classe, requisito_prof,
  bonus_atr, bonus_atr_qnt, duracao, alcance, area, espera,
  categoria
)
VALUES (
  40,
  0,0,0,0,
  1, 10000, 0, 8,
  5, -50, 4, 1, 1, 4,
  0
),(
  40,
  0,0,0,0,
  2, 10000, 0, 8,
  2, -50, 4, 1, 1, 4,
  0
),(
  40,
  0,0,0,0,
  3, 10000, 0, 8,
  1, -50, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  4, 50000, 0, 8,
  7, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  5, 50000, 0, 8,
  4, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  6, 50000, 0, 8,
  5, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  7, 50000, 0, 8,
  6, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  8, 50000, 0, 8,
  3, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  9, 50000, 0, 8,
  2, -100, 4, 1, 1, 4,
  0
),(
  70,
  0,0,0,0,
  10, 50000, 0, 8,
  1, -100, 4, 1, 1, 4,
  0
);

INSERT INTO tb_migrations (cod_migration) VALUE (43);