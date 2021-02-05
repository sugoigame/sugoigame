CREATE TABLE tb_obstaculos
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT(10) UNSIGNED ZEROFILL       NOT NULL,
  x             INT(10) UNSIGNED                NOT NULL,
  y             INT(10) UNSIGNED                NOT NULL,
  tipo          INT(11),
  hp            INT(10) UNSIGNED                NOT NULL,
  CONSTRAINT tb_obstaculos_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_obstaculos_tb_usuarios_id_fk
  ON tb_obstaculos (tripulacao_id);

INSERT INTO tb_migrations (cod_migration) VALUE (82);