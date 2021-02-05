ALTER TABLE tb_torneio_inscricao ADD rodada INT DEFAULT 0 NULL;
ALTER TABLE tb_torneio_inscricao ADD pontos INT DEFAULT 0 NULL;
ALTER TABLE tb_torneio_inscricao ADD na_fila TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_torneio_inscricao ADD fila_entrada TIMESTAMP DEFAULT NULL  NULL;
ALTER TABLE tb_torneio_inscricao ADD tempo_na_fila BIGINT DEFAULT NULL  NULL;

CREATE TABLE tb_torneio_rodadas
(
  id BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL NOT NULL,
  rodada INT,
  status INT,
  momento TIMESTAMP DEFAULT current_timestamp,
  adversario_id INT UNSIGNED ZEROFILL,
  CONSTRAINT tb_torneio_rodadas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT tb_torneio_rodadas_adversario_tb_usuarios_id_fk FOREIGN KEY (adversario_id) REFERENCES tb_usuarios (id) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (107);