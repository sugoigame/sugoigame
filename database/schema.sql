 -- IMPORTANTE: HABILITAR NOVAMENTE APOS CRIAR TABELAS
SET FOREIGN_KEY_CHECKS=0;
-- sugoi.chat definition

CREATE TABLE `chat` (
  `id_message` int NOT NULL AUTO_INCREMENT,
  `conta_id` int DEFAULT NULL,
  `capitao` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `canal` varchar(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_message`)
) ENGINE=InnoDB AUTO_INCREMENT=8235 DEFAULT CHARSET=utf8mb3;


-- sugoi.tb_akuma definition

CREATE TABLE `tb_akuma` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_akuma` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL,
  `img` int NOT NULL DEFAULT '100',
  `categoria` int NOT NULL,
  PRIMARY KEY (`cod_akuma`,`cod`),
  UNIQUE KEY `cod_akuma` (`cod_akuma`)
) ENGINE=InnoDB AUTO_INCREMENT=660 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca definition

CREATE TABLE `tb_alianca` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `img` int NOT NULL,
  `mural` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `xp` int NOT NULL DEFAULT '0',
  `xp_max` int NOT NULL DEFAULT '500',
  `lvl` int NOT NULL DEFAULT '1',
  `score` int NOT NULL DEFAULT '0',
  `vitorias` int NOT NULL DEFAULT '0',
  `derrotas` int NOT NULL DEFAULT '0',
  `banco` bigint NOT NULL DEFAULT '0',
  `0` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '111111111111',
  `1` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '111000001100',
  `2` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '110000001000',
  `3` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '100000001000',
  `4` varchar(12) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '000000000000',
  PRIMARY KEY (`cod_alianca`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_guerra definition

CREATE TABLE `tb_alianca_guerra` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_inimigo` int(6) unsigned zerofill NOT NULL,
  `vitoria` int NOT NULL,
  `pts` int NOT NULL DEFAULT '0',
  `fim` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_shop definition

CREATE TABLE `tb_alianca_shop` (
  `cod` int unsigned NOT NULL,
  `tipo` int NOT NULL,
  `lvl` int NOT NULL,
  `preco` int NOT NULL,
  `faccao` int NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ban definition

CREATE TABLE `tb_ban` (
  `ip` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_boss definition

CREATE TABLE `tb_boss` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `real_boss_id` int unsigned NOT NULL,
  `hp` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_buff_global definition

CREATE TABLE `tb_buff_global` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buff_id` int unsigned NOT NULL,
  `expiracao` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_coliseu_itens definition

CREATE TABLE `tb_coliseu_itens` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL,
  `preco` int unsigned NOT NULL,
  UNIQUE KEY `cod` (`cod`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_buff_npc definition

CREATE TABLE `tb_combate_buff_npc` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `efeito` int NOT NULL,
  `atr` int unsigned NOT NULL,
  `espera` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_log_personagem_morto definition

CREATE TABLE `tb_combate_log_personagem_morto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `personagem_id` int(10) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29590 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes definition

CREATE TABLE `tb_combinacoes` (
  `1` int NOT NULL,
  `1_t` int NOT NULL,
  `2` int NOT NULL,
  `2_t` int NOT NULL,
  `3` int NOT NULL,
  `3_t` int NOT NULL,
  `4` int NOT NULL,
  `4_t` int NOT NULL,
  `5` int NOT NULL,
  `5_t` int NOT NULL,
  `6` int NOT NULL,
  `6_t` int NOT NULL,
  `7` int NOT NULL,
  `7_t` int NOT NULL,
  `8` int NOT NULL,
  `8_t` int NOT NULL,
  `lvl` int NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_artesao definition

CREATE TABLE `tb_combinacoes_artesao` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int NOT NULL DEFAULT '0',
  `1` int NOT NULL DEFAULT '0',
  `1_t` int NOT NULL DEFAULT '0',
  `1_q` int NOT NULL DEFAULT '0',
  `2` int NOT NULL DEFAULT '0',
  `2_t` int NOT NULL DEFAULT '0',
  `2_q` int NOT NULL DEFAULT '0',
  `3` int NOT NULL DEFAULT '0',
  `3_t` int NOT NULL DEFAULT '0',
  `3_q` int NOT NULL DEFAULT '0',
  `4` int NOT NULL DEFAULT '0',
  `4_t` int NOT NULL DEFAULT '0',
  `4_q` int NOT NULL DEFAULT '0',
  `5` int NOT NULL DEFAULT '0',
  `5_t` int NOT NULL DEFAULT '0',
  `5_q` int NOT NULL DEFAULT '0',
  `6` int NOT NULL DEFAULT '0',
  `6_t` int NOT NULL DEFAULT '0',
  `6_q` int NOT NULL DEFAULT '0',
  `7` int NOT NULL DEFAULT '0',
  `7_t` int NOT NULL DEFAULT '0',
  `7_q` int NOT NULL DEFAULT '0',
  `8` int NOT NULL DEFAULT '0',
  `8_t` int NOT NULL DEFAULT '0',
  `8_q` int NOT NULL DEFAULT '0',
  `lvl` int NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL DEFAULT '1',
  `visivel` int DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_artesao_aleatorio definition

CREATE TABLE `tb_combinacoes_artesao_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_carpinteiro definition

CREATE TABLE `tb_combinacoes_carpinteiro` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int NOT NULL DEFAULT '0',
  `1` int NOT NULL DEFAULT '0',
  `1_t` int NOT NULL DEFAULT '0',
  `1_q` int NOT NULL DEFAULT '0',
  `2` int NOT NULL DEFAULT '0',
  `2_t` int NOT NULL DEFAULT '0',
  `2_q` int NOT NULL DEFAULT '0',
  `3` int NOT NULL DEFAULT '0',
  `3_t` int NOT NULL DEFAULT '0',
  `3_q` int NOT NULL DEFAULT '0',
  `4` int NOT NULL DEFAULT '0',
  `4_t` int NOT NULL DEFAULT '0',
  `4_q` int NOT NULL DEFAULT '0',
  `5` int NOT NULL DEFAULT '0',
  `5_t` int NOT NULL DEFAULT '0',
  `5_q` int NOT NULL DEFAULT '0',
  `6` int NOT NULL DEFAULT '0',
  `6_t` int NOT NULL DEFAULT '0',
  `6_q` int NOT NULL DEFAULT '0',
  `7` int NOT NULL DEFAULT '0',
  `7_t` int NOT NULL DEFAULT '0',
  `7_q` int NOT NULL DEFAULT '0',
  `8` int NOT NULL DEFAULT '0',
  `8_t` int NOT NULL DEFAULT '0',
  `8_q` int NOT NULL DEFAULT '0',
  `lvl` int NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL DEFAULT '1',
  `visivel` int DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_carpinteiro_aleatorio definition

CREATE TABLE `tb_combinacoes_carpinteiro_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_forja definition

CREATE TABLE `tb_combinacoes_forja` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int NOT NULL DEFAULT '0',
  `1` int NOT NULL DEFAULT '0',
  `1_t` int NOT NULL DEFAULT '0',
  `1_q` int NOT NULL DEFAULT '0',
  `2` int NOT NULL DEFAULT '0',
  `2_t` int NOT NULL DEFAULT '0',
  `2_q` int NOT NULL DEFAULT '0',
  `3` int NOT NULL DEFAULT '0',
  `3_t` int NOT NULL DEFAULT '0',
  `3_q` int NOT NULL DEFAULT '0',
  `4` int NOT NULL DEFAULT '0',
  `4_t` int NOT NULL DEFAULT '0',
  `4_q` int NOT NULL DEFAULT '0',
  `5` int NOT NULL DEFAULT '0',
  `5_t` int NOT NULL DEFAULT '0',
  `5_q` int NOT NULL DEFAULT '0',
  `6` int NOT NULL DEFAULT '0',
  `6_t` int NOT NULL DEFAULT '0',
  `6_q` int NOT NULL DEFAULT '0',
  `7` int NOT NULL DEFAULT '0',
  `7_t` int NOT NULL DEFAULT '0',
  `7_q` int NOT NULL DEFAULT '0',
  `8` int NOT NULL DEFAULT '0',
  `8_t` int NOT NULL DEFAULT '0',
  `8_q` int NOT NULL DEFAULT '0',
  `lvl` int NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL DEFAULT '1',
  `visivel` int DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_forja_aleatorio definition

CREATE TABLE `tb_combinacoes_forja_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int NOT NULL,
  `tipo` int NOT NULL,
  `quant` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_conta definition

CREATE TABLE `tb_conta` (
  `conta_id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_encrip` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tripulacao_id` int(6) unsigned zerofill DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `senha` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cookie` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `ativacao` varchar(8) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gold` int unsigned DEFAULT '0',
  `fbid` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `dobroes` int unsigned NOT NULL DEFAULT '0',
  `dobroes_criados` int unsigned NOT NULL DEFAULT '0',
  `medalhas_recrutamento` int unsigned DEFAULT '0',
  `beta` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`conta_id`),
  UNIQUE KEY `email` (`email`),
  KEY `tripulacao_id` (`tripulacao_id`)
) ENGINE=InnoDB AUTO_INCREMENT=901 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_equipamentos definition

CREATE TABLE `tb_equipamentos` (
  `item` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int NOT NULL,
  `cat_dano` int NOT NULL,
  `b_1` int NOT NULL,
  `b_2` int NOT NULL,
  `categoria` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `lvl` int NOT NULL,
  `treino_max` int NOT NULL,
  `slot` int NOT NULL,
  `requisito` int NOT NULL,
  PRIMARY KEY (`item`)
) ENGINE=InnoDB AUTO_INCREMENT=537 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_era_hall definition

CREATE TABLE `tb_era_hall` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('pirata','marinha') CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `rei_pirata` int DEFAULT NULL,
  `almirante_frota` int DEFAULT NULL,
  `yonkous` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `almirantes` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `termino` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_forum_categoria definition

CREATE TABLE `tb_forum_categoria` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `permite_topico_jogador` tinyint NOT NULL DEFAULT '1',
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `agrupamento` int NOT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_forum_categoria_tb_forum_categoria_id_fk` (`agrupamento`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_habilidades definition

CREATE TABLE `tb_habilidades` (
  `habilidade_id` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `categoria` int unsigned NOT NULL DEFAULT '1' COMMENT '1-Classe, 2-Profissao, 3-Akuma',
  `tipo` int unsigned NOT NULL COMMENT '0-passiva, 1-ataque, 2-buff, 3-cura',
  `requisito_pontos` int unsigned NOT NULL DEFAULT '1',
  `requisito_classe` int unsigned NOT NULL DEFAULT '0',
  `arvore` int unsigned NOT NULL DEFAULT '0',
  `sequencia` int unsigned NOT NULL DEFAULT '0',
  `consumo` int unsigned NOT NULL DEFAULT '0',
  `espera` int unsigned NOT NULL DEFAULT '0',
  `alcance` int unsigned NOT NULL DEFAULT '1',
  `area` int unsigned NOT NULL DEFAULT '1',
  `cura_hp` int unsigned NOT NULL DEFAULT '0',
  `cura_mp` int unsigned NOT NULL DEFAULT '0',
  `mod_dano` int NOT NULL DEFAULT '0',
  `mod_alcance` int NOT NULL DEFAULT '0',
  `bonus_attr` int unsigned NOT NULL DEFAULT '0',
  `bonus_attr_quant` int NOT NULL DEFAULT '0',
  `duracao` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`habilidade_id`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=398 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_bonus_ativo definition

CREATE TABLE `tb_ilha_bonus_ativo` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha` int unsigned NOT NULL,
  `x` int unsigned NOT NULL,
  `y` int unsigned NOT NULL,
  `buff_id` int unsigned NOT NULL,
  `expiracao` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_itens definition

CREATE TABLE `tb_ilha_itens` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int NOT NULL,
  PRIMARY KEY (`ilha`,`cod_item`,`tipo_item`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_mercador definition

CREATE TABLE `tb_ilha_mercador` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha_origem` int unsigned DEFAULT NULL,
  `ilha_destino` int unsigned NOT NULL,
  `recurso` int unsigned NOT NULL,
  `quant` int unsigned NOT NULL,
  `finalizou` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=316 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_missoes definition

CREATE TABLE `tb_ilha_missoes` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`ilha`,`cod_missao`),
  KEY `cod_missao` (`cod_missao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_mod definition

CREATE TABLE `tb_ilha_mod` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `mod` double NOT NULL DEFAULT '1',
  `mod_venda` double NOT NULL DEFAULT '0.8',
  PRIMARY KEY (`ilha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_personagens definition

CREATE TABLE `tb_ilha_personagens` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`ilha`,`img`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_profissoes definition

CREATE TABLE `tb_ilha_profissoes` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `profissao` int NOT NULL,
  `profissao_lvl_max` int NOT NULL,
  PRIMARY KEY (`ilha`,`profissao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_recurso definition

CREATE TABLE `tb_ilha_recurso` (
  `ilha` int unsigned NOT NULL,
  `recurso_0` int unsigned DEFAULT '0',
  `recurso_1` int unsigned DEFAULT '0',
  `recurso_2` int unsigned DEFAULT '0',
  `recurso_gerado` int unsigned DEFAULT NULL,
  PRIMARY KEY (`ilha`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_recurso_venda definition

CREATE TABLE `tb_ilha_recurso_venda` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha` int unsigned NOT NULL,
  `recurso_oferecido` int unsigned NOT NULL,
  `recurso_desejado` int unsigned NOT NULL,
  `quant` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_acessorio definition

CREATE TABLE `tb_item_acessorio` (
  `cod_acessorio` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bonus_atr` int NOT NULL,
  `bonus_atr_qnt` int NOT NULL,
  `img` int NOT NULL,
  `mergulho` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_acessorio`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_comida definition

CREATE TABLE `tb_item_comida` (
  `cod_comida` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `hp_recuperado` int NOT NULL DEFAULT '0',
  `mp_recuperado` int NOT NULL DEFAULT '0',
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL DEFAULT '1',
  `mergulho` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_comida`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_equipamentos definition

CREATE TABLE `tb_item_equipamentos` (
  `item` int(6) unsigned zerofill NOT NULL,
  `cod_equipamento` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int NOT NULL,
  `cat_dano` int NOT NULL,
  `b_1` int NOT NULL,
  `b_2` int NOT NULL,
  `categoria` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `lvl` int NOT NULL,
  `upgrade` int NOT NULL DEFAULT '0',
  `treino_max` int NOT NULL,
  `slot` int NOT NULL,
  `requisito` int NOT NULL,
  PRIMARY KEY (`cod_equipamento`),
  KEY `item` (`item`)
) ENGINE=InnoDB AUTO_INCREMENT=30316 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_mapa definition

CREATE TABLE `tb_item_mapa` (
  `cod_mapa` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `img` int NOT NULL DEFAULT '22',
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Mapa',
  `desenho` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`cod_mapa`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_mapa_visivel definition

CREATE TABLE `tb_item_mapa_visivel` (
  `cod_mapa` int(6) unsigned zerofill NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `mar` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_missao definition

CREATE TABLE `tb_item_missao` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `img` int DEFAULT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `img_format` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'png',
  `tipo_missao` int unsigned DEFAULT NULL,
  `x` int DEFAULT NULL,
  `y` int DEFAULT NULL,
  `method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `rdm_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4408 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_navio_canhao definition

CREATE TABLE `tb_item_navio_canhao` (
  `cod_canhao` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bonus` int NOT NULL,
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL,
  `preco` int NOT NULL,
  `mergulho` int NOT NULL,
  PRIMARY KEY (`cod_canhao`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_navio_casco definition

CREATE TABLE `tb_item_navio_casco` (
  `cod_casco` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(32) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bonus` int NOT NULL,
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL DEFAULT '1',
  `preco` int NOT NULL DEFAULT '1000',
  `mergulho` int NOT NULL DEFAULT '1',
  `kairouseki` tinyint DEFAULT '0',
  PRIMARY KEY (`cod_casco`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_navio_leme definition

CREATE TABLE `tb_item_navio_leme` (
  `cod_leme` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bonus` int NOT NULL,
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL DEFAULT '1',
  `preco` int NOT NULL DEFAULT '1000',
  `mergulho` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_leme`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_navio_velas definition

CREATE TABLE `tb_item_navio_velas` (
  `cod_velas` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bonus` int NOT NULL,
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL DEFAULT '1',
  `preco` int NOT NULL DEFAULT '1000',
  `mergulho` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_velas`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_pose definition

CREATE TABLE `tb_item_pose` (
  `cod_pose` int NOT NULL AUTO_INCREMENT,
  `tipo` int NOT NULL,
  `apontando` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `img` int NOT NULL,
  PRIMARY KEY (`cod_pose`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_reagents definition

CREATE TABLE `tb_item_reagents` (
  `cod_reagent` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `mergulho` int NOT NULL DEFAULT '0',
  `zona` int NOT NULL,
  `mining` int NOT NULL,
  `madeira` int NOT NULL,
  `preco` bigint NOT NULL DEFAULT '100000',
  `method` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `img_format` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'png',
  PRIMARY KEY (`cod_reagent`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_item_remedio definition

CREATE TABLE `tb_item_remedio` (
  `cod_remedio` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `hp_recuperado` int NOT NULL,
  `mp_recuperado` int NOT NULL,
  `img` int NOT NULL,
  `requisito_lvl` int NOT NULL DEFAULT '1',
  `mergulho` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_remedio`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa_rdm definition

CREATE TABLE `tb_mapa_rdm` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `x` int unsigned NOT NULL,
  `y` int unsigned NOT NULL,
  `rdm_id` int unsigned NOT NULL,
  `visivel_cartografo` tinyint unsigned NOT NULL DEFAULT '1',
  `ameaca` int DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=35814 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa_zona_exploracao definition

CREATE TABLE `tb_mapa_zona_exploracao` (
  `id` int NOT NULL AUTO_INCREMENT,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `zona` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa_zona_mergulho definition

CREATE TABLE `tb_mapa_zona_mergulho` (
  `id` int NOT NULL AUTO_INCREMENT,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `zona` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa_zona_rdm definition

CREATE TABLE `tb_mapa_zona_rdm` (
  `id` int NOT NULL AUTO_INCREMENT,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `zona` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mensagens_globais definition

CREATE TABLE `tb_mensagens_globais` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `assunto` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `mensagem` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mini_eventos definition

CREATE TABLE `tb_mini_eventos` (
  `id` bigint unsigned NOT NULL,
  `fim` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pack_recompensa` int unsigned DEFAULT NULL,
  `inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_natal definition

CREATE TABLE `tb_natal` (
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_navio definition

CREATE TABLE `tb_navio` (
  `cod_navio` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `limite` int NOT NULL,
  `img` int NOT NULL,
  `preco` int unsigned NOT NULL,
  PRIMARY KEY (`cod_navio`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_news_coo definition

CREATE TABLE `tb_news_coo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `msg` varchar(255) NOT NULL,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5083 DEFAULT CHARSET=utf8mb3;


-- sugoi.tb_noticias definition

CREATE TABLE `tb_noticias` (
  `cod_noticia` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `texto` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `autor` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `banner` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cod_noticia`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_fa definition

CREATE TABLE `tb_ranking_fa` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fama_ameaca` int NOT NULL,
  `tripulacao` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=2620 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_fugas definition

CREATE TABLE `tb_ranking_fugas` (
  `posicao` int NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `fugas` int NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_reputacao definition

CREATE TABLE `tb_ranking_reputacao` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `reputacao` int NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_reputacao_mensal definition

CREATE TABLE `tb_ranking_reputacao_mensal` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `reputacao` int NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_score_atirador definition

CREATE TABLE `tb_ranking_score_atirador` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `tripulacao` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=480 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_score_espadachim definition

CREATE TABLE `tb_ranking_score_espadachim` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `tripulacao` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=824 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_score_lutador definition

CREATE TABLE `tb_ranking_score_lutador` (
  `posicao` int NOT NULL AUTO_INCREMENT,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `score` int NOT NULL,
  `tripulacao` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=525 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_vd definition

CREATE TABLE `tb_ranking_vd` (
  `posicao` int NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `v_d` int NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ranking_vitorias definition

CREATE TABLE `tb_ranking_vitorias` (
  `posicao` int NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `vitorias` int NOT NULL,
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao` int NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_realizacoes definition

CREATE TABLE `tb_realizacoes` (
  `cod_realizacao` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tipo` int NOT NULL DEFAULT '0',
  `categoria` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pontos` int NOT NULL,
  `titulo` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  PRIMARY KEY (`cod_realizacao`)
) ENGINE=InnoDB AUTO_INCREMENT=279 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_recompensa_recebida_incursao definition

CREATE TABLE `tb_recompensa_recebida_incursao` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_relatorio definition

CREATE TABLE `tb_relatorio` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `relatorio` bigint NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL,
  `nome_skil` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao_skil` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `img_skil` int NOT NULL,
  `effect` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_relatorio_afetados definition

CREATE TABLE `tb_relatorio_afetados` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `relatorio` bigint NOT NULL,
  `acerto` int NOT NULL,
  `quadro` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int unsigned NOT NULL,
  `efeito` int NOT NULL,
  `atributo` int unsigned NOT NULL,
  `cura_h` int unsigned NOT NULL,
  `cura_m` int unsigned NOT NULL,
  `derrotado` int NOT NULL,
  `bloq` int NOT NULL,
  `esq` int NOT NULL,
  `cri` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_relatorio_diario definition

CREATE TABLE `tb_relatorio_diario` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `dia` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tripulacoes_ativas_24_horas` int DEFAULT NULL,
  `contas_ativas_24_horas` int DEFAULT NULL,
  `novas_contas_24_horas` int DEFAULT NULL,
  `gold_gasto_24_horas` int DEFAULT NULL,
  `dobrao_gasto_24_horas` int DEFAULT NULL,
  `ips_ativos_24_horas` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_relatorio_diario_acesso definition

CREATE TABLE `tb_relatorio_diario_acesso` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `dia` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5772 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_rnk_patente definition

CREATE TABLE `tb_rnk_patente` (
  `patente_id` int unsigned NOT NULL AUTO_INCREMENT,
  `nome_0` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `nome_1` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `reputacao` int NOT NULL DEFAULT '0',
  `ranking` int NOT NULL DEFAULT '0',
  `lvl` int NOT NULL DEFAULT '0',
  `reputacao_base` int NOT NULL,
  PRIMARY KEY (`patente_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_titulos definition

CREATE TABLE `tb_titulos` (
  `cod_titulo` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `bonus_atr` int NOT NULL,
  `bonus_atr_quant` int NOT NULL,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `compartilhavel` tinyint DEFAULT '0',
  `nome_f` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`cod_titulo`)
) ENGINE=InnoDB AUTO_INCREMENT=251 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_variavel_global definition

CREATE TABLE `tb_variavel_global` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `variavel` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `valor_int` bigint DEFAULT NULL,
  `valor_varchar` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_vip_compras definition

CREATE TABLE `tb_vip_compras` (
  `id` int NOT NULL AUTO_INCREMENT,
  `conta_id` int NOT NULL,
  `plano_id` int NOT NULL,
  `status` varchar(60) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'WAITING_PAYMENT',
  `criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_baixa` timestamp NULL DEFAULT NULL,
  `metodo` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `referencia` varchar(200) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `gateway` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=381 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_vip_dobro definition

CREATE TABLE `tb_vip_dobro` (
  `id` int NOT NULL AUTO_INCREMENT,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_vip_pagamentos definition

CREATE TABLE `tb_vip_pagamentos` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mensagem` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_vip_planos definition

CREATE TABLE `tb_vip_planos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `valor` float NOT NULL,
  `valor_brl` float DEFAULT NULL,
  `valor_usd` float DEFAULT NULL,
  `valor_eur` float DEFAULT NULL,
  `golds` int NOT NULL,
  `bonus` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_afilhados definition

CREATE TABLE `tb_afilhados` (
  `id` int(11) unsigned zerofill NOT NULL,
  `afilhado` int(11) unsigned zerofill NOT NULL,
  `berries_ganhos` tinyint unsigned DEFAULT '0',
  `medalha_ganha` tinyint unsigned DEFAULT '0',
  `bau_ganho` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`afilhado`),
  UNIQUE KEY `id` (`id`,`afilhado`),
  CONSTRAINT `tb_afilhados_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_afilhados_ibfk_2` FOREIGN KEY (`afilhado`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_akuma_skil_atk definition

CREATE TABLE `tb_akuma_skil_atk` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `consumo` int NOT NULL DEFAULT '0',
  `lvl` int NOT NULL,
  `dano` int NOT NULL,
  `alcance` int NOT NULL DEFAULT '1',
  `area` int NOT NULL DEFAULT '1',
  `espera` int NOT NULL,
  `special_effect` int unsigned DEFAULT NULL,
  `special_target` int unsigned DEFAULT NULL,
  `special_apply_type` int unsigned DEFAULT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`),
  CONSTRAINT `tb_akuma_skil_atk_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2004 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_akuma_skil_buff definition

CREATE TABLE `tb_akuma_skil_buff` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lvl` int NOT NULL,
  `consumo` int NOT NULL DEFAULT '0',
  `bonus_atr` int NOT NULL,
  `bonus_atr_qnt` int NOT NULL,
  `duracao` int NOT NULL,
  `alcance` int NOT NULL DEFAULT '0',
  `area` int NOT NULL DEFAULT '1',
  `espera` int NOT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`),
  CONSTRAINT `tb_akuma_skil_buff_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2685 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_akuma_skil_passiva definition

CREATE TABLE `tb_akuma_skil_passiva` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lvl` int NOT NULL,
  `bonus_atr` int NOT NULL,
  `bonus_atr_qnt` int NOT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`),
  CONSTRAINT `tb_akuma_skil_passiva_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3377 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_aliados definition

CREATE TABLE `tb_alianca_aliados` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_aliado` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`cod_alianca`,`cod_aliado`),
  KEY `cod_aliado` (`cod_aliado`),
  CONSTRAINT `tb_alianca_aliados_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_alianca_aliados_ibfk_2` FOREIGN KEY (`cod_aliado`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_banco definition

CREATE TABLE `tb_alianca_banco` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int NOT NULL,
  `quant` int NOT NULL,
  KEY `cod_alianca` (`cod_alianca`),
  CONSTRAINT `tb_alianca_banco_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_banco_log definition

CREATE TABLE `tb_alianca_banco_log` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `item` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tipo` int NOT NULL,
  KEY `cod_alianca` (`cod_alianca`),
  CONSTRAINT `tb_alianca_banco_log_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_missoes definition

CREATE TABLE `tb_alianca_missoes` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `quant` int NOT NULL DEFAULT '0',
  `fim` int NOT NULL,
  `boss_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`cod_alianca`),
  CONSTRAINT `tb_alianca_missoes_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mini_eventos_concluidos definition

CREATE TABLE `tb_mini_eventos_concluidos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mini_evento_id` bigint unsigned NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_mini_eventos_concluidos_tb_mini_eventos_id_fk` (`mini_evento_id`),
  KEY `tb_mini_eventos_concluidos_tb_usuario_itens_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_mini_eventos_concluidos_tb_mini_eventos_id_fk` FOREIGN KEY (`mini_evento_id`) REFERENCES `tb_mini_eventos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=328 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes definition

CREATE TABLE `tb_missoes` (
  `cod_missao` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `respectiva` int(6) unsigned zerofill DEFAULT NULL,
  `faccao` int NOT NULL,
  `nome` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao_2` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao_3` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `recompensa_xp` int NOT NULL,
  `recompensa_berries` int NOT NULL,
  `requisito_lvl` int NOT NULL,
  `requisito_missao` int(6) unsigned zerofill NOT NULL,
  `duracao` double NOT NULL,
  `img` int NOT NULL,
  PRIMARY KEY (`cod_missao`),
  KEY `respectiva` (`respectiva`),
  CONSTRAINT `tb_missoes_ibfk_1` FOREIGN KEY (`respectiva`) REFERENCES `tb_missoes` (`cod_missao`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=512 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_reset_senha_token definition

CREATE TABLE `tb_reset_senha_token` (
  `conta_id` int(10) unsigned zerofill NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `expiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `tb_reset_senha_token_token_uindex` (`token`),
  KEY `tb_reset_senha_token_tb_conta_conta_id_fk` (`conta_id`),
  CONSTRAINT `tb_reset_senha_token_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_rota_mercador definition

CREATE TABLE `tb_rota_mercador` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mercador_id` bigint unsigned NOT NULL,
  `indice` int unsigned NOT NULL,
  `x` int unsigned NOT NULL,
  `y` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_rota_mercador_tb_ilha_mercador_id_fk` (`mercador_id`),
  CONSTRAINT `tb_rota_mercador_tb_ilha_mercador_id_fk` FOREIGN KEY (`mercador_id`) REFERENCES `tb_ilha_mercador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_afilhados_recrutados definition

CREATE TABLE `tb_afilhados_recrutados` (
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_afilhados_recrutados_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_convite definition

CREATE TABLE `tb_alianca_convite` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `convidado` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`cod_alianca`,`convidado`),
  KEY `convidado` (`convidado`),
  CONSTRAINT `tb_alianca_convite_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_alianca_convite_ibfk_2` FOREIGN KEY (`convidado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_guerra_ajuda definition

CREATE TABLE `tb_alianca_guerra_ajuda` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int NOT NULL,
  PRIMARY KEY (`cod_alianca`,`id`),
  KEY `id` (`id`),
  CONSTRAINT `tb_alianca_guerra_ajuda_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_alianca_guerra_ajuda_ibfk_2` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_guerra_pedidos definition

CREATE TABLE `tb_alianca_guerra_pedidos` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `convidado` int(6) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL,
  PRIMARY KEY (`cod_alianca`,`convidado`),
  KEY `convidado` (`convidado`),
  CONSTRAINT `tb_alianca_guerra_pedidos_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_alianca_guerra_pedidos_ibfk_2` FOREIGN KEY (`convidado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_alianca_membros definition

CREATE TABLE `tb_alianca_membros` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `autoridade` int NOT NULL DEFAULT '4',
  `cooperacao` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cod_alianca` (`cod_alianca`),
  CONSTRAINT `tb_alianca_membros_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_alianca_membros_ibfk_2` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_amigos definition

CREATE TABLE `tb_amigos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `amigo` int(6) unsigned zerofill NOT NULL,
  `capitao` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`,`amigo`),
  KEY `amigo` (`amigo`),
  CONSTRAINT `tb_amigos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_amigos_ibfk_2` FOREIGN KEY (`amigo`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_boss_damage definition

CREATE TABLE `tb_boss_damage` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `real_boss_id` int unsigned NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `damage` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_boss_damage_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_boss_damage_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_coliseu_cp definition

CREATE TABLE `tb_coliseu_cp` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cp` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_coliseu_cp_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_coliseu_fila definition

CREATE TABLE `tb_coliseu_fila` (
  `id` int(6) unsigned zerofill NOT NULL,
  `pausado` tinyint DEFAULT '0',
  `momento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `lvl` int DEFAULT NULL,
  `desafio_momento` timestamp NULL DEFAULT NULL,
  `desafio` int(10) unsigned zerofill DEFAULT NULL,
  `desafio_aceito` tinyint DEFAULT '0',
  `busca_casual` tinyint DEFAULT '0',
  `busca_competitivo` tinyint DEFAULT '0',
  `busca_coliseu` tinyint DEFAULT '0',
  `desafio_tipo` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_coliseu_fila_tb_usuarios_id_fk` (`desafio`),
  CONSTRAINT `tb_coliseu_fila_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_coliseu_fila_tb_usuarios_id_fk` FOREIGN KEY (`desafio`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_coliseu_ranking definition

CREATE TABLE `tb_coliseu_ranking` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cp` int unsigned NOT NULL,
  `lvl` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_coliseu_ranking_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate definition

CREATE TABLE `tb_combate` (
  `combate` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_1` int(6) unsigned zerofill NOT NULL,
  `id_2` int(6) unsigned zerofill NOT NULL,
  `vez` int NOT NULL DEFAULT '0',
  `vez_tempo` double NOT NULL DEFAULT '0',
  `passe_1` int NOT NULL DEFAULT '0',
  `passe_2` int NOT NULL DEFAULT '0',
  `move_1` int NOT NULL DEFAULT '0',
  `move_2` int NOT NULL DEFAULT '0',
  `relatorio` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `saiu_1` int NOT NULL DEFAULT '0',
  `saiu_2` int NOT NULL DEFAULT '0',
  `recop_1` double NOT NULL DEFAULT '0',
  `recop_2` double NOT NULL DEFAULT '0',
  `tipo` int NOT NULL DEFAULT '1',
  `permite_apostas_1` tinyint DEFAULT '1',
  `permite_apostas_2` tinyint DEFAULT '1',
  `premio_apostas` int unsigned DEFAULT '0',
  `preco_apostas` int unsigned DEFAULT '0',
  `fim_apostas` tinyint DEFAULT '0',
  `battle_back` int unsigned DEFAULT NULL,
  `permite_dados_1` tinyint unsigned DEFAULT '0',
  `permite_dados_2` tinyint unsigned DEFAULT '0',
  `inicio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`combate`),
  UNIQUE KEY `id_1` (`id_1`,`id_2`),
  KEY `id_2` (`id_2`),
  CONSTRAINT `tb_combate_ibfk_1` FOREIGN KEY (`id_1`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_ibfk_2` FOREIGN KEY (`id_2`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_apostas definition

CREATE TABLE `tb_combate_apostas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `combate_id` bigint(20) unsigned zerofill NOT NULL,
  `aposta` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combate_apostas_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_combate_apostas_tb_combate_combate_fk` (`combate_id`),
  CONSTRAINT `tb_combate_apostas_tb_combate_combate_fk` FOREIGN KEY (`combate_id`) REFERENCES `tb_combate` (`combate`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_apostas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_bot definition

CREATE TABLE `tb_combate_bot` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_inimiga` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `faccao_inimiga` int unsigned NOT NULL,
  `bandeira_inimiga` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `vez` int NOT NULL DEFAULT '1',
  `move` int NOT NULL DEFAULT '5',
  `battle_back` int unsigned DEFAULT NULL,
  `incursao` tinyint unsigned DEFAULT NULL,
  `relatorio` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `mercador` bigint unsigned DEFAULT NULL,
  `disputa_ilha` tinyint unsigned DEFAULT NULL,
  `haki` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combate_bot_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_combate_bot_tb_ilha_mercador_id_fk` (`mercador`),
  CONSTRAINT `tb_combate_bot_tb_ilha_mercador_id_fk` FOREIGN KEY (`mercador`) REFERENCES `tb_ilha_mercador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_bot_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12551 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_buff definition

CREATE TABLE `tb_combate_buff` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_buff` int(4) unsigned zerofill NOT NULL,
  `atr` int NOT NULL,
  `efeito` int NOT NULL,
  `espera` int NOT NULL,
  KEY `tb_combate_buff_ibfk_1` (`cod`),
  CONSTRAINT `tb_combate_buff_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_buff_bot definition

CREATE TABLE `tb_combate_buff_bot` (
  `id` bigint unsigned NOT NULL,
  `cod` bigint unsigned NOT NULL,
  `cod_buff` int(4) unsigned zerofill NOT NULL,
  `atr` int NOT NULL,
  `efeito` int NOT NULL,
  `espera` int NOT NULL,
  KEY `tb_combate_buff_bot_ibfk_1` (`cod`),
  CONSTRAINT `tb_combate_buff_bot_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_combate_personagens_bot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_desafio definition

CREATE TABLE `tb_combate_desafio` (
  `desafiante` int(6) unsigned zerofill NOT NULL,
  `desafiante_nome` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `desafiado` int(6) unsigned zerofill NOT NULL,
  UNIQUE KEY `desafiante` (`desafiante`,`desafiado`),
  KEY `desafiado` (`desafiado`),
  CONSTRAINT `tb_combate_desafio_ibfk_1` FOREIGN KEY (`desafiante`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_desafio_ibfk_2` FOREIGN KEY (`desafiado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_log definition

CREATE TABLE `tb_combate_log` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `id_1` int(6) unsigned zerofill NOT NULL,
  `id_2` int(6) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL,
  `horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pos_1` varchar(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `pos_2` varchar(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ip_1` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `ip_2` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `vencedor` int(10) unsigned zerofill DEFAULT NULL,
  `reputacao_ganha` int unsigned DEFAULT NULL,
  `reputacao_perdida` int unsigned DEFAULT NULL,
  `reputacao_mensal_ganha` int unsigned DEFAULT NULL,
  `reputacao_mensal_perdida` int unsigned DEFAULT NULL,
  `berries_ganhos` int unsigned DEFAULT NULL,
  `berries_perdidos` int unsigned DEFAULT NULL,
  `relatorio` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `reputacao_anterior_vencedor` int unsigned DEFAULT NULL,
  `reputacao_anterior_perdedor` int unsigned DEFAULT NULL,
  `reputacao_mensal_anterior_vencedor` int unsigned DEFAULT NULL,
  `reputacao_mensal_anterior_perdedor` int unsigned DEFAULT NULL,
  `fim` timestamp NULL DEFAULT NULL,
  KEY `id_1` (`id_1`),
  KEY `id_2` (`id_2`),
  CONSTRAINT `tb_combate_log_ibfk_1` FOREIGN KEY (`id_1`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_log_ibfk_2` FOREIGN KEY (`id_2`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_log_npc definition

CREATE TABLE `tb_combate_log_npc` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `rdm_id` int unsigned NOT NULL,
  `relatorio` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_combate_log_npc_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_combate_log_npc_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=122941 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_npc definition

CREATE TABLE `tb_combate_npc` (
  `id` int(6) unsigned zerofill NOT NULL,
  `img_npc` int NOT NULL,
  `nome_npc` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT 'Rei dos Mares',
  `hp_npc` int NOT NULL,
  `hp_max_npc` int NOT NULL,
  `mp_npc` int NOT NULL,
  `mp_max_npc` int NOT NULL,
  `atk_npc` int NOT NULL,
  `def_npc` int NOT NULL,
  `agl_npc` int NOT NULL,
  `res_npc` int NOT NULL,
  `pre_npc` int NOT NULL,
  `dex_npc` int NOT NULL,
  `con_npc` int NOT NULL,
  `dano` int NOT NULL DEFAULT '0',
  `armadura` int NOT NULL DEFAULT '0',
  `move` int NOT NULL DEFAULT '5',
  `relatorio` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `buff_npc` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `zona` int NOT NULL DEFAULT '2',
  `boss_id` int unsigned DEFAULT NULL,
  `battle_back` int unsigned DEFAULT NULL,
  `chefe_ilha` tinyint DEFAULT '0',
  `mira` int DEFAULT '0',
  `skin_npc` int DEFAULT NULL,
  `chefe_especial` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combate_npc_tb_boss_id_fk` (`boss_id`),
  CONSTRAINT `tb_combate_npc_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_npc_tb_boss_id_fk` FOREIGN KEY (`boss_id`) REFERENCES `tb_boss` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_personagens definition

CREATE TABLE `tb_combate_personagens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `hp` int NOT NULL,
  `hp_max` int NOT NULL,
  `mp` int NOT NULL,
  `mp_max` int NOT NULL,
  `atk` int NOT NULL,
  `def` int NOT NULL,
  `agl` int NOT NULL,
  `res` int NOT NULL,
  `pre` int NOT NULL,
  `dex` int NOT NULL,
  `con` int NOT NULL,
  `vit` int NOT NULL,
  `quadro_x` int NOT NULL,
  `quadro_y` int NOT NULL,
  `haki_esq` int NOT NULL DEFAULT '0',
  `haki_cri` int NOT NULL DEFAULT '0',
  `haki_blo` int NOT NULL DEFAULT '0',
  `fa_ganha` bigint unsigned DEFAULT '0',
  `desistencia` tinyint NOT NULL DEFAULT '0',
  `img` int unsigned DEFAULT NULL,
  `skin_r` int unsigned DEFAULT NULL,
  `skin_c` int unsigned DEFAULT NULL,
  `medico_usado` tinyint DEFAULT '0',
  PRIMARY KEY (`cod`),
  KEY `id` (`id`),
  CONSTRAINT `tb_combate_personagens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_personagens_ibfk_2` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_personagens_bot definition

CREATE TABLE `tb_combate_personagens_bot` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `combate_bot_id` bigint unsigned NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `img` int NOT NULL,
  `lvl` int NOT NULL,
  `skin_r` int NOT NULL,
  `skin_c` int NOT NULL,
  `hp` int NOT NULL,
  `hp_max` int NOT NULL,
  `mp` int NOT NULL,
  `mp_max` int NOT NULL,
  `atk` int NOT NULL,
  `def` int NOT NULL,
  `agl` int NOT NULL,
  `res` int NOT NULL,
  `pre` int NOT NULL,
  `dex` int NOT NULL,
  `con` int NOT NULL,
  `vit` int NOT NULL,
  `quadro_x` int NOT NULL,
  `quadro_y` int NOT NULL,
  `haki_esq` int NOT NULL DEFAULT '0',
  `haki_cri` int NOT NULL DEFAULT '0',
  `haki_blo` int NOT NULL DEFAULT '0',
  `categoria_akuma` int DEFAULT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `classe` int DEFAULT NULL,
  `classe_score` int DEFAULT '1000',
  `pack_habilidade_id` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combate_personagens_bot_ibfk_1` (`combate_bot_id`),
  CONSTRAINT `tb_combate_personagens_bot_ibfk_1` FOREIGN KEY (`combate_bot_id`) REFERENCES `tb_combate_bot` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59696 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_skil_espera definition

CREATE TABLE `tb_combate_skil_espera` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(4) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL,
  `espera` int NOT NULL,
  PRIMARY KEY (`cod`,`cod_skil`,`tipo`),
  KEY `id` (`id`),
  CONSTRAINT `tb_combate_skil_espera_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_skil_espera_ibfk_2` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combate_special_effect definition

CREATE TABLE `tb_combate_special_effect` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `combate_id` bigint unsigned DEFAULT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `personagem_id` int(10) unsigned zerofill DEFAULT NULL,
  `special_effect` int NOT NULL,
  `duracao` int unsigned NOT NULL,
  `momento` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `personagem_bot_id` bigint unsigned DEFAULT NULL,
  `bot_id` bigint unsigned DEFAULT NULL,
  `vontade` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combate_special_effect_tb_combate_combate_fk` (`combate_id`),
  KEY `tb_combate_special_effect_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_combate_special_effect_tb_personagens_cod_fk` (`personagem_id`),
  CONSTRAINT `tb_combate_special_effect_tb_combate_combate_fk` FOREIGN KEY (`combate_id`) REFERENCES `tb_combate` (`combate`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_special_effect_tb_personagens_cod_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combate_special_effect_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=63076 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_artesao_conhecidas definition

CREATE TABLE `tb_combinacoes_artesao_conhecidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `combinacao_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combinacoes_artesao_conhecidas_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb__artesao_conhecidas_tb_artesao_cod_receita_fk` (`combinacao_id`),
  CONSTRAINT `tb__artesao_conhecidas_tb_artesao_cod_receita_fk` FOREIGN KEY (`combinacao_id`) REFERENCES `tb_combinacoes_artesao` (`cod_receita`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combinacoes_artesao_conhecidas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_carpinteiro_conhecidas definition

CREATE TABLE `tb_combinacoes_carpinteiro_conhecidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `combinacao_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combinacoes_carpinteiro_conhecidas_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb__carpinteiro_conhecidas_tb_carpinteiro_cod_receita_fk` (`combinacao_id`),
  CONSTRAINT `tb__carpinteiro_conhecidas_tb_carpinteiro_cod_receita_fk` FOREIGN KEY (`combinacao_id`) REFERENCES `tb_combinacoes_carpinteiro` (`cod_receita`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combinacoes_carpinteiro_conhecidas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_combinacoes_forja_conhecidas definition

CREATE TABLE `tb_combinacoes_forja_conhecidas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `combinacao_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_combinacoes_forja_conhecidas_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb__forja_conhecidas_tb_forja_cod_receita_fk` (`combinacao_id`),
  CONSTRAINT `tb__forja_conhecidas_tb_forja_cod_receita_fk` FOREIGN KEY (`combinacao_id`) REFERENCES `tb_combinacoes_forja` (`cod_receita`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_combinacoes_forja_conhecidas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_dobroes_leilao_log definition

CREATE TABLE `tb_dobroes_leilao_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vendedor_id` int(10) unsigned zerofill DEFAULT NULL,
  `comprador_id` int(10) unsigned zerofill DEFAULT NULL,
  `quant` int unsigned NOT NULL,
  `preco_unitario` int unsigned NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_dobroes_leilao_log_tb_usuarios_id_fk_vendedor` (`vendedor_id`),
  KEY `tb_dobroes_leilao_log_tb_usuarios_id_fk_comprador` (`comprador_id`),
  CONSTRAINT `tb_dobroes_leilao_log_tb_usuarios_id_fk_comprador` FOREIGN KEY (`comprador_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_dobroes_leilao_log_tb_usuarios_id_fk_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=726 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_dobroes_log definition

CREATE TABLE `tb_dobroes_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `conta_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `quant` int unsigned NOT NULL,
  `script` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_dobroes_log_tb_conta_conta_id_fk` (`conta_id`),
  KEY `tb_dobroes_log_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_dobroes_log_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_dobroes_log_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1348 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_dobroes_oferta definition

CREATE TABLE `tb_dobroes_oferta` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `quant` int unsigned NOT NULL,
  `preco_unitario` int unsigned NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_dobroes_oferta_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_dobroes_oferta_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_evento_amizade_brindes definition

CREATE TABLE `tb_evento_amizade_brindes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_evento_amizade_brindes_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_evento_amizade_brindes_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_evento_amizade_recompensa definition

CREATE TABLE `tb_evento_amizade_recompensa` (
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `recompensa_id` int unsigned NOT NULL,
  KEY `tb_evento_amizade_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_evento_amizade_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_evento_chefes definition

CREATE TABLE `tb_evento_chefes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha` int NOT NULL,
  `personagem_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_evento_chefes_tb_personagens_cod_fk` (`personagem_id`),
  KEY `tb_evento_chefes_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_evento_chefes_tb_personagens_cod_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_evento_chefes_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_evento_recompensa definition

CREATE TABLE `tb_evento_recompensa` (
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `recompensa_id` int unsigned NOT NULL,
  KEY `tb_evento_amizade_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_evento_piratas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_forum_likes definition

CREATE TABLE `tb_forum_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `tipo` int DEFAULT NULL,
  `data_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `post_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_forum_likes_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_forum_likes_tb_forum_post_id_fk` (`post_id`),
  CONSTRAINT `tb_forum_likes_tb_forum_post_id_fk` FOREIGN KEY (`post_id`) REFERENCES `tb_forum_post` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_forum_likes_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_forum_post definition

CREATE TABLE `tb_forum_post` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conteudo` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oculto` tinyint NOT NULL DEFAULT '0',
  `topico_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_forum_comentario_tb_forum_topico_id_fk` (`topico_id`),
  KEY `tb_forum_comentario_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_forum_comentario_tb_forum_topico_id_fk` FOREIGN KEY (`topico_id`) REFERENCES `tb_forum_topico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_forum_comentario_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_forum_topico definition

CREATE TABLE `tb_forum_topico` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `categoria_id` bigint unsigned NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `criador_id` int(10) unsigned zerofill DEFAULT NULL,
  `bloqueado` tinyint NOT NULL DEFAULT '0',
  `resolvido` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_forum_topico_tb_forum_categoria_id_fk` (`categoria_id`),
  KEY `tb_forum_topico_tb_usuarios_id_fk` (`criador_id`),
  CONSTRAINT `tb_forum_topico_tb_forum_categoria_id_fk` FOREIGN KEY (`categoria_id`) REFERENCES `tb_forum_categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_forum_topico_tb_usuarios_id_fk` FOREIGN KEY (`criador_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_forum_topico_lido definition

CREATE TABLE `tb_forum_topico_lido` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `topico_id` bigint unsigned NOT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_forum_topico_lido_tb_forum_topico_id_fk` (`topico_id`),
  KEY `tb_forum_topico_lido_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_forum_topico_lido_tb_forum_topico_id_fk` FOREIGN KEY (`topico_id`) REFERENCES `tb_forum_topico` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_forum_topico_lido_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_gold_log definition

CREATE TABLE `tb_gold_log` (
  `id` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned zerofill NOT NULL,
  `quant` int NOT NULL DEFAULT '0',
  `quando` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `script` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_gold_log_tb_usuarios_id_fk` (`user_id`),
  CONSTRAINT `tb_gold_log_tb_usuarios_id_fk` FOREIGN KEY (`user_id`) REFERENCES `tb_usuarios` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5610 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_haki_treino definition

CREATE TABLE `tb_haki_treino` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `data` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_haki_treino_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_haki_treino_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6496 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_disputa definition

CREATE TABLE `tb_ilha_disputa` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha` int unsigned NOT NULL,
  `vencedor_id` int(10) unsigned zerofill DEFAULT NULL,
  `vencedor_pronto` tinyint unsigned NOT NULL DEFAULT '0',
  `dono_pronto` tinyint unsigned NOT NULL DEFAULT '0',
  `fim` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_ilha_disputa_tb_usuarios_id_fk` (`vencedor_id`),
  CONSTRAINT `tb_ilha_disputa_tb_usuarios_id_fk` FOREIGN KEY (`vencedor_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_disputa_progresso definition

CREATE TABLE `tb_ilha_disputa_progresso` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `progresso` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_ilha_disputa_progresso_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_ilha_disputa_progresso_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_incursao_protecao definition

CREATE TABLE `tb_ilha_incursao_protecao` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ilha` int unsigned NOT NULL,
  `sequencia` int unsigned NOT NULL,
  `personagem_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_ilha_incursao_protecao_tb_personagens_cod_fk` (`personagem_id`),
  CONSTRAINT `tb_ilha_incursao_protecao_tb_personagens_cod_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_ilha_mercador_personagem definition

CREATE TABLE `tb_ilha_mercador_personagem` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `mercador_id` bigint unsigned NOT NULL,
  `personagem_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_ilha_mercador_personagem_tb_ilha_mercador_id_fk` (`mercador_id`),
  KEY `tb_ilha_mercador_personagem_tb_personagens_cod_fk` (`personagem_id`),
  CONSTRAINT `tb_ilha_mercador_personagem_tb_ilha_mercador_id_fk` FOREIGN KEY (`mercador_id`) REFERENCES `tb_ilha_mercador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_ilha_mercador_personagem_tb_personagens_cod_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_incursao_nivel definition

CREATE TABLE `tb_incursao_nivel` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `nivel` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_incursao_nivel_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_incursao_nivel_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1872 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_incursao_personagem definition

CREATE TABLE `tb_incursao_personagem` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `personagem_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `pontos` int unsigned NOT NULL DEFAULT '0',
  `nivel` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_incursao_personagem_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_incursao_personagem_tb_personagens_id_fk` (`personagem_id`),
  CONSTRAINT `tb_incursao_personagem_tb_personagens_id_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_incursao_personagem_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_incursao_pontos definition

CREATE TABLE `tb_incursao_pontos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned DEFAULT NULL,
  `pontos_espadachim` int unsigned NOT NULL DEFAULT '0',
  `pontos_lutador` int unsigned NOT NULL DEFAULT '0',
  `pontos_atirador` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_incursao_pontos_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_incursao_pontos_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_incursao_progresso definition

CREATE TABLE `tb_incursao_progresso` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `progresso` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_incursao_progresso_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_incursao_progresso_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=144 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_incursao_recompensa_recebida definition

CREATE TABLE `tb_incursao_recompensa_recebida` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `nivel` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_incursa_recompensa_recebida_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_incursa_recompensa_recebida_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_inimigos definition

CREATE TABLE `tb_inimigos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `personagem` int(6) unsigned zerofill NOT NULL,
  `inimigo` int(6) unsigned zerofill NOT NULL,
  `fa` int NOT NULL,
  KEY `id` (`id`),
  KEY `personagem` (`personagem`),
  KEY `inimigo` (`inimigo`),
  CONSTRAINT `tb_inimigos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_inimigos_ibfk_2` FOREIGN KEY (`personagem`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_jardim_laftel definition

CREATE TABLE `tb_jardim_laftel` (
  `id` int(6) unsigned zerofill NOT NULL,
  `tempo` double NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_jardim_laftel_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_kanban_item definition

CREATE TABLE `tb_kanban_item` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `title` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `description` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `column` int unsigned NOT NULL DEFAULT '0',
  `tripulacao_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_kanban_item_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_kanban_item_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_kanban_rate definition

CREATE TABLE `tb_kanban_rate` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kanban_item_id` int unsigned DEFAULT NULL,
  `conta_id` int(10) unsigned zerofill NOT NULL,
  `rate` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tb_kanban_rate_kanban_item_id_conta_id_uindex` (`kanban_item_id`,`conta_id`),
  KEY `tb_kanban_rate_tb_conta_conta_id_fk` (`conta_id`),
  CONSTRAINT `tb_kanban_rate_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_kanban_rate_tb_kanban_item_id_fk` FOREIGN KEY (`kanban_item_id`) REFERENCES `tb_kanban_item` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_log_acesso definition

CREATE TABLE `tb_log_acesso` (
  `conta_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `url` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  KEY `tb_log_acesso_tb_conta_conta_id_fk` (`conta_id`),
  KEY `tb_log_acesso_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_log_acesso_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`),
  CONSTRAINT `tb_log_acesso_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa definition

CREATE TABLE `tb_mapa` (
  `x` int NOT NULL,
  `y` int NOT NULL,
  `navegavel` tinyint(1) NOT NULL,
  `ilha` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `tipo_vento` int NOT NULL DEFAULT '0',
  `dir_vento` int NOT NULL DEFAULT '0',
  `tipo_corrente` int NOT NULL DEFAULT '0',
  `dir_corrente` int NOT NULL DEFAULT '0',
  `mar` int NOT NULL,
  `nevoa` int NOT NULL DEFAULT '0',
  `damage` int NOT NULL DEFAULT '0',
  `tele` int NOT NULL DEFAULT '0',
  `tele_x` int NOT NULL DEFAULT '0',
  `tele_y` int NOT NULL DEFAULT '0',
  `zona` int NOT NULL DEFAULT '2',
  `ilha_dono` int(10) unsigned zerofill DEFAULT NULL,
  `zona_especial` int DEFAULT NULL,
  PRIMARY KEY (`x`,`y`),
  KEY `x` (`x`),
  KEY `y` (`y`),
  KEY `tb_mapa_tb_usuarios_id_fk` (`ilha_dono`),
  CONSTRAINT `tb_mapa_tb_usuarios_id_fk` FOREIGN KEY (`ilha_dono`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mapa_contem definition

CREATE TABLE `tb_mapa_contem` (
  `increment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `id` int(6) unsigned zerofill DEFAULT NULL,
  `nps_id` int unsigned DEFAULT NULL,
  `mercador_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`increment_id`),
  UNIQUE KEY `tb_mapa_contem_id_uindex` (`id`),
  KEY `tb_mapa_contem_tb_ilha_mercador_id_fk` (`mercador_id`),
  CONSTRAINT `tb_mapa_contem_tb_ilha_mercador_id_fk` FOREIGN KEY (`mercador_id`) REFERENCES `tb_ilha_mercador` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_mapa_contem_tb_usuarios_id_fk` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=188193 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_marcenaria_reparos definition

CREATE TABLE `tb_marcenaria_reparos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `tempo` double NOT NULL,
  `tipo` int NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_marcenaria_reparos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mensagens definition

CREATE TABLE `tb_mensagens` (
  `cod_mensagem` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `remetente` int(6) unsigned zerofill NOT NULL,
  `destinatario` int(6) unsigned zerofill NOT NULL,
  `assunto` varchar(40) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `texto` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `lido` int NOT NULL DEFAULT '0',
  `hora` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`cod_mensagem`),
  KEY `remetente` (`remetente`),
  KEY `destinatario` (`destinatario`),
  CONSTRAINT `tb_mensagens_ibfk_1` FOREIGN KEY (`remetente`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_mensagens_ibfk_2` FOREIGN KEY (`destinatario`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2621 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_mensagens_globais_lidas definition

CREATE TABLE `tb_mensagens_globais_lidas` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mensagem_id` int unsigned NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_mensagens_globais_lidas_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_mensagens_globais_lidas_tb_mensagens_globais_id_fk` (`mensagem_id`),
  CONSTRAINT `tb_mensagens_globais_lidas_tb_mensagens_globais_id_fk` FOREIGN KEY (`mensagem_id`) REFERENCES `tb_mensagens_globais` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_mensagens_globais_lidas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_caca_diario definition

CREATE TABLE `tb_missoes_caca_diario` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `missao_caca_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_missoes_caca_diario_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_missoes_caca_diario_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_chefe_ilha definition

CREATE TABLE `tb_missoes_chefe_ilha` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha_derrotado` int unsigned NOT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `recompensa_recebida` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_missoes_chefe_ilha_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_missoes_chefe_ilha_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1468 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_concluidas definition

CREATE TABLE `tb_missoes_concluidas` (
  `increment_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`increment_id`),
  KEY `cod_missao` (`cod_missao`),
  KEY `tb_missoes_concluidas_tb_usuarios_id_fk` (`id`),
  CONSTRAINT `tb_missoes_concluidas_tb_usuarios_id_fk` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14619 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_concluidas_dia definition

CREATE TABLE `tb_missoes_concluidas_dia` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `ilha` int unsigned NOT NULL,
  `quant` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_missoes_concluidas_dia_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_missoes_concluidas_dia_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_iniciadas definition

CREATE TABLE `tb_missoes_iniciadas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  `fim` double NOT NULL,
  `log` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `venceu` tinyint DEFAULT NULL,
  `hp_final` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `mp_final` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `tipo_karma` varchar(3) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cod_missao` (`cod_missao`),
  CONSTRAINT `tb_missoes_iniciadas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_r definition

CREATE TABLE `tb_missoes_r` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `fim` double NOT NULL,
  `modif` int NOT NULL,
  PRIMARY KEY (`id`,`x`,`y`),
  CONSTRAINT `tb_missoes_r_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_missoes_r_dia definition

CREATE TABLE `tb_missoes_r_dia` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  PRIMARY KEY (`id`,`x`,`y`),
  CONSTRAINT `tb_missoes_r_dia_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_noticia_comment definition

CREATE TABLE `tb_noticia_comment` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oculto` int NOT NULL DEFAULT '0',
  `conteudo` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `tb_noticia_comment_tb_noticias_cod_noticia_fk` (`noticia_id`),
  KEY `tb_noticia_comment_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_noticia_comment_tb_noticias_cod_noticia_fk` FOREIGN KEY (`noticia_id`) REFERENCES `tb_noticias` (`cod_noticia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_noticia_comment_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_noticia_lida definition

CREATE TABLE `tb_noticia_lida` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `noticia_id` int(10) unsigned zerofill NOT NULL,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `data_leitura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_noticia_lida_tb_noticias_cod_noticia_fk` (`noticia_id`),
  KEY `tb_noticia_lida_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_noticia_lida_tb_noticias_cod_noticia_fk` FOREIGN KEY (`noticia_id`) REFERENCES `tb_noticias` (`cod_noticia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_noticia_lida_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=408 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_noticia_likes definition

CREATE TABLE `tb_noticia_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `tipo` int DEFAULT NULL,
  `data_like` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_noticia_likes_tb_noticia_id_fk` (`comment_id`),
  KEY `tb_noticia_likes_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_noticia_likes_tb_noticia_comment_id_fk` FOREIGN KEY (`comment_id`) REFERENCES `tb_noticia_comment` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_noticia_likes_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_obstaculos definition

CREATE TABLE `tb_obstaculos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `x` int unsigned NOT NULL,
  `y` int unsigned NOT NULL,
  `tipo` int DEFAULT NULL,
  `hp` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_obstaculos_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_obstaculos_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2162 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagem_equip_treino definition

CREATE TABLE `tb_personagem_equip_treino` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `item` int(6) unsigned zerofill NOT NULL,
  `xp` int unsigned NOT NULL,
  PRIMARY KEY (`cod`,`item`),
  CONSTRAINT `tb_personagem_equip_treino_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagem_equipamentos definition

CREATE TABLE `tb_personagem_equipamentos` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `1` int(10) unsigned zerofill DEFAULT NULL,
  `2` int(10) unsigned zerofill DEFAULT NULL,
  `3` int(10) unsigned zerofill DEFAULT NULL,
  `4` int(10) unsigned zerofill DEFAULT NULL,
  `5` int(10) unsigned zerofill DEFAULT NULL,
  `6` int(10) unsigned zerofill DEFAULT NULL,
  `7` int(10) unsigned zerofill DEFAULT NULL,
  `8` int(10) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`cod`),
  KEY `1` (`1`),
  KEY `2` (`2`),
  KEY `3` (`3`),
  KEY `4` (`4`),
  KEY `5` (`5`),
  KEY `6` (`6`),
  KEY `7` (`7`),
  KEY `8` (`8`),
  CONSTRAINT `tb_personagem_equipamentos_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_10` FOREIGN KEY (`4`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_11` FOREIGN KEY (`5`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_12` FOREIGN KEY (`6`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_13` FOREIGN KEY (`7`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_14` FOREIGN KEY (`8`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_2` FOREIGN KEY (`1`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_3` FOREIGN KEY (`2`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_4` FOREIGN KEY (`3`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_5` FOREIGN KEY (`4`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_6` FOREIGN KEY (`5`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_7` FOREIGN KEY (`6`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_8` FOREIGN KEY (`7`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_equipamentos_ibfk_9` FOREIGN KEY (`3`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagem_habilidade definition

CREATE TABLE `tb_personagem_habilidade` (
  `personagem_habilidade_id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `personagem_id` int(6) unsigned zerofill NOT NULL,
  `habilidade_id` int(4) unsigned zerofill NOT NULL,
  `img` int unsigned NOT NULL,
  `nome` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`personagem_habilidade_id`),
  UNIQUE KEY `personagem_id` (`personagem_id`,`habilidade_id`),
  KEY `habilidade_id` (`habilidade_id`),
  CONSTRAINT `tb_personagem_habilidade_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_habilidade_ibfk_2` FOREIGN KEY (`habilidade_id`) REFERENCES `tb_habilidades` (`habilidade_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagem_habilidade_pontos definition

CREATE TABLE `tb_personagem_habilidade_pontos` (
  `personagem_id` int(6) unsigned zerofill NOT NULL,
  `habilidade_id` int(4) unsigned zerofill NOT NULL,
  `pontos` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`personagem_id`,`habilidade_id`),
  KEY `habilidade_id` (`habilidade_id`),
  CONSTRAINT `tb_personagem_habilidade_pontos_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_habilidade_pontos_ibfk_2` FOREIGN KEY (`habilidade_id`) REFERENCES `tb_habilidades` (`habilidade_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagem_titulo definition

CREATE TABLE `tb_personagem_titulo` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `titulo` int(5) unsigned zerofill NOT NULL,
  UNIQUE KEY `cod` (`cod`,`titulo`),
  KEY `titulo` (`titulo`),
  CONSTRAINT `tb_personagem_titulo_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_personagem_titulo_ibfk_2` FOREIGN KEY (`titulo`) REFERENCES `tb_titulos` (`cod_titulo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagens definition

CREATE TABLE `tb_personagens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int(4) unsigned zerofill NOT NULL,
  `skin_c` int unsigned NOT NULL DEFAULT '0',
  `skin_r` int unsigned NOT NULL DEFAULT '0',
  `hp` int unsigned NOT NULL DEFAULT '5300',
  `hp_max` int unsigned NOT NULL DEFAULT '5300',
  `mp` int unsigned NOT NULL DEFAULT '100',
  `mp_max` int unsigned NOT NULL DEFAULT '100',
  `xp` int NOT NULL DEFAULT '0',
  `xp_max` int NOT NULL DEFAULT '150',
  `fama_ameaca` int unsigned NOT NULL DEFAULT '0',
  `lvl` int NOT NULL DEFAULT '1',
  `nome` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `titulo` int(5) unsigned zerofill DEFAULT NULL,
  `classe` int NOT NULL DEFAULT '0',
  `classe_treino` double NOT NULL DEFAULT '0',
  `classe_aprender` int NOT NULL DEFAULT '0',
  `classe_score` int NOT NULL DEFAULT '0',
  `profissao` int NOT NULL DEFAULT '0',
  `profissao_lvl` int NOT NULL DEFAULT '0',
  `profissao_xp` double NOT NULL DEFAULT '0',
  `profissao_xp_max` double NOT NULL DEFAULT '0',
  `akuma` int(6) unsigned zerofill DEFAULT NULL,
  `atk` int NOT NULL DEFAULT '1',
  `def` int NOT NULL DEFAULT '1',
  `agl` int NOT NULL DEFAULT '1',
  `res` int NOT NULL DEFAULT '1',
  `pre` int NOT NULL DEFAULT '1',
  `dex` int NOT NULL DEFAULT '1',
  `con` int NOT NULL DEFAULT '1',
  `vit` int NOT NULL DEFAULT '1',
  `pts` int NOT NULL DEFAULT '69',
  `cod_acessorio` int NOT NULL DEFAULT '0',
  `respawn` double unsigned NOT NULL DEFAULT '0',
  `respawn_tipo` int NOT NULL DEFAULT '0',
  `haki_lvl` int NOT NULL DEFAULT '1',
  `haki_xp` int NOT NULL DEFAULT '0',
  `haki_xp_max` int NOT NULL DEFAULT '1000',
  `haki_pts` int NOT NULL DEFAULT '1',
  `haki_esq` int NOT NULL DEFAULT '0',
  `haki_blo` int NOT NULL DEFAULT '0',
  `haki_cri` int NOT NULL DEFAULT '0',
  `haki_hdr` int unsigned NOT NULL DEFAULT '0',
  `tatic_a` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `tatic_d` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `tatic_p` varchar(5) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '0',
  `ativo` tinyint unsigned NOT NULL DEFAULT '1',
  `haki_count_dias_treino` int unsigned NOT NULL DEFAULT '0',
  `haki_ultimo_dia_treino` date DEFAULT NULL,
  `preso` int unsigned DEFAULT '0',
  `excelencia_lvl` int unsigned NOT NULL DEFAULT '0',
  `excelencia_xp` int unsigned NOT NULL DEFAULT '0',
  `excelencia_xp_max` int unsigned NOT NULL DEFAULT '97500',
  `sexo` int unsigned NOT NULL DEFAULT '0',
  `fa_premio` int unsigned DEFAULT '0',
  `time_coliseu` tinyint DEFAULT '0',
  `selos_xp` int unsigned DEFAULT '0',
  `temporario` tinyint DEFAULT '0',
  `borda` int unsigned DEFAULT '0',
  `time_casual` tinyint NOT NULL DEFAULT '0',
  `time_competitivo` tinyint NOT NULL DEFAULT '0',
  `maestria` int unsigned DEFAULT '0',
  `haki_lvl_ultima_era` int unsigned DEFAULT NULL,
  `is_arena` tinyint NOT NULL DEFAULT '0',
  `arena_selected_build_id` int DEFAULT NULL,
  PRIMARY KEY (`cod`),
  UNIQUE KEY `cod` (`nome`),
  KEY `id` (`id`),
  KEY `titulo` (`titulo`),
  KEY `akuma` (`akuma`),
  KEY `tb_personagens_tb_arena_build_id_fk` (`arena_selected_build_id`),
  CONSTRAINT `tb_personagens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_personagens_ibfk_2` FOREIGN KEY (`titulo`) REFERENCES `tb_titulos` (`cod_titulo`) ON DELETE SET NULL ON UPDATE SET NULL,
  CONSTRAINT `tb_personagens_ibfk_3` FOREIGN KEY (`akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_personagens_tb_arena_build_id_fk` FOREIGN KEY (`arena_selected_build_id`) REFERENCES `tb_arena_build` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3886 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_personagens_skil definition

CREATE TABLE `tb_personagens_skil` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(4) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL,
  `nome` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `descricao` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `icon` int unsigned NOT NULL DEFAULT '1',
  `lvl` int NOT NULL DEFAULT '0',
  `xp` int NOT NULL DEFAULT '0',
  `xp_max` int NOT NULL DEFAULT '100',
  `effect` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'Atingir fisicamente',
  `special_effect` int unsigned DEFAULT NULL,
  `special_target` int unsigned DEFAULT NULL,
  `special_apply_type` int unsigned DEFAULT NULL,
  `editado` tinyint DEFAULT '0',
  KEY `cod` (`cod`),
  CONSTRAINT `tb_personagens_skil_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_pve definition

CREATE TABLE `tb_pve` (
  `id` int(6) unsigned zerofill NOT NULL,
  `zona` int NOT NULL,
  `quant` int NOT NULL,
  UNIQUE KEY `id` (`id`,`zona`),
  CONSTRAINT `tb_pve_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_pvp_imune definition

CREATE TABLE `tb_pvp_imune` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `adversario_id` int(10) unsigned zerofill NOT NULL,
  `horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_pvp_imune_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_pvp_imune_tb_usuarios_adversario_id_fk` (`adversario_id`),
  CONSTRAINT `tb_pvp_imune_tb_usuarios_adversario_id_fk` FOREIGN KEY (`adversario_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_pvp_imune_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_realizacoes_concluidas definition

CREATE TABLE `tb_realizacoes_concluidas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_realizacao` int(5) unsigned zerofill NOT NULL,
  `tipo` int NOT NULL DEFAULT '0',
  `personagem` int(6) unsigned zerofill DEFAULT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`,`cod_realizacao`,`tipo`,`personagem`),
  KEY `cod_realizacao` (`cod_realizacao`),
  KEY `personagem` (`personagem`),
  CONSTRAINT `tb_realizacoes_concluidas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_realizacoes_concluidas_ibfk_2` FOREIGN KEY (`cod_realizacao`) REFERENCES `tb_realizacoes` (`cod_realizacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_realizacoes_concluidas_ibfk_3` FOREIGN KEY (`personagem`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_recompensa_recebida_era definition

CREATE TABLE `tb_recompensa_recebida_era` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_recompensa_recebida_era_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_recompensa_recebida_era_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_recompensa_recebida_grandes_poderes definition

CREATE TABLE `tb_recompensa_recebida_grandes_poderes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_recompensa_recebida_grandes_poderes_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_recompensa_recebida_grandes_poderes_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_recompensa_recebida_haki definition

CREATE TABLE `tb_recompensa_recebida_haki` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_recompensa_recebida_haki_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_recompensa_recebida_haki_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_resets definition

CREATE TABLE `tb_resets` (
  `tipo` int NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  KEY `cod` (`cod`),
  CONSTRAINT `tb_resets_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_rotas definition

CREATE TABLE `tb_rotas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int NOT NULL,
  `y` int NOT NULL,
  `indice` int NOT NULL,
  `momento` double NOT NULL,
  UNIQUE KEY `id` (`id`,`x`,`y`,`indice`),
  CONSTRAINT `tb_rotas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_torneio definition

CREATE TABLE `tb_torneio` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `status` int NOT NULL,
  `inicio` timestamp NOT NULL,
  `limite_inscricao` timestamp NOT NULL,
  `limite_conclusao` timestamp NOT NULL,
  `vencedor` int(6) unsigned zerofill DEFAULT NULL,
  `coordenadas` json NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_torneio_tb_usuarios_FK` (`vencedor`),
  CONSTRAINT `tb_torneio_tb_usuarios_FK` FOREIGN KEY (`vencedor`) REFERENCES `tb_usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_torneio_chave definition

CREATE TABLE `tb_torneio_chave` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tripulacao_1_id` int(6) unsigned zerofill DEFAULT NULL,
  `tripulacao_1_pronto` tinyint(1) DEFAULT '0',
  `tripulacao_2_id` int(6) unsigned zerofill DEFAULT NULL,
  `tripulacao_2_pronto` tinyint(1) DEFAULT '0',
  `limite_inicio` timestamp NULL DEFAULT NULL,
  `limite_fim` timestamp NULL DEFAULT NULL,
  `vencedor` bigint DEFAULT NULL,
  `finalizada` tinyint(1) DEFAULT '0',
  `proxima_chave` bigint DEFAULT NULL,
  `torneio_id` bigint NOT NULL,
  `placar_1` int DEFAULT NULL,
  `placar_2` int DEFAULT NULL,
  `em_andamento` tinyint(1) DEFAULT NULL,
  `combate_id` bigint(20) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_torneio_chave_tb_torneio_chave_FK` (`proxima_chave`),
  KEY `tb_torneio_chave_tb_usuarios_FK` (`tripulacao_1_id`),
  KEY `tb_torneio_chave_tb_usuarios_FK_1` (`tripulacao_2_id`),
  KEY `tb_torneio_chave_tb_torneio_FK` (`torneio_id`),
  KEY `tb_torneio_chave_tb_combate_FK` (`combate_id`),
  CONSTRAINT `tb_torneio_chave_tb_combate_FK` FOREIGN KEY (`combate_id`) REFERENCES `tb_combate` (`combate`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_torneio_chave_tb_torneio_chave_FK` FOREIGN KEY (`proxima_chave`) REFERENCES `tb_torneio_chave` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `tb_torneio_chave_tb_torneio_FK` FOREIGN KEY (`torneio_id`) REFERENCES `tb_torneio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_torneio_chave_tb_usuarios_FK` FOREIGN KEY (`tripulacao_1_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `tb_torneio_chave_tb_usuarios_FK_1` FOREIGN KEY (`tripulacao_2_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1597 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_torneio_inscricao definition

CREATE TABLE `tb_torneio_inscricao` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `torneio_id` bigint NOT NULL,
  `tripulacao_id` int(6) unsigned zerofill NOT NULL,
  `data_inscricao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_torneio_inscricao_tb_torneio_FK` (`torneio_id`),
  KEY `tb_torneio_inscricao_tb_usuarios_FK` (`tripulacao_id`),
  CONSTRAINT `tb_torneio_inscricao_tb_torneio_FK` FOREIGN KEY (`torneio_id`) REFERENCES `tb_torneio` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_torneio_inscricao_tb_usuarios_FK` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_animacoes_skills definition

CREATE TABLE `tb_tripulacao_animacoes_skills` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `effect` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `quant` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_animacoes_skills_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_tripulacao_animacoes_skills_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1506 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_bordas definition

CREATE TABLE `tb_tripulacao_bordas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `borda` int unsigned NOT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_bordas_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_tripulacao_bordas_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=363 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_buff definition

CREATE TABLE `tb_tripulacao_buff` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `buff_id` int NOT NULL,
  `expiracao` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_buff_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_tripulacao_buff_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=205 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_formacao definition

CREATE TABLE `tb_tripulacao_formacao` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill NOT NULL,
  `formacao_id` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `personagem_id` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_formacao_tb_usuarios_id_fk` (`tripulacao_id`),
  KEY `tb_tripulacao_formacao_tb_personagens_cod_fk` (`personagem_id`),
  CONSTRAINT `tb_tripulacao_formacao_tb_personagens_cod_fk` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_tripulacao_formacao_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=450 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_skin_navio definition

CREATE TABLE `tb_tripulacao_skin_navio` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conta_id` int(10) unsigned zerofill DEFAULT NULL,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `skin_id` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_skin_navio_tb_conta_conta_id_fk` (`conta_id`),
  KEY `tb_tripulacao_skin_navio_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_tripulacao_skin_navio_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_tripulacao_skin_navio_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_tripulacao_skins definition

CREATE TABLE `tb_tripulacao_skins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tripulacao_id` int(10) unsigned zerofill DEFAULT NULL,
  `img` int unsigned NOT NULL,
  `skin` int unsigned NOT NULL,
  `data_compra` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `conta_id` int(10) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_tripulacao_skins_tb_conta_conta_id_fk` (`conta_id`),
  KEY `tb_tripulacao_skins_tb_usuarios_id_fk` (`tripulacao_id`),
  CONSTRAINT `tb_tripulacao_skins_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_tripulacao_skins_tb_usuarios_id_fk` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=1300 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_usuario_itens definition

CREATE TABLE `tb_usuario_itens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int NOT NULL,
  `quant` int NOT NULL DEFAULT '1',
  `novo` tinyint DEFAULT '1',
  `okok` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`okok`),
  KEY `id` (`id`,`cod_item`,`tipo_item`),
  CONSTRAINT `tb_usuario_itens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=118553 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_usuario_navio definition

CREATE TABLE `tb_usuario_navio` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_navio` int(4) unsigned zerofill DEFAULT NULL,
  `cod_casco` int unsigned NOT NULL DEFAULT '0',
  `cod_leme` int NOT NULL DEFAULT '0',
  `cod_velas` int NOT NULL DEFAULT '0',
  `cod_canhao` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `hp` int NOT NULL DEFAULT '100',
  `hp_max` int NOT NULL DEFAULT '100',
  `lvl` int NOT NULL DEFAULT '1',
  `reparo` double NOT NULL DEFAULT '0',
  `reparo_tipo` int DEFAULT NULL,
  `reparo_quant` int DEFAULT NULL,
  `xp` int NOT NULL DEFAULT '0',
  `xp_max` int NOT NULL DEFAULT '250',
  `capacidade_inventario` int DEFAULT '55',
  `ultima_cura` bigint DEFAULT '0',
  `ultimo_disparo` bigint DEFAULT '0',
  `hp_teste` int NOT NULL DEFAULT '100',
  `ultimo_disparo_sofrido` bigint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cod_navio` (`cod_navio`),
  CONSTRAINT `tb_usuario_navio_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_usuario_navio_ibfk_2` FOREIGN KEY (`cod_navio`) REFERENCES `tb_navio` (`cod_navio`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_usuarios definition

CREATE TABLE `tb_usuarios` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `conta_id` int(11) unsigned zerofill NOT NULL,
  `tripulacao` varchar(15) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_logon` double DEFAULT NULL,
  `faccao` int NOT NULL,
  `reputacao` int NOT NULL DEFAULT '0',
  `reputacao_mensal` int unsigned NOT NULL DEFAULT '0',
  `coord_x_navio` int DEFAULT NULL,
  `coord_y_navio` int DEFAULT NULL,
  `res_x` int NOT NULL DEFAULT '0',
  `res_y` int NOT NULL DEFAULT '0',
  `cod_personagem` int(4) unsigned zerofill DEFAULT NULL,
  `berries` bigint unsigned NOT NULL DEFAULT '5000',
  `recrutando` double NOT NULL DEFAULT '0',
  `mergulho` double DEFAULT '0',
  `mergulho_cod` int(6) unsigned zerofill DEFAULT NULL,
  `expedicao` double DEFAULT NULL,
  `expedicao_cod` int(6) unsigned zerofill DEFAULT NULL,
  `desenho` double DEFAULT NULL,
  `desenho_cod` int(6) unsigned zerofill DEFAULT NULL,
  `mining` double NOT NULL DEFAULT '0',
  `mining_cod` int(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `madeira` double NOT NULL DEFAULT '0',
  `madeira_cod` int(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `adm` int NOT NULL DEFAULT '0',
  `M` int NOT NULL DEFAULT '0',
  `vitorias` int NOT NULL DEFAULT '0',
  `derrotas` int NOT NULL DEFAULT '0',
  `fugas` int NOT NULL DEFAULT '0',
  `bandeira` varchar(36) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL DEFAULT '010113046758010128123542010115204020',
  `kai` int NOT NULL DEFAULT '0',
  `ip` varchar(30) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `realizacoes` int NOT NULL DEFAULT '0',
  `disposicao` int NOT NULL DEFAULT '10000',
  `isca` int NOT NULL DEFAULT '0',
  `inativo` timestamp NULL DEFAULT NULL,
  `progress` int unsigned NOT NULL DEFAULT '0',
  `advertencia` int NOT NULL DEFAULT '0',
  `missao_caca` int unsigned DEFAULT NULL,
  `missao_caca_progress` int unsigned DEFAULT NULL,
  `missao_rotation` mediumtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `karma_bom` int unsigned NOT NULL DEFAULT '0',
  `karma_mau` int unsigned NOT NULL DEFAULT '0',
  `tempo_missao` int unsigned DEFAULT '0',
  `direcao_navio` int unsigned DEFAULT '3',
  `skin_navio` int unsigned DEFAULT '0',
  `coup_de_burst_usado` tinyint DEFAULT '0',
  `ultima_pagina` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `presente_diario_obtido` tinyint DEFAULT '0',
  `presente_diario_count` int unsigned DEFAULT '0',
  `moedas_evento` int unsigned DEFAULT '0',
  `skin_tabuleiro_navio` int unsigned DEFAULT '0',
  `credito_skin` int unsigned DEFAULT '0',
  `credito_skin_navio` int unsigned DEFAULT '0',
  `campanha_impel_down` int unsigned DEFAULT NULL,
  `haki_xp` int unsigned DEFAULT '0',
  `fa_premio_unico_1` tinyint DEFAULT '0',
  `coliseu_points` int unsigned DEFAULT '0',
  `coliseu_premio` int unsigned DEFAULT '0',
  `coliseu_points_edicao` int unsigned DEFAULT '0',
  `navegacao_automatica` tinyint DEFAULT '0',
  `battle_points` int unsigned DEFAULT '0',
  `battle_lvl` int unsigned DEFAULT '1',
  `batalhas_criancas` int unsigned DEFAULT '0',
  `missoes_automaticas` tinyint NOT NULL DEFAULT '0',
  `x` int unsigned NOT NULL DEFAULT '0',
  `y` int unsigned NOT NULL DEFAULT '0',
  `mar_visivel` tinyint unsigned NOT NULL DEFAULT '0',
  `navegacao_destino` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `navegacao_inicio` double DEFAULT NULL,
  `navegacao_fim` double DEFAULT NULL,
  `free_reset_atributos` int unsigned DEFAULT '5',
  `campanha_enies_lobby` int unsigned DEFAULT NULL,
  `coliseu_points_edicao_passada` int NOT NULL DEFAULT '0',
  `arena_lvl` int NOT NULL DEFAULT '1',
  `arena_xp` int NOT NULL DEFAULT '0',
  `arena_xp_max` int NOT NULL DEFAULT '100',
  `arena_coins` int NOT NULL DEFAULT '0',
  `selected_team_id` int DEFAULT NULL,
  `arena_xp_bottle` int NOT NULL DEFAULT '0',
  `arena_chests` int NOT NULL DEFAULT '0',
  `arena_medals` int NOT NULL DEFAULT '0',
  `arena_chest_opening` timestamp NULL DEFAULT NULL,
  `arena_gift_last_receive` timestamp NULL DEFAULT NULL,
  `arena_gift_id_request` int DEFAULT NULL,
  `arena_gift_received` int NOT NULL DEFAULT '0',
  `iscas_usadas` int NOT NULL DEFAULT '0',
  `protecao_pvp` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tripulacao` (`tripulacao`),
  KEY `conta_id` (`conta_id`),
  KEY `tb_usuarios_x_y_index` (`x`,`y`),
  KEY `tb_usuarios_tb_arena_team_id_fk` (`selected_team_id`),
  CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_usuarios_tb_arena_team_id_fk` FOREIGN KEY (`selected_team_id`) REFERENCES `tb_arena_team` (`id`) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_vip definition

CREATE TABLE `tb_vip` (
  `id` int(6) unsigned zerofill NOT NULL,
  `luneta` int NOT NULL DEFAULT '0',
  `luneta_duracao` double NOT NULL DEFAULT '0',
  `sense` int NOT NULL DEFAULT '0',
  `sense_duracao` double NOT NULL DEFAULT '0',
  `tatic` int NOT NULL DEFAULT '0',
  `tatic_duracao` double NOT NULL DEFAULT '0',
  `reset_personagem` int unsigned NOT NULL DEFAULT '0',
  `reset_nome` int unsigned NOT NULL DEFAULT '0',
  `conhecimento` int DEFAULT '0',
  `conhecimento_duracao` double DEFAULT '0',
  `coup_de_burst` int DEFAULT '0',
  `coup_de_burst_duracao` double DEFAULT '0',
  `formacoes` int DEFAULT '0',
  `formacoes_duracao` double DEFAULT '0',
  PRIMARY KEY (`id`),
  CONSTRAINT `tb_vip_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


-- sugoi.tb_wanted_log definition

CREATE TABLE `tb_wanted_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `vencedor_cod` int unsigned NOT NULL,
  `perdedor_cod` int unsigned NOT NULL,
  `fa_ganha` int unsigned NOT NULL DEFAULT '0',
  `fa_perdida` int unsigned NOT NULL DEFAULT '0',
  `vencedor_lvl` int unsigned DEFAULT NULL,
  `perdedor_lvl` int unsigned DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fa_anterior_vencedor` int unsigned DEFAULT NULL,
  `fa_anterior_perdedor` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tb_wanted_log_tb_personagens_vencedor_cod_fk` (`vencedor_cod`),
  KEY `tb_wanted_log_tb_personagens_perdedor_cod_fk` (`perdedor_cod`),
  CONSTRAINT `tb_wanted_log_tb_personagens_perdedor_cod_fk` FOREIGN KEY (`perdedor_cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tb_wanted_log_tb_personagens_vencedor_cod_fk` FOREIGN KEY (`vencedor_cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=474402 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;


ALTER TABLE tb_usuarios ADD ultimo_reset DATE NULL;

ALTER TABLE tb_vip_planos ADD stripe_checkout_url_brl varchar(255) NULL;
ALTER TABLE tb_vip_planos ADD stripe_checkout_url_usd varchar(255) NULL;
ALTER TABLE tb_vip_planos ADD stripe_checkout_url_eur varchar(255) NULL;

ALTER TABLE tb_missoes_r_dia ADD dia DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL;
ALTER TABLE tb_missoes_caca_diario ADD inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;
ALTER TABLE tb_missoes_concluidas_dia ADD dia TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL;

-- NOVO SISTEMA DE HABILIDADES:
ALTER TABLE tb_combate_personagens ADD efeitos json NULL;
ALTER TABLE tb_combate_npc ADD efeitos json NULL;
ALTER TABLE tb_combate_personagens_bot ADD efeitos json NULL;
DELETE FROM tb_personagens_skil WHERE cod_skil > 14 or cod_skil = 1 or tipo <> 1;
ALTER TABLE tb_personagens_skil DROP COLUMN tipo;
ALTER TABLE tb_personagens_skil CHANGE effect animacao varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT 'Atingir fisicamente' NULL;
ALTER TABLE tb_personagens_skil DROP COLUMN special_effect;
ALTER TABLE tb_personagens_skil DROP COLUMN special_target;
ALTER TABLE tb_personagens_skil DROP COLUMN special_apply_type;
ALTER TABLE tb_personagens_skil DROP COLUMN lvl;
ALTER TABLE tb_personagens_skil DROP COLUMN xp;
ALTER TABLE tb_personagens_skil DROP COLUMN xp_max;
ALTER TABLE tb_personagens_skil MODIFY COLUMN cod_skil int(4) unsigned NOT NULL;
ALTER TABLE tb_personagens_skil CHANGE icon icone int unsigned DEFAULT 1 NOT NULL;
ALTER TABLE tb_personagens_skil CHANGE cod cod_pers int(6) unsigned zerofill NOT NULL;
CREATE UNIQUE INDEX tb_personagens_skil_cod_IDX USING BTREE ON tb_personagens_skil (cod,cod_skil);
ALTER TABLE tb_combate_skil_espera ADD CONSTRAINT tb_combate_skil_espera_unique UNIQUE KEY (cod,cod_skil,id);
ALTER TABLE tb_combate_skil_espera DROP PRIMARY KEY;
ALTER TABLE tb_combate_skil_espera DROP COLUMN tipo;
ALTER TABLE tb_combate_skil_espera ADD CONSTRAINT tb_combate_skil_espera_pk PRIMARY KEY (cod,cod_skil);


SET FOREIGN_KEY_CHECKS=1;
