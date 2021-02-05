CREATE TABLE tb_combate_buff_npc
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  efeito        INT                         NOT NULL,
  atr           INT UNSIGNED                NOT NULL,
  espera        INT                         NOT NULL
);
INSERT INTO tb_migrations (cod_migration) VALUE (53);