CREATE EVENT `bonus_semanal`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2018-01-09 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    DECLARE current_week INT;

    SET current_week := (SELECT WEEK(current_date, 3) -
                                WEEK(current_date - INTERVAL (DAY(current_date) - 1) DAY, 3) + 1 AS week_number);

    IF current_week = 1
    THEN
      INSERT INTO tb_buff_global (buff_id, expiracao)
        VALUE (24, unix_timestamp(TIMESTAMPADD(WEEK, 1, current_timestamp)));
    END IF;

    IF current_week = 2
    THEN
      INSERT INTO tb_buff_global (buff_id, expiracao)
        VALUE (11, unix_timestamp(TIMESTAMPADD(WEEK, 1, current_timestamp)));
    END IF;

    IF current_week = 3
    THEN
      INSERT INTO tb_buff_global (buff_id, expiracao)
        VALUE (12, unix_timestamp(TIMESTAMPADD(WEEK, 1, current_timestamp)));
    END IF;

    IF current_week = 4
    THEN
      INSERT INTO tb_buff_global (buff_id, expiracao)
        VALUE (23, unix_timestamp(TIMESTAMPADD(WEEK, 1, current_timestamp)));
    END IF;
  END;

CREATE EVENT `evento_periodico`
  ON SCHEDULE EVERY 1 WEEK
  STARTS '2018-01-12 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    DECLARE current_week INT;

    SET current_week := (SELECT WEEK(current_date, 3) -
                                WEEK(current_date - INTERVAL (DAY(current_date) - 1) DAY, 3) + 1 AS week_number);

    IF current_week = 1
    THEN
      TRUNCATE tb_evento_recompensa;

      DELETE FROM tb_pve
      WHERE zona >= 15 AND zona <= 21;

      UPDATE tb_variavel_global
      SET valor_varchar = 'eventoPirata'
      WHERE variavel = 'EVENTO_PERIODICO_ATIVO';
    END IF;

    IF current_week = 2
    THEN
      TRUNCATE tb_evento_recompensa;

      DELETE FROM tb_pve
      WHERE zona = 73;

      UPDATE tb_variavel_global
      SET valor_varchar = 'eventoLadroesTesouro'
      WHERE variavel = 'EVENTO_PERIODICO_ATIVO';
    END IF;

    IF current_week = 3
    THEN
      TRUNCATE tb_evento_recompensa;

      DELETE FROM tb_pve
      WHERE zona = 9998;

      UPDATE tb_variavel_global
      SET valor_varchar = 'eventoChefesIlhas'
      WHERE variavel = 'EVENTO_PERIODICO_ATIVO';
    END IF;

    IF current_week = 4
    THEN
      TRUNCATE tb_evento_recompensa;

      UPDATE tb_variavel_global
      SET valor_varchar = 'boss'
      WHERE variavel = 'EVENTO_PERIODICO_ATIVO';
    END IF;
  END;


INSERT INTO tb_migrations (cod_migration) VALUE (107);