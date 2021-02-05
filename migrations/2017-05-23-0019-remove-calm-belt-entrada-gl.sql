UPDATE tb_mapa SET zona = 3 WHERE x = 3 AND y = 35;
UPDATE tb_mapa SET zona = 3 WHERE x = 3 AND y = 65;
UPDATE tb_mapa SET zona = 3 WHERE x = 198 AND y = 66;
UPDATE tb_mapa SET zona = 3 WHERE x = 198 AND y = 65;

INSERT INTO tb_migrations (cod_migration) VALUE (19);