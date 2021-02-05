UPDATE tb_skil_atk
SET dano = 40, consumo = 5, espera = 2
WHERE cod_skil = 155;

UPDATE tb_skil_atk
SET dano = 50, consumo = 5, espera = 2
WHERE cod_skil = 156;

INSERT INTO tb_migrations (cod_migration) VALUE (34);