CREATE TABLE tb_variavel_global
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  variavel      VARCHAR(255),
  valor_int     BIGINT,
  valor_varchar VARCHAR(255)
);

ALTER TABLE tb_dobroes_leilao_log
  MODIFY vendedor_id INT(10) UNSIGNED ZEROFILL;
ALTER TABLE tb_dobroes_leilao_log
  MODIFY comprador_id INT(10) UNSIGNED ZEROFILL;

INSERT INTO tb_migrations (cod_migration) VALUE (69);