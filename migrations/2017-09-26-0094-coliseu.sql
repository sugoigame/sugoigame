ALTER TABLE tb_usuarios
  ADD coliseu_points INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_usuarios
  ADD coliseu_premio INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_usuarios
  ADD coliseu_points_edicao INT UNSIGNED DEFAULT 0 NULL;

DROP PROCEDURE finaliza_coliseu;

CREATE PROCEDURE `finaliza_coliseu`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_coliseu_ranking;

    INSERT INTO tb_coliseu_ranking (id, cp)
      (SELECT
         cp.id,
         cp.coliseu_points_edicao
       FROM tb_usuarios cp);
  END;

DROP PROCEDURE inicia_coliseu;

CREATE PROCEDURE `inicia_coliseu`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_coliseu_fila;

    UPDATE tb_usuarios
    SET coliseu_points_edicao = 0;
  END;

ALTER TABLE tb_coliseu_fila
  ADD pausado TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_personagens
  ADD time_coliseu TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_coliseu_fila
  ADD momento TIMESTAMP DEFAULT current_timestamp NULL;

ALTER TABLE tb_coliseu_fila
  ADD lvl INT NULL;

ALTER TABLE tb_coliseu_fila
  ADD desafio_momento TIMESTAMP NULL;

ALTER TABLE tb_coliseu_fila
  ADD desafio INT UNSIGNED ZEROFILL NULL;
ALTER TABLE tb_coliseu_fila
  ADD CONSTRAINT tb_coliseu_fila_tb_usuarios_id_fk
FOREIGN KEY (desafio) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE tb_coliseu_fila
  ADD desafio_aceito TINYINT DEFAULT 0 NULL;

CREATE TABLE tb_relatorio_diario_acesso
(
  id            BIGINT AUTO_INCREMENT
    PRIMARY KEY,
  tripulacao_id INT(10) UNSIGNED ZEROFILL NULL,
  dia           TIMESTAMP                 NULL
);

CREATE PROCEDURE gera_relatorio_acesso_diario() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    INSERT INTO tb_relatorio_diario_acesso (tripulacao_id, dia)
      (SELECT
         id,
         current_date
       FROM tb_usuarios
       WHERE
         ultimo_logon > unix_timestamp() - (24 * 60 * 60));
  END;

CREATE EVENT `relatorio_acesso_diario`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL gera_relatorio_acesso_diario();
  END;

ALTER TABLE tb_usuarios ADD navegacao_automatica TINYINT DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (94);