UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 1;
UPDATE tb_equipamentos
SET b_1 = 2, b_2 = 3
WHERE slot = 2;
UPDATE tb_equipamentos
SET b_1 = 3, b_2 = 4
WHERE slot = 3;
UPDATE tb_equipamentos
SET b_1 = 4, b_2 = 5
WHERE slot = 4;
UPDATE tb_equipamentos
SET b_1 = 5, b_2 = 6
WHERE slot = 5;
UPDATE tb_equipamentos
SET b_1 = 6, b_2 = 7
WHERE slot = 6;

UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 6
WHERE slot = 7 AND requisito = 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 7 AND requisito = 2;
UPDATE tb_equipamentos
SET b_1 = 2, b_2 = 2
WHERE slot = 8 AND requisito = 0;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 8 AND requisito = 2;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 6
WHERE slot = 8 AND requisito = 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 1
WHERE slot = 9 AND requisito = 1;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 3
WHERE slot = 9 AND requisito = 2;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 5
WHERE slot = 9 AND requisito = 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 1
WHERE slot = 10 AND requisito = 1;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 3
WHERE slot = 10 AND requisito = 2;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 5
WHERE slot = 10 AND requisito = 3;


INSERT INTO tb_migrations (cod_migration) VALUE (16);