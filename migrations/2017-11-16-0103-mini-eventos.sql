ALTER TABLE tb_mapa
  ADD zona_especial INT NULL;

CREATE TABLE tb_mini_eventos
(
  id  BIGINT UNSIGNED PRIMARY KEY NOT NULL,
  fim TIMESTAMP                   NOT NULL
);

CREATE TABLE tb_mini_eventos_concluidos
(
  id             BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  mini_evento_id BIGINT UNSIGNED             NOT NULL,
  tripulacao_id  INT UNSIGNED ZEROFILL       NOT NULL,
  momento        TIMESTAMP                            DEFAULT current_timestamp,
  CONSTRAINT tb_mini_eventos_concluidos_tb_mini_eventos_id_fk FOREIGN KEY (mini_evento_id) REFERENCES tb_mini_eventos (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_mini_eventos_concluidos_tb_usuario_itens_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuario_itens (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_mini_eventos
  ADD pack_recompensa INT UNSIGNED NULL;

ALTER TABLE tb_mini_eventos
  ADD inicio TIMESTAMP DEFAULT current_timestamp NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (103);