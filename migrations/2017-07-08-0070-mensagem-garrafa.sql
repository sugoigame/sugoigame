CREATE TABLE tb_item_missao
(
  id          BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  img         INT(4),
  nome        VARCHAR(100),
  descricao   TEXT,
  img_format  VARCHAR(5)                           DEFAULT 'png',
  tipo_missao INT UNSIGNED,
  x           INT,
  y           INT,
  method      VARCHAR(255)
);
INSERT INTO tb_migrations (cod_migration) VALUE (70);