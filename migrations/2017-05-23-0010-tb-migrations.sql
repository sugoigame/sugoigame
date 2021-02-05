CREATE TABLE sugoigame3.tb_migrations
(
  id            INT UNSIGNED ZEROFILL PRIMARY KEY   NOT NULL AUTO_INCREMENT,
  cod_migration INT UNSIGNED                        NOT NULL,
  date          TIMESTAMP DEFAULT current_timestamp NOT NULL
);
CREATE UNIQUE INDEX tb_migrations_id_uindex
  ON sugoigame3.tb_migrations (id);

INSERT INTO tb_migrations (cod_migration) VALUES
  (1), (2), (3), (4), (5), (6), (7), (8), (9), (10);