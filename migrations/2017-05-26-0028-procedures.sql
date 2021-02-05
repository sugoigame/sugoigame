ALTER TABLE tb_ranking_reputacao
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE tb_ranking_reputacao_mensal
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE tb_ranking_fa
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE tb_ranking_score_espadachim
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE tb_ranking_score_lutador
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

ALTER TABLE tb_ranking_score_atirador
  MODIFY posicao INT(6) NOT NULL AUTO_INCREMENT;

CREATE PROCEDURE `reseta_missao_r`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  TRUNCATE TABLE tb_missoes_r_dia;

CREATE PROCEDURE `remove_mensagens_antigas`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  DELETE FROM
    tb_mensagens
  WHERE momento < DATE_SUB(NOW(), INTERVAL 5 DAY);

CREATE PROCEDURE `reseta_disposicao`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  UPDATE tb_usuarios
  SET disposicao = '10000';

CREATE PROCEDURE `inicia_coliseu`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_coliseu_cp;
    TRUNCATE TABLE tb_coliseu_fila;
  END;

ALTER TABLE tb_coliseu_ranking
  ADD lvl INT UNSIGNED NULL;

CREATE PROCEDURE `finaliza_coliseu`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_coliseu_ranking;

    INSERT INTO tb_coliseu_ranking (id, cp, lvl)
      (SELECT
         cp.id,
         cp.cp,
         (SELECT max(lvl)
          FROM tb_personagens pers
          WHERE ativo = 1 AND id = cp.id)
       FROM tb_coliseu_cp cp);

    UPDATE tb_usuarios
    SET berries = berries + 10000000
    WHERE id = (SELECT id
                FROM tb_coliseu_ranking
                WHERE lvl >= 45
                ORDER BY cp DESC
                LIMIT 1);

    UPDATE tb_usuarios
    SET berries = berries + 6000000
    WHERE id = (SELECT id
                FROM tb_coliseu_ranking
                WHERE lvl >= 35 AND lvl < 45
                ORDER BY cp DESC
                LIMIT 1);

    UPDATE tb_usuarios
    SET berries = berries + 4000000
    WHERE id = (SELECT id
                FROM tb_coliseu_ranking
                WHERE lvl >= 25 AND lvl < 35
                ORDER BY cp DESC
                LIMIT 1);

    UPDATE tb_usuarios
    SET berries = berries + 2000000
    WHERE id = (SELECT id
                FROM tb_coliseu_ranking
                WHERE lvl < 25
                ORDER BY cp DESC
                LIMIT 1);
  END;

CREATE PROCEDURE `atualiza_ranking_reputacao`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_reputacao;

    INSERT INTO tb_ranking_reputacao (id, nome, reputacao, bandeira, faccao)
      (SELECT
         usr.id,
         usr.tripulacao,
         usr.reputacao,
         usr.bandeira,
         usr.faccao
       FROM tb_usuarios usr
       WHERE usr.adm = 0 AND usr.reputacao > 0 AND (SELECT count(*)
                                                    FROM tb_combate_log log
                                                    WHERE (log.id_1 = usr.id OR log.id_2 = usr.id) AND
                                                          log.tipo IN (1, 2, 5, 7) AND
                                                          log.horario > '2017-09-09 00:00:00') >= 10
       ORDER BY reputacao DESC);
  END;

CREATE PROCEDURE `atualiza_ranking_reputacao_mensal`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_reputacao_mensal;

    INSERT INTO tb_ranking_reputacao_mensal (id, nome, reputacao, bandeira, faccao)
      (SELECT
         usr.id,
         usr.tripulacao,
         usr.reputacao_mensal,
         usr.bandeira,
         usr.faccao
       FROM tb_usuarios usr
       WHERE usr.adm = 0 AND usr.reputacao_mensal > 0 AND (SELECT count(*)
                                                           FROM tb_combate_log log
                                                           WHERE (log.id_1 = usr.id OR log.id_2 = usr.id) AND
                                                                 log.tipo IN (1, 2, 5, 7) AND
                                                                 log.horario > '2017-11-06 00:00:00') >= 3
       ORDER BY reputacao_mensal DESC);
  END;

CREATE PROCEDURE `atualiza_ranking_fa`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_fa;

    INSERT INTO tb_ranking_fa (cod, nome, fama_ameaca, tripulacao, bandeira, faccao)
      (SELECT
         pers.cod,
         pers.nome,
         pers.fama_ameaca,
         usr.tripulacao,
         usr.bandeira,
         usr.faccao
       FROM tb_personagens pers
         INNER JOIN tb_usuarios usr ON pers.id = usr.id
       WHERE usr.adm = 0 AND pers.fama_ameaca > 0
       ORDER BY pers.fama_ameaca DESC);
  END;

CREATE PROCEDURE `atualiza_ranking_espadachim`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_score_espadachim;

    INSERT INTO tb_ranking_score_espadachim (cod, nome, score, tripulacao, bandeira, faccao)
      (SELECT
         pers.cod,
         pers.nome,
         pers.classe_score,
         usr.tripulacao,
         usr.bandeira,
         usr.faccao
       FROM tb_personagens pers
         INNER JOIN tb_usuarios usr ON pers.id = usr.id
       WHERE usr.adm = 0 AND pers.classe = 1
       ORDER BY pers.classe_score DESC);
  END;

CREATE PROCEDURE `atualiza_ranking_lutador`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_score_lutador;

    INSERT INTO tb_ranking_score_lutador (cod, nome, score, tripulacao, bandeira, faccao)
      (SELECT
         pers.cod,
         pers.nome,
         pers.classe_score,
         usr.tripulacao,
         usr.bandeira,
         usr.faccao
       FROM tb_personagens pers
         INNER JOIN tb_usuarios usr ON pers.id = usr.id
       WHERE usr.adm = 0 AND pers.classe = 2
       ORDER BY pers.classe_score DESC);
  END;

CREATE PROCEDURE `atualiza_ranking_atirador`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    TRUNCATE TABLE tb_ranking_score_atirador;

    INSERT INTO tb_ranking_score_atirador (cod, nome, score, tripulacao, bandeira, faccao)
      (SELECT
         pers.cod,
         pers.nome,
         pers.classe_score,
         usr.tripulacao,
         usr.bandeira,
         usr.faccao
       FROM tb_personagens pers
         INNER JOIN tb_usuarios usr ON pers.id = usr.id
       WHERE usr.adm = 0 AND pers.classe = 3
       ORDER BY pers.classe_score DESC);
  END;

INSERT INTO tb_migrations (cod_migration) VALUE (28);