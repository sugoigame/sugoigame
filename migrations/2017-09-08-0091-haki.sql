ALTER TABLE tb_usuarios
  ADD haki_xp INT UNSIGNED DEFAULT 0 NULL;

TRUNCATE tb_haki_treino;
ALTER TABLE tb_haki_treino
  DROP FOREIGN KEY tb_haki_treino_ibfk_1;
ALTER TABLE tb_haki_treino
  DROP PRIMARY KEY;
ALTER TABLE tb_haki_treino
  DROP cod;
ALTER TABLE tb_haki_treino
  DROP fim;
ALTER TABLE tb_haki_treino
  ADD id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE tb_haki_treino
  DROP pts;
ALTER TABLE tb_haki_treino
  ADD data TIMESTAMP DEFAULT current_timestamp NULL;
ALTER TABLE tb_haki_treino
  ADD tripulacao_id INT UNSIGNED ZEROFILL NOT NULL;
ALTER TABLE tb_haki_treino
  ADD CONSTRAINT tb_haki_treino_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_combate_bot
  ADD haki INT UNSIGNED NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (91);