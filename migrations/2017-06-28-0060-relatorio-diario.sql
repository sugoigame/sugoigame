CREATE TABLE tb_relatorio_diario
(
  id                          BIGINT PRIMARY KEY NOT NULL AUTO_INCREMENT,
  dia                         TIMESTAMP                   DEFAULT current_timestamp,
  tripulacoes_ativas_24_horas INT,
  contas_ativas_24_horas      INT,
  novas_contas_24_horas       INT
);
ALTER TABLE tb_relatorio_diario
  ADD gold_gasto_24_horas INT NULL;
ALTER TABLE tb_relatorio_diario
  ADD dobrao_gasto_24_horas INT NULL;
ALTER TABLE tb_relatorio_diario
  ADD ips_ativos_24_horas INT NULL DEFAULT 0;

CREATE PROCEDURE `relatorio_diario`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  BEGIN
    INSERT INTO tb_relatorio_diario (tripulacoes_ativas_24_horas, contas_ativas_24_horas, ips_ativos_24_horas, novas_contas_24_horas, gold_gasto_24_horas, dobrao_gasto_24_horas)
    VALUES
      (
        (SELECT count(id) AS total
         FROM tb_usuarios
         WHERE ultimo_logon > unix_timestamp() - 24 * 60 * 60),
        (SELECT count(DISTINCT conta_id) AS total
         FROM tb_usuarios
         WHERE ultimo_logon > unix_timestamp() - 24 * 60 * 60),
        (SELECT count(DISTINCT ip) AS total
         FROM tb_usuarios
         WHERE ultimo_logon > unix_timestamp() - 24 * 60 * 60),
        (SELECT count(conta_id) AS total
         FROM tb_conta
         WHERE tb_conta.cadastro > SUBDATE(now(), INTERVAL 1 DAY)),
        (SELECT sum(quant)
         FROM tb_gold_log
         WHERE quando > SUBDATE(now(), INTERVAL 1 DAY)),
        (SELECT sum(quant)
         FROM tb_dobroes_log
         WHERE data > SUBDATE(now(), INTERVAL 1 DAY))
      );
  END;

CREATE EVENT `relatorio_diario`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-05-27 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL relatorio_diario();
  END;

ALTER TABLE tb_usuarios
  ADD ultima_pagina VARCHAR(255) NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (60);