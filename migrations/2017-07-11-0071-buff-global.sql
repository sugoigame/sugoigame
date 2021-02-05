CREATE TABLE tb_buff_global
(
  id        BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  buff_id   INT UNSIGNED                NOT NULL,
  expiracao INT UNSIGNED                NOT NULL
);

INSERT INTO tb_migrations (cod_migration) VALUE (71);