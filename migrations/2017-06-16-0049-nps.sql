ALTER TABLE tb_mapa_contem
  ADD nps_id INT UNSIGNED NULL;
ALTER TABLE tb_mapa_contem
  DROP FOREIGN KEY tb_mapa_contem_ibfk_1;
ALTER TABLE tb_mapa_contem
  DROP PRIMARY KEY;
ALTER TABLE tb_mapa_contem
  MODIFY id INT(6) UNSIGNED ZEROFILL;
ALTER TABLE tb_mapa_contem
  ADD increment_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE tb_mapa_contem
  MODIFY COLUMN increment_id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT
  FIRST;

ALTER TABLE tb_mapa_contem
  ADD CONSTRAINT tb_mapa_contem_tb_usuarios_id_fk
FOREIGN KEY (id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE UNIQUE INDEX tb_mapa_contem_id_uindex
  ON tb_mapa_contem (id);

CREATE TABLE tb_evento_piratas
(
  tripulacao_id INT UNSIGNED ZEROFILL NOT NULL,
  recompensa_id INT UNSIGNED          NOT NULL,
  CONSTRAINT tb_evento_piratas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

INSERT INTO tb_migrations (cod_migration) VALUE (49);