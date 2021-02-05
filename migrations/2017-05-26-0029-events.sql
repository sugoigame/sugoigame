SET GLOBAL event_scheduler = 1;

CREATE EVENT `resets_diarios`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL reseta_disposicao();
    CALL reseta_missao_r();
    CALL remove_mensagens_antigas();
  END;

CREATE EVENT `atualiza_rankings`
  ON SCHEDULE EVERY 1 HOUR
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL atualiza_ranking_reputacao();
    CALL atualiza_ranking_reputacao_mensal();
    CALL atualiza_ranking_fa();
    CALL atualiza_ranking_espadachim();
    CALL atualiza_ranking_lutador();
    CALL atualiza_ranking_atirador();
  END;

CREATE EVENT `inicia_coliseu`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-06-02 23:59:59'
  ON COMPLETION NOT PRESERVE ENABLE DO
  CALL inicia_coliseu();

CREATE EVENT `finaliza_coliseu`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2017-06-04 23:59:59'
  ON COMPLETION NOT PRESERVE ENABLE DO
  CALL finaliza_coliseu();

INSERT INTO tb_migrations (cod_migration) VALUE (29);