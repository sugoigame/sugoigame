ALTER TABLE tb_usuarios
  ADD reputacao_mensal INT UNSIGNED DEFAULT 0 NOT NULL;


CREATE TABLE IF NOT EXISTS `tb_ranking_reputacao_mensal` (
  `posicao` int(6) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `reputacao` int(6) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO tb_migrations (cod_migration) VALUE (25);