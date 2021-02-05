CREATE TABLE tb_combate_bot
(
  id                 BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id      INT UNSIGNED ZEROFILL       NOT NULL,
  tripulacao_inimiga VARCHAR(255)                NOT NULL,
  faccao_inimiga     INT UNSIGNED                NOT NULL,
  bandeira_inimiga   VARCHAR(255),
  vez                INT DEFAULT 1               NOT NULL,
  move               INT DEFAULT 5               NOT NULL,
  battle_back        INT UNSIGNED,
  incursao           TINYINT UNSIGNED
);

ALTER TABLE tb_combate_bot
  ADD CONSTRAINT tb_combate_bot_tb_usuarios_id_fk
FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_combate_bot
  ADD relatorio LONGTEXT NULL;

CREATE TABLE tb_combate_personagens_bot
(
  id                 BIGINT UNSIGNED PRIMARY KEY          NOT NULL AUTO_INCREMENT,
  combate_bot_id     BIGINT UNSIGNED                      NOT NULL,
  nome               VARCHAR(255)                         NOT NULL,
  img                INT(4)                               NOT NULL,
  lvl                INT(4)                               NOT NULL,
  skin_r             INT(4)                               NOT NULL,
  skin_c             INT(4)                               NOT NULL,
  hp                 INT(4)                               NOT NULL,
  hp_max             INT(4)                               NOT NULL,
  mp                 INT(4)                               NOT NULL,
  mp_max             INT(4)                               NOT NULL,
  atk                INT(4)                               NOT NULL,
  def                INT(4)                               NOT NULL,
  agl                INT(4)                               NOT NULL,
  res                INT(4)                               NOT NULL,
  pre                INT(4)                               NOT NULL,
  dex                INT(4)                               NOT NULL,
  con                INT(4)                               NOT NULL,
  vit                INT(4)                               NOT NULL,
  quadro_x           INT(3)                               NOT NULL,
  quadro_y           INT(3)                               NOT NULL,
  haki_esq           INT(3)                               NOT NULL,
  haki_cri           INT(3)                               NOT NULL,
  haki_blo           INT(3)                               NOT NULL,
  categoria_akuma    INT,
  titulo             VARCHAR(255),
  classe             INT,
  classe_score       INT DEFAULT 1000                     NULL,
  pack_habilidade_id INT UNSIGNED                         NULL,
  CONSTRAINT tb_combate_personagens_bot_ibfk_1 FOREIGN KEY (combate_bot_id) REFERENCES tb_combate_bot (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_combate_buff_bot
(
  id       BIGINT UNSIGNED          NOT NULL,
  cod      BIGINT UNSIGNED          NOT NULL,
  cod_buff INT(4) UNSIGNED ZEROFILL NOT NULL,
  atr      INT(1)                   NOT NULL,
  efeito   INT(3)                   NOT NULL,
  espera   INT(2)                   NOT NULL,
  CONSTRAINT tb_combate_buff_bot_ibfk_1 FOREIGN KEY (cod) REFERENCES tb_combate_personagens_bot (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_incursao_nivel
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  nivel         INT UNSIGNED                         DEFAULT 0,
  CONSTRAINT tb_incursao_nivel_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE tb_incursao_progresso
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  progresso     INT UNSIGNED                         DEFAULT 0,
  CONSTRAINT tb_incursao_progresso_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE tb_incursao_personagem
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  personagem_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  pontos        INT UNSIGNED DEFAULT 0      NOT NULL,
  nivel         INT UNSIGNED DEFAULT 0      NOT NULL,
  CONSTRAINT tb_incursao_personagem_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_incursao_personagem_tb_personagens_id_fk FOREIGN KEY (personagem_id) REFERENCES tb_personagens (cod)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE tb_incursao_pontos
(
  id                BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id     INT UNSIGNED ZEROFILL       NOT NULL,
  ilha              INT UNSIGNED,
  pontos_espadachim INT UNSIGNED DEFAULT 0      NOT NULL,
  pontos_lutador    INT UNSIGNED DEFAULT 0      NOT NULL,
  pontos_atirador   INT UNSIGNED DEFAULT 0      NOT NULL,
  CONSTRAINT tb_incursao_pontos_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE EVENT `reset_incursoes`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-07-11 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    TRUNCATE tb_incursao_progresso;
  END;


INSERT INTO tb_migrations (cod_migration) VALUE (65);