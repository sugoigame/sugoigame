UPDATE tb_mapa
SET zona = 12
WHERE x = 160 AND y = 56;

UPDATE tb_mapa
SET zona = 13
WHERE x = 177 AND y = 50;

UPDATE tb_mapa
SET zona = 14
WHERE x = 117 AND y = 42;


INSERT INTO tb_migrations (cod_migration) VALUE (46);