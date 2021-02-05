CREATE TABLE tb_boss
(
  id           INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  real_boss_id INT UNSIGNED             NOT NULL,
  hp           INT UNSIGNED             NOT NULL
);

ALTER TABLE tb_combate_npc
  ADD boss_id INT UNSIGNED NULL;
ALTER TABLE tb_combate_npc
  ADD CONSTRAINT tb_combate_npc_tb_boss_id_fk
FOREIGN KEY (boss_id) REFERENCES tb_boss (id);

CREATE TABLE tb_boss_damage
(
  id            INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  real_boss_id  INT UNSIGNED             NOT NULL,
  tripulacao_id INT UNSIGNED ZEROFILL    NOT NULL,
  damage        BIGINT DEFAULT 0         NOT NULL,
  CONSTRAINT tb_boss_damage_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

UPDATE tb_mapa SET zona = 11 WHERE x = 6 AND y = 4;
UPDATE tb_mapa SET zona = 11 WHERE x = 26 AND y = 7;
UPDATE tb_mapa SET zona = 11 WHERE x = 57 AND y = 26;
UPDATE tb_mapa SET zona = 11 WHERE x = 72 AND y = 6;
UPDATE tb_mapa SET zona = 11 WHERE x = 59 AND y = 31;

UPDATE tb_mapa SET zona = 11 WHERE x = 113 AND y = 19;
UPDATE tb_mapa SET zona = 11 WHERE x = 133 AND y = 30;
UPDATE tb_mapa SET zona = 11 WHERE x = 156 AND y = 31;
UPDATE tb_mapa SET zona = 11 WHERE x = 182 AND y = 6;
UPDATE tb_mapa SET zona = 11 WHERE x = 188 AND y = 35;

UPDATE tb_mapa SET zona = 11 WHERE x = 107 AND y = 92;
UPDATE tb_mapa SET zona = 11 WHERE x = 134 AND y = 77;
UPDATE tb_mapa SET zona = 11 WHERE x = 181 AND y = 68;
UPDATE tb_mapa SET zona = 11 WHERE x = 188 AND y = 92;
UPDATE tb_mapa SET zona = 11 WHERE x = 164 AND y = 93;

UPDATE tb_mapa SET zona = 11 WHERE x = 4 AND y = 79;
UPDATE tb_mapa SET zona = 11 WHERE x = 33 AND y = 85;
UPDATE tb_mapa SET zona = 11 WHERE x = 16 AND y = 95;
UPDATE tb_mapa SET zona = 11 WHERE x = 69 AND y = 93;
UPDATE tb_mapa SET zona = 11 WHERE x = 91 AND y = 68;

UPDATE tb_mapa SET zona = 11 WHERE x = 7 AND y = 60;
UPDATE tb_mapa SET zona = 11 WHERE x = 33 AND y = 41;
UPDATE tb_mapa SET zona = 11 WHERE x = 6 AND y = 50;
UPDATE tb_mapa SET zona = 11 WHERE x = 81 AND y = 55;

UPDATE tb_mapa SET zona = 11 WHERE x = 142 AND y = 45;

CREATE EVENT `atualiza_hp_icaro`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    UPDATE tb_boss SET hp = 1000000;
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (45);

