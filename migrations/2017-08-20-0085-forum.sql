CREATE TABLE tb_forum_categoria
(
  id                     BIGINT(20) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  nome                   VARCHAR(255),
  permite_topico_jogador TINYINT(4) DEFAULT '1'          NOT NULL,
  descricao              TEXT,
  agrupamento            INT(11)                         NOT NULL,
  icon                   VARCHAR(255)
);
CREATE INDEX tb_forum_categoria_tb_forum_categoria_id_fk
  ON tb_forum_categoria (agrupamento);

CREATE TABLE tb_forum_topico
(
  id           BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  categoria_id BIGINT(20) UNSIGNED                 NOT NULL,
  nome         VARCHAR(255),
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  criador_id   INT(10) UNSIGNED ZEROFILL,
  bloqueado    TINYINT(4) DEFAULT '0'              NOT NULL,
  resolvido    TINYINT(4) DEFAULT '0'              NOT NULL,
  CONSTRAINT tb_forum_topico_tb_forum_categoria_id_fk FOREIGN KEY (categoria_id) REFERENCES tb_forum_categoria (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_forum_topico_tb_usuarios_id_fk FOREIGN KEY (criador_id) REFERENCES tb_usuarios (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);
CREATE INDEX tb_forum_topico_tb_forum_categoria_id_fk
  ON tb_forum_topico (categoria_id);
CREATE INDEX tb_forum_topico_tb_usuarios_id_fk
  ON tb_forum_topico (criador_id);


CREATE TABLE tb_forum_post
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  conteudo      MEDIUMTEXT                          NOT NULL,
  tripulacao_id INT(10) UNSIGNED ZEROFILL,
  data_criacao  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  oculto        TINYINT(4) DEFAULT '0'              NOT NULL,
  topico_id     BIGINT(20) UNSIGNED                 NOT NULL,
  CONSTRAINT tb_forum_comentario_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT tb_forum_comentario_tb_forum_topico_id_fk FOREIGN KEY (topico_id) REFERENCES tb_forum_topico (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_forum_comentario_tb_forum_topico_id_fk
  ON tb_forum_post (topico_id);
CREATE INDEX tb_forum_comentario_tb_usuarios_id_fk
  ON tb_forum_post (tripulacao_id);

CREATE TABLE tb_forum_likes
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  tripulacao_id INT(10) UNSIGNED ZEROFILL           NOT NULL,
  tipo          INT(11),
  data_like     TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  post_id       BIGINT(20) UNSIGNED                 NOT NULL,
  CONSTRAINT tb_forum_likes_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_forum_likes_tb_forum_post_id_fk FOREIGN KEY (post_id) REFERENCES tb_forum_post (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_forum_likes_tb_usuarios_id_fk
  ON tb_forum_likes (tripulacao_id);
CREATE INDEX tb_forum_likes_tb_forum_post_id_fk
  ON tb_forum_likes (post_id);

CREATE TABLE tb_forum_topico_lido
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  tripulacao_id INT(10) UNSIGNED ZEROFILL           NOT NULL,
  topico_id     BIGINT(20) UNSIGNED                 NOT NULL,
  data_leitura  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  CONSTRAINT tb_forum_topico_lido_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_forum_topico_lido_tb_forum_topico_id_fk FOREIGN KEY (topico_id) REFERENCES tb_forum_topico (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_forum_topico_lido_tb_forum_topico_id_fk
  ON tb_forum_topico_lido (topico_id);
CREATE INDEX tb_forum_topico_lido_tb_usuarios_id_fk
  ON tb_forum_topico_lido (tripulacao_id);

CREATE TABLE tb_noticia_lida
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  noticia_id    INT(10) UNSIGNED ZEROFILL       NOT NULL,
  tripulacao_id INT(10) UNSIGNED ZEROFILL       NOT NULL,
  data_leitura  TIMESTAMP                                DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT tb_noticia_lida_tb_noticias_cod_noticia_fk FOREIGN KEY (noticia_id) REFERENCES tb_noticias (cod_noticia)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_noticia_lida_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_noticia_lida_tb_noticias_cod_noticia_fk
  ON tb_noticia_lida (noticia_id);
CREATE INDEX tb_noticia_lida_tb_usuarios_id_fk
  ON tb_noticia_lida (tripulacao_id);

CREATE TABLE tb_noticia_comment
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  noticia_id    INT(10) UNSIGNED ZEROFILL           NOT NULL,
  tripulacao_id INT(10) UNSIGNED ZEROFILL,
  data_criacao  TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  oculto        INT(11) DEFAULT '0'                 NOT NULL,
  conteudo      TEXT,
  CONSTRAINT tb_noticia_comment_tb_noticias_cod_noticia_fk FOREIGN KEY (noticia_id) REFERENCES tb_noticias (cod_noticia)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_noticia_comment_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE SET NULL
    ON UPDATE CASCADE
);
CREATE INDEX tb_noticia_comment_tb_noticias_cod_noticia_fk
  ON tb_noticia_comment (noticia_id);
CREATE INDEX tb_noticia_comment_tb_usuarios_id_fk
  ON tb_noticia_comment (tripulacao_id);

CREATE TABLE tb_noticia_likes
(
  id            BIGINT(20) UNSIGNED PRIMARY KEY     NOT NULL AUTO_INCREMENT,
  tripulacao_id INT(10) UNSIGNED ZEROFILL           NOT NULL,
  tipo          INT(11),
  data_like     TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
  comment_id    BIGINT(20) UNSIGNED                 NOT NULL,
  CONSTRAINT tb_noticia_likes_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_noticia_likes_tb_noticia_comment_id_fk FOREIGN KEY (comment_id) REFERENCES tb_noticia_comment (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE INDEX tb_noticia_likes_tb_noticia_id_fk
  ON tb_noticia_likes (comment_id);
CREATE INDEX tb_noticia_likes_tb_usuarios_id_fk
  ON tb_noticia_likes (tripulacao_id);

INSERT INTO tb_migrations (cod_migration) VALUE (85);