ALTER TABLE tb_combate_npc
  ADD skin_npc INT NULL;
ALTER TABLE tb_combate_npc
  ADD chefe_especial INT NULL;

CREATE TABLE tb_evento_chefes
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha          INT                         NOT NULL,
  personagem_id INT UNSIGNED ZEROFILL       NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_evento_chefes_tb_personagens_cod_fk FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_evento_chefes_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE EVENT `atualiza_rankings`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    TRUNCATE tb_evento_chefes;
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (77);