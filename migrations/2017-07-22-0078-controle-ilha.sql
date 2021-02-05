ALTER TABLE tb_mapa
  ADD ilha_dono INT UNSIGNED ZEROFILL NULL;
ALTER TABLE tb_mapa
  ADD CONSTRAINT tb_mapa_tb_usuarios_id_fk
FOREIGN KEY (ilha_dono) REFERENCES tb_usuarios (id)
  ON DELETE SET NULL
  ON UPDATE CASCADE;

CREATE TABLE tb_ilha_recurso
(
  ilha           INT UNSIGNED PRIMARY KEY NOT NULL,
  recurso_0      INT UNSIGNED DEFAULT 0,
  recurso_1      INT UNSIGNED DEFAULT 0,
  recurso_2      INT UNSIGNED DEFAULT 0,
  recurso_gerado INT UNSIGNED
);

INSERT INTO tb_ilha_recurso (ilha, recurso_gerado) VALUES
  (1, 0),
  (2, 1),
  (3, 2),
  (4, 0),
  (5, 1),
  (6, 2),
  (7, 0),
  (8, 1),
  (9, 2),
  (10, 0),
  (11, 1),
  (12, 2),
  (13, 0),
  (14, 1),
  (15, 2),
  (16, 0),
  (17, 1),
  (18, 2),
  (19, 0),
  (20, 1),
  (21, 2),
  (22, 0),
  (23, 1),
  (24, 2),
  (25, 0),
  (26, 1),
  (27, 2),
  (28, 0),
  (29, 1),
  (30, 2),
  (31, 0),
  (32, 1),
  (33, 2),
  (34, 0),
  (35, 1),
  (36, 2),
  (37, 0),
  (38, 1),
  (39, 2),
  (40, 0),
  (41, 1),
  (42, 2),
  (43, 0),
  (44, 1),
  (45, 2),
  (46, 0);

CREATE TABLE tb_ilha_bonus_ativo
(
  id        BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha      INT UNSIGNED                NOT NULL,
  x         INT UNSIGNED                NOT NULL,
  y         INT UNSIGNED                NOT NULL,
  buff_id   INT UNSIGNED                NOT NULL,
  expiracao INT
);

CREATE TABLE tb_ilha_recurso_venda
(
  id                BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha              INT UNSIGNED                NOT NULL,
  recurso_oferecido INT UNSIGNED                NOT NULL,
  recurso_desejado  INT UNSIGNED                NOT NULL,
  quant             INT UNSIGNED                NOT NULL
);

CREATE TABLE tb_ilha_mercador
(
  id           BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha_origem  INT UNSIGNED,
  ilha_destino INT UNSIGNED                NOT NULL,
  recurso      INT UNSIGNED                NOT NULL,
  quant        INT UNSIGNED                NOT NULL,
  finalizou    TINYINT UNSIGNED DEFAULT 0  NOT NULL
);

CREATE TABLE tb_rota_mercador
(
  id          BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  mercador_id BIGINT UNSIGNED             NOT NULL,
  indice      INT UNSIGNED                NOT NULL,
  x           INT UNSIGNED                NOT NULL,
  y           INT UNSIGNED                NOT NULL
);

ALTER TABLE tb_rota_mercador
  ADD CONSTRAINT tb_rota_mercador_tb_ilha_mercador_id_fk
FOREIGN KEY (mercador_id) REFERENCES tb_ilha_mercador (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_mapa_contem
  ADD mercador_id BIGINT UNSIGNED NULL;
ALTER TABLE tb_mapa_contem
  ADD CONSTRAINT tb_mapa_contem_tb_ilha_mercador_id_fk
FOREIGN KEY (mercador_id) REFERENCES tb_ilha_mercador (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE PROCEDURE `atualiza_rota_mercador`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DELETE c FROM tb_mapa_contem c
    WHERE mercador_id IS NOT NULL AND (SELECT count(r.id)
                                       FROM tb_rota_mercador r
                                       WHERE r.mercador_id = c.mercador_id) <= 0;

    UPDATE tb_ilha_mercador m
    SET m.finalizou = 1
    WHERE (SELECT count(r.id)
           FROM tb_rota_mercador r
           WHERE r.mercador_id = m.id) <= 0;

    UPDATE tb_mapa_contem c
      INNER JOIN (SELECT r.*
                  FROM tb_rota_mercador r,
                    (SELECT
                       MIN(r2.indice) AS minimo,
                       r2.mercador_id
                     FROM tb_rota_mercador r2
                     GROUP BY r2.mercador_id) AS rota
                  WHERE r.indice = rota.minimo AND rota.mercador_id = r.mercador_id) AS r
        ON r.mercador_id = c.mercador_id
    SET
      c.x = r.x,
      c.y = r.y
    WHERE c.mercador_id IS NOT NULL;

    DELETE r FROM
      tb_rota_mercador r,
      (SELECT
         MIN(r2.indice) AS minimo,
         r2.mercador_id
       FROM tb_rota_mercador r2
       GROUP BY r2.mercador_id) AS rota
    WHERE r.indice = rota.minimo AND rota.mercador_id = r.mercador_id;
  END;

CREATE EVENT `atualiza_rota_mercador`
  ON SCHEDULE EVERY 45 SECOND
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL atualiza_rota_mercador();
  END;

CREATE TABLE tb_ilha_mercador_personagem
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  mercador_id   BIGINT UNSIGNED             NOT NULL,
  personagem_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_ilha_mercador_personagem_tb_ilha_mercador_id_fk FOREIGN KEY (mercador_id) REFERENCES tb_ilha_mercador (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_ilha_mercador_personagem_tb_personagens_cod_fk FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
ALTER TABLE tb_combate_bot
  ADD mercador BIGINT UNSIGNED NULL;
ALTER TABLE tb_combate_bot
  ADD CONSTRAINT tb_combate_bot_tb_ilha_mercador_id_fk
FOREIGN KEY (mercador) REFERENCES tb_ilha_mercador (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

CREATE TABLE tb_ilha_incursao_protecao
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha          INT UNSIGNED                NOT NULL,
  sequencia     INT UNSIGNED                NOT NULL,
  personagem_id INT UNSIGNED ZEROFILL       NOT NULL,
  CONSTRAINT tb_ilha_incursao_protecao_tb_personagens_cod_fk FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_ilha_disputa
(
  id          BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  ilha        INT UNSIGNED                NOT NULL,
  vencedor_id INT UNSIGNED ZEROFILL,
  CONSTRAINT tb_ilha_disputa_tb_usuarios_id_fk FOREIGN KEY (vencedor_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_ilha_disputa_progresso
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  progresso     INT UNSIGNED                NOT NULL
);
ALTER TABLE tb_ilha_disputa_progresso
  ADD CONSTRAINT tb_ilha_disputa_progresso_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_ilha_disputa
  ADD vencedor_pronto TINYINT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_ilha_disputa
  ADD dono_pronto TINYINT UNSIGNED DEFAULT 0 NOT NULL;

ALTER TABLE tb_combate_bot
  ADD disputa_ilha TINYINT UNSIGNED NULL;

CREATE PROCEDURE `inicia_disputa_ilha`(ILHA_ID INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DELETE FROM tb_ilha_disputa_progresso
    WHERE ilha = ILHA_ID;
    INSERT INTO tb_ilha_disputa (ilha, fim) VALUE (ILHA_ID, unix_timestamp() + 2 * 60 * 60);
  END;

CREATE PROCEDURE `finaliza_incursao_disputa_ilha`(ILHA_ID INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DELETE FROM tb_ilha_disputa
    WHERE ilha = ILHA_ID AND vencedor_id IS NULL;
  END;

CREATE PROCEDURE `finaliza_disputa_ilha`(ILHA_ID INT) NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    DECLARE winner INT;

    SET winner = (SELECT vencedor_id
                  FROM tb_ilha_disputa
                  WHERE ilha = ILHA_ID AND vencedor_pronto = 1 AND dono_pronto = 0);

    IF winner IS NOT NULL
    THEN
      UPDATE tb_mapa m
        INNER JOIN tb_ilha_disputa d ON m.ilha = d.ilha
      SET m.ilha_dono = d.vencedor_id;
    END IF;

    DELETE FROM tb_ilha_disputa
    WHERE ilha = ILHA_ID AND (vencedor_pronto <> 1 OR dono_pronto <> 1);
  END;

/* SABAODY */
CREATE EVENT `inicia_disputa_sabaody`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 19:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(42);
  END;
CREATE EVENT `finaliza_incursao_disputa_sabaody`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 20:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(42);
  END;
CREATE EVENT `finaliza_disputa_sabaody`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 21:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(42);
  END;

/* FAROL */
CREATE EVENT `inicia_disputa_farol`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 21:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(29);
  END;
CREATE EVENT `finaliza_incursao_disputa_farol`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 22:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(29);
  END;
CREATE EVENT `finaliza_disputa_farol`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 23:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(29);
  END;

/* Little Garden */
CREATE EVENT `inicia_disputa_little_garden`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-16 22:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(31);
  END;
CREATE EVENT `finaliza_incursao_disputa_little_garden`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-16 23:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(31);
  END;
CREATE EVENT `finaliza_disputa_little_garden`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-17 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(31);
  END;

/* ALUBARNA */
CREATE EVENT `inicia_disputa_alubarna`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-29 23:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(35);
  END;
CREATE EVENT `finaliza_incursao_disputa_alubarna`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(35);
  END;
CREATE EVENT `finaliza_disputa_alubarna`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 01:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(35);
  END;

/* PUNK HAZARD */
CREATE EVENT `inicia_disputa_punk_hazard`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 01:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(44);
  END;
CREATE EVENT `finaliza_incursao_disputa_punk_hazard`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 02:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(44);
  END;
CREATE EVENT `finaliza_disputa_punk_hazard`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 03:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(44);
  END;

/* TRILLER BARK */
CREATE EVENT `inicia_disputa_triller_bark`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 18:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(40);
  END;
CREATE EVENT `finaliza_incursao_disputa_triller_bark`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 19:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(40);
  END;
CREATE EVENT `finaliza_disputa_triller_bark`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 20:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(40);
  END;

/* WATER 7 */
CREATE EVENT `inicia_disputa_water7`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 20:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(41);
  END;
CREATE EVENT `finaliza_incursao_disputa_water7`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 21:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(41);
  END;
CREATE EVENT `finaliza_disputa_water7`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 22:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(41);
  END;

/* YUKIRYU */
CREATE EVENT `inicia_disputa_yukiryu`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 22:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(45);
  END;
CREATE EVENT `finaliza_incursao_disputa_yukiryu`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-30 23:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(45);
  END;
CREATE EVENT `finaliza_disputa_yukiryu`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-31 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(45);
  END;

/* mocktown */
CREATE EVENT `inicia_disputa_mocktown`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-31 13:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(37);
  END;
CREATE EVENT `finaliza_incursao_disputa_mocktown`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-31 14:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(37);
  END;
CREATE EVENT `finaliza_disputa_mocktown`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-31 15:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(37);
  END;

/* yuba */
CREATE EVENT `inicia_disputa_yuba`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-01 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(34);
  END;
CREATE EVENT `finaliza_incursao_disputa_yuba`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-01 01:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(34);
  END;
CREATE EVENT `finaliza_disputa_yuba`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-01 02:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(34);
  END;

/* whiskey peaks */
CREATE EVENT `inicia_disputa_whiskey_peaks`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 01:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(30);
  END;
CREATE EVENT `finaliza_incursao_whiskey_peaks`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 02:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(30);
  END;
CREATE EVENT `finaliza_disputa_whiskey_peaks`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 03:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(30);
  END;

/* cricket_house */
CREATE EVENT `inicia_disputa_cricket_house`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 11:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(38);
  END;
CREATE EVENT `finaliza_incursao_cricket_house`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 12:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(38);
  END;
CREATE EVENT `finaliza_disputa_cricket_house`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-02 13:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(38);
  END;

/* drum */
CREATE EVENT `inicia_disputa_drum`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-03 19:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(32);
  END;
CREATE EVENT `finaliza_incursao_drum`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-03 20:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(32);
  END;
CREATE EVENT `finaliza_disputa_drum`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-03 21:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(32);
  END;

/* nanohana */
CREATE EVENT `inicia_disputa_nanohana`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 03:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(36);
  END;
CREATE EVENT `finaliza_incursao_nanohana`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 04:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(36);
  END;
CREATE EVENT `finaliza_disputa_nanohana`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 05:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(36);
  END;

/* long ring */
CREATE EVENT `inicia_disputa_long_ring`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 17:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(39);
  END;
CREATE EVENT `finaliza_incursao_long_ring`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 18:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(39);
  END;
CREATE EVENT `finaliza_disputa_long_ring`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-04 19:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(39);
  END;

/* raibanse */
CREATE EVENT `inicia_disputa_raibanse`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-05 02:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(33);
  END;
CREATE EVENT `finaliza_incursao_raibanse`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-05 03:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(33);
  END;
CREATE EVENT `finaliza_disputa_raibanse`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-08-05 04:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(33);
  END;

/* Laftel */
CREATE EVENT `inicia_disputa_laftel`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-10-16 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL inicia_disputa_ilha(47);
  END;
CREATE EVENT `finaliza_incursao_laftel`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-10-16 01:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_incursao_disputa_ilha(47);
  END;
CREATE EVENT `finaliza_disputa_laftel`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-10-16 02:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL finaliza_disputa_ilha(47);
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (78);