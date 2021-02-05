UPDATE tb_missoes
SET recompensa_berries = requisito_lvl * 1000;

UPDATE tb_item_reagents
SET preco = preco / 10;

UPDATE tb_item_remedio
SET hp_recuperado = hp_recuperado * 2, mp_recuperado = mp_recuperado * 2;

UPDATE tb_item_comida
SET hp_recuperado = hp_recuperado * 2, mp_recuperado = mp_recuperado * 2;

INSERT INTO tb_migrations (cod_migration) VALUE (24);