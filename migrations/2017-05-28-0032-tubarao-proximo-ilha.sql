UPDATE tb_mapa mapa INNER JOIN
  (SELECT
     x,
     y,
     ilha
   FROM tb_mapa) AS visinho ON visinho.x >= mapa.x - 1 AND visinho.x <= mapa.x + 1
                               AND visinho.y >= mapa.y - 1 AND visinho.y <= mapa.y + 1 AND visinho.ilha <> 0
SET mapa.zona = 3;

INSERT INTO tb_migrations (cod_migration) VALUE (32);