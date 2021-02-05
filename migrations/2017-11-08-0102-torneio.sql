ALTER TABLE tb_torneio_inscricao
  MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE tb_torneio_inscricao
  ADD PRIMARY KEY (id);
ALTER TABLE tb_torneio_inscricao
  DROP facebook;
ALTER TABLE tb_torneio_inscricao
  CHANGE status confirmacao INT(1) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE tb_torneio_inscricao
  MODIFY tripulacao VARCHAR(100) NOT NULL;
ALTER TABLE tb_torneio_inscricao
  CHANGE nome nome_capitao VARCHAR(100) NOT NULL;
ALTER TABLE tb_torneio_inscricao
  ADD tripulacao_id INT UNSIGNED ZEROFILL NOT NULL;
ALTER TABLE tb_torneio_inscricao
  MODIFY COLUMN tripulacao_id INT UNSIGNED ZEROFILL NOT NULL
  AFTER id;

INSERT INTO tb_migrations (cod_migration) VALUE (102);