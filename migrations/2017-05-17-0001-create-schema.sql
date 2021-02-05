-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 22-Maio-2017 às 18:10
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sugoigame3`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_afilhados`
--

CREATE TABLE IF NOT EXISTS `tb_afilhados` (
  `id` int(11) unsigned zerofill NOT NULL,
  `afilhado` int(11) unsigned zerofill NOT NULL,
  PRIMARY KEY (`afilhado`),
  UNIQUE KEY `id` (`id`,`afilhado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_afilhados_recrutados`
--

CREATE TABLE IF NOT EXISTS `tb_afilhados_recrutados` (
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_akuma`
--

CREATE TABLE IF NOT EXISTS `tb_akuma` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_akuma` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `tipo` int(1) NOT NULL,
  `img` int(4) NOT NULL DEFAULT '100',
  `categoria` int(2) NOT NULL,
  PRIMARY KEY (`cod_akuma`,`cod`),
  UNIQUE KEY `nome` (`nome`),
  UNIQUE KEY `cod_akuma` (`cod_akuma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_akuma_skil_atk`
--

CREATE TABLE IF NOT EXISTS `tb_akuma_skil_atk` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `consumo` int(4) NOT NULL DEFAULT '0',
  `lvl` int(2) NOT NULL,
  `dano` int(6) NOT NULL,
  `alcance` int(2) NOT NULL DEFAULT '1',
  `area` int(2) NOT NULL DEFAULT '1',
  `espera` int(1) NOT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_akuma_skil_buff`
--

CREATE TABLE IF NOT EXISTS `tb_akuma_skil_buff` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lvl` int(2) NOT NULL,
  `consumo` int(4) NOT NULL DEFAULT '0',
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_qnt` int(4) NOT NULL,
  `duracao` int(1) NOT NULL,
  `alcance` int(2) NOT NULL DEFAULT '0',
  `area` int(2) NOT NULL DEFAULT '1',
  `espera` int(1) NOT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_akuma_skil_passiva`
--

CREATE TABLE IF NOT EXISTS `tb_akuma_skil_passiva` (
  `cod_akuma` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `lvl` int(2) NOT NULL,
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_qnt` int(4) NOT NULL,
  PRIMARY KEY (`cod_skil`),
  KEY `cod_akuma` (`cod_akuma`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca`
--

CREATE TABLE IF NOT EXISTS `tb_alianca` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `img` int(4) NOT NULL,
  `mural` text NOT NULL,
  `xp` int(6) NOT NULL DEFAULT '0',
  `xp_max` int(6) NOT NULL DEFAULT '500',
  `lvl` int(11) NOT NULL DEFAULT '1',
  `score` int(11) NOT NULL DEFAULT '0',
  `vitorias` int(4) NOT NULL DEFAULT '0',
  `derrotas` int(4) NOT NULL DEFAULT '0',
  `banco` bigint(20) NOT NULL DEFAULT '0',
  `0` varchar(12) NOT NULL DEFAULT '111111111111',
  `1` varchar(12) NOT NULL DEFAULT '111000001100',
  `2` varchar(12) NOT NULL DEFAULT '110000001000',
  `3` varchar(12) NOT NULL DEFAULT '100000001000',
  `4` varchar(12) NOT NULL DEFAULT '000000000000',
  PRIMARY KEY (`cod_alianca`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=230 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_aliados`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_aliados` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_aliado` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`cod_alianca`,`cod_aliado`),
  KEY `cod_aliado` (`cod_aliado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_banco`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_banco` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int(2) NOT NULL,
  `quant` int(4) NOT NULL,
  KEY `cod_alianca` (`cod_alianca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_banco_log`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_banco_log` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario` varchar(100) NOT NULL,
  `item` varchar(100) NOT NULL,
  `tipo` int(1) NOT NULL,
  KEY `cod_alianca` (`cod_alianca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_convite`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_convite` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `convidado` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`cod_alianca`,`convidado`),
  KEY `convidado` (`convidado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_guerra`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_guerra` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `cod_inimigo` int(6) unsigned zerofill NOT NULL,
  `vitoria` int(3) NOT NULL,
  `pts` int(3) NOT NULL DEFAULT '0',
  `fim` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_guerra_ajuda`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_guerra_ajuda` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int(6) NOT NULL,
  PRIMARY KEY (`cod_alianca`,`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_guerra_pedidos`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_guerra_pedidos` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `convidado` int(6) unsigned zerofill NOT NULL,
  `tipo` int(3) NOT NULL,
  PRIMARY KEY (`cod_alianca`,`convidado`),
  KEY `convidado` (`convidado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_membros`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_membros` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `autoridade` int(1) NOT NULL DEFAULT '4',
  `cooperacao` int(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `cod_alianca` (`cod_alianca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_missoes`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_missoes` (
  `cod_alianca` int(6) unsigned zerofill NOT NULL,
  `quant` int(6) NOT NULL DEFAULT '0',
  `fim` int(6) NOT NULL,
  PRIMARY KEY (`cod_alianca`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_alianca_shop`
--

CREATE TABLE IF NOT EXISTS `tb_alianca_shop` (
  `cod` int(6) unsigned NOT NULL,
  `tipo` int(2) NOT NULL,
  `lvl` int(2) NOT NULL,
  `preco` int(6) NOT NULL,
  `faccao` int(1) NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_amigos`
--

CREATE TABLE IF NOT EXISTS `tb_amigos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `amigo` int(6) unsigned zerofill NOT NULL,
  `capitao` varchar(100) NOT NULL,
  PRIMARY KEY (`id`,`amigo`),
  KEY `amigo` (`amigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ban`
--

CREATE TABLE IF NOT EXISTS `tb_ban` (
  `ip` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_chat`
--

CREATE TABLE IF NOT EXISTS `tb_chat` (
  `cod` double NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `adm` int(1) NOT NULL DEFAULT '0',
  `msg` text NOT NULL,
  `horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `canal` varchar(1) NOT NULL,
  `cod_canal` int(2) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_chat_bloqueio`
--

CREATE TABLE IF NOT EXISTS `tb_chat_bloqueio` (
  `id` int(6) unsigned zerofill NOT NULL,
  `M` int(6) unsigned zerofill NOT NULL,
  `tipo` int(1) NOT NULL,
  `tempo` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_coliseu_cp`
--

CREATE TABLE IF NOT EXISTS `tb_coliseu_cp` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cp` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_coliseu_fila`
--

CREATE TABLE IF NOT EXISTS `tb_coliseu_fila` (
  `id` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_coliseu_itens`
--

CREATE TABLE IF NOT EXISTS `tb_coliseu_itens` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `tipo` int(2) NOT NULL,
  `preco` int(2) unsigned NOT NULL,
  UNIQUE KEY `cod` (`cod`,`tipo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_coliseu_ranking`
--

CREATE TABLE IF NOT EXISTS `tb_coliseu_ranking` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cp` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate`
--

CREATE TABLE IF NOT EXISTS `tb_combate` (
  `combate` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_1` int(6) unsigned zerofill NOT NULL,
  `id_2` int(6) unsigned zerofill NOT NULL,
  `vez` int(1) NOT NULL,
  `vez_tempo` double NOT NULL,
  `passe_1` int(1) NOT NULL,
  `passe_2` int(1) NOT NULL,
  `move_1` int(1) NOT NULL,
  `move_2` int(1) NOT NULL,
  `relatorio` text NOT NULL,
  `saiu_1` int(1) NOT NULL DEFAULT '0',
  `saiu_2` int(1) NOT NULL DEFAULT '0',
  `recop_1` double NOT NULL DEFAULT '0',
  `recop_2` double NOT NULL DEFAULT '0',
  `tipo` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`combate`),
  UNIQUE KEY `id_1` (`id_1`,`id_2`),
  KEY `id_2` (`id_2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_buff`
--

CREATE TABLE IF NOT EXISTS `tb_combate_buff` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_buff` int(4) unsigned zerofill NOT NULL,
  `atr` int(1) NOT NULL,
  `efeito` int(3) NOT NULL,
  `espera` int(2) NOT NULL,
  PRIMARY KEY (`cod`,`cod_buff`,`atr`,`efeito`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_desafio`
--

CREATE TABLE IF NOT EXISTS `tb_combate_desafio` (
  `desafiante` int(6) unsigned zerofill NOT NULL,
  `desafiante_nome` varchar(100) NOT NULL,
  `desafiado` int(6) unsigned zerofill NOT NULL,
  UNIQUE KEY `desafiante` (`desafiante`,`desafiado`),
  KEY `desafiado` (`desafiado`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_log`
--

CREATE TABLE IF NOT EXISTS `tb_combate_log` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `id_1` int(6) unsigned zerofill NOT NULL,
  `id_2` int(6) unsigned zerofill NOT NULL,
  `tipo` int(2) NOT NULL,
  `horario` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `pos_1` varchar(9) NOT NULL,
  `pos_2` varchar(9) NOT NULL,
  `ip_1` varchar(30) NOT NULL,
  `ip_2` varchar(30) NOT NULL,
  KEY `id_1` (`id_1`),
  KEY `id_2` (`id_2`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_npc`
--

CREATE TABLE IF NOT EXISTS `tb_combate_npc` (
  `id` int(6) unsigned zerofill NOT NULL,
  `img_npc` int(3) NOT NULL,
  `nome_npc` varchar(100) NOT NULL DEFAULT 'Rei dos Mares',
  `hp_npc` int(6) NOT NULL,
  `hp_max_npc` int(11) NOT NULL,
  `mp_npc` int(11) NOT NULL,
  `mp_max_npc` int(11) NOT NULL,
  `atk_npc` int(4) NOT NULL,
  `def_npc` int(4) NOT NULL,
  `agl_npc` int(4) NOT NULL,
  `res_npc` int(4) NOT NULL,
  `pre_npc` int(4) NOT NULL,
  `dex_npc` int(4) NOT NULL,
  `con_npc` int(4) NOT NULL,
  `dano` int(6) NOT NULL DEFAULT '0',
  `armadura` int(6) NOT NULL DEFAULT '0',
  `move` int(1) NOT NULL DEFAULT '5',
  `relatorio` text NOT NULL,
  `buff_npc` text NOT NULL,
  `zona` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_personagens`
--

CREATE TABLE IF NOT EXISTS `tb_combate_personagens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `hp` int(4) NOT NULL,
  `hp_max` int(4) NOT NULL,
  `mp` int(4) NOT NULL,
  `mp_max` int(4) NOT NULL,
  `atk` int(4) NOT NULL,
  `def` int(4) NOT NULL,
  `agl` int(4) NOT NULL,
  `res` int(4) NOT NULL,
  `pre` int(4) NOT NULL,
  `dex` int(4) NOT NULL,
  `con` int(4) NOT NULL,
  `vit` int(4) NOT NULL,
  `quadro_x` int(3) NOT NULL,
  `quadro_y` int(3) NOT NULL,
  `haki_esq` int(3) NOT NULL,
  `haki_cri` int(3) NOT NULL,
  `haki_blo` int(3) NOT NULL,
  PRIMARY KEY (`cod`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combate_skil_espera`
--

CREATE TABLE IF NOT EXISTS `tb_combate_skil_espera` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(4) unsigned zerofill NOT NULL,
  `tipo` int(1) NOT NULL,
  `espera` int(2) NOT NULL,
  PRIMARY KEY (`cod`,`cod_skil`,`tipo`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes` (
  `1` int(6) NOT NULL,
  `1_t` int(3) NOT NULL,
  `2` int(6) NOT NULL,
  `2_t` int(3) NOT NULL,
  `3` int(6) NOT NULL,
  `3_t` int(3) NOT NULL,
  `4` int(6) NOT NULL,
  `4_t` int(3) NOT NULL,
  `5` int(6) NOT NULL,
  `5_t` int(3) NOT NULL,
  `6` int(6) NOT NULL,
  `6_t` int(3) NOT NULL,
  `7` int(6) NOT NULL,
  `7_t` int(3) NOT NULL,
  `8` int(6) NOT NULL,
  `8_t` int(3) NOT NULL,
  `lvl` int(3) NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_artesao`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_artesao` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int(1) NOT NULL DEFAULT '0',
  `1` int(6) NOT NULL DEFAULT '0',
  `1_t` int(3) NOT NULL DEFAULT '0',
  `1_q` int(3) NOT NULL DEFAULT '0',
  `2` int(6) NOT NULL DEFAULT '0',
  `2_t` int(3) NOT NULL DEFAULT '0',
  `2_q` int(3) NOT NULL DEFAULT '0',
  `3` int(6) NOT NULL DEFAULT '0',
  `3_t` int(3) NOT NULL DEFAULT '0',
  `3_q` int(3) NOT NULL DEFAULT '0',
  `4` int(6) NOT NULL DEFAULT '0',
  `4_t` int(3) NOT NULL DEFAULT '0',
  `4_q` int(3) NOT NULL DEFAULT '0',
  `5` int(6) NOT NULL DEFAULT '0',
  `5_t` int(3) NOT NULL DEFAULT '0',
  `5_q` int(3) NOT NULL DEFAULT '0',
  `6` int(6) NOT NULL DEFAULT '0',
  `6_t` int(3) NOT NULL DEFAULT '0',
  `6_q` int(3) NOT NULL DEFAULT '0',
  `7` int(6) NOT NULL DEFAULT '0',
  `7_t` int(3) NOT NULL DEFAULT '0',
  `7_q` int(3) NOT NULL DEFAULT '0',
  `8` int(6) NOT NULL DEFAULT '0',
  `8_t` int(3) NOT NULL DEFAULT '0',
  `8_q` int(3) NOT NULL DEFAULT '0',
  `lvl` int(3) NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_artesao_aleatorio`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_artesao_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_carpinteiro`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_carpinteiro` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int(1) NOT NULL DEFAULT '0',
  `1` int(6) NOT NULL DEFAULT '0',
  `1_t` int(3) NOT NULL DEFAULT '0',
  `1_q` int(3) NOT NULL DEFAULT '0',
  `2` int(6) NOT NULL DEFAULT '0',
  `2_t` int(3) NOT NULL DEFAULT '0',
  `2_q` int(3) NOT NULL DEFAULT '0',
  `3` int(6) NOT NULL DEFAULT '0',
  `3_t` int(3) NOT NULL DEFAULT '0',
  `3_q` int(3) NOT NULL DEFAULT '0',
  `4` int(6) NOT NULL DEFAULT '0',
  `4_t` int(3) NOT NULL DEFAULT '0',
  `4_q` int(3) NOT NULL DEFAULT '0',
  `5` int(6) NOT NULL DEFAULT '0',
  `5_t` int(3) NOT NULL DEFAULT '0',
  `5_q` int(3) NOT NULL DEFAULT '0',
  `6` int(6) NOT NULL DEFAULT '0',
  `6_t` int(3) NOT NULL DEFAULT '0',
  `6_q` int(3) NOT NULL DEFAULT '0',
  `7` int(6) NOT NULL DEFAULT '0',
  `7_t` int(3) NOT NULL DEFAULT '0',
  `7_q` int(3) NOT NULL DEFAULT '0',
  `8` int(6) NOT NULL DEFAULT '0',
  `8_t` int(3) NOT NULL DEFAULT '0',
  `8_q` int(3) NOT NULL DEFAULT '0',
  `lvl` int(3) NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_carpinteiro_aleatorio`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_carpinteiro_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_forja`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_forja` (
  `cod_receita` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `aleatorio` int(1) NOT NULL DEFAULT '0',
  `1` int(6) NOT NULL DEFAULT '0',
  `1_t` int(3) NOT NULL DEFAULT '0',
  `1_q` int(3) NOT NULL DEFAULT '0',
  `2` int(6) NOT NULL DEFAULT '0',
  `2_t` int(3) NOT NULL DEFAULT '0',
  `2_q` int(3) NOT NULL DEFAULT '0',
  `3` int(6) NOT NULL DEFAULT '0',
  `3_t` int(3) NOT NULL DEFAULT '0',
  `3_q` int(3) NOT NULL DEFAULT '0',
  `4` int(6) NOT NULL DEFAULT '0',
  `4_t` int(3) NOT NULL DEFAULT '0',
  `4_q` int(3) NOT NULL DEFAULT '0',
  `5` int(6) NOT NULL DEFAULT '0',
  `5_t` int(3) NOT NULL DEFAULT '0',
  `5_q` int(3) NOT NULL DEFAULT '0',
  `6` int(6) NOT NULL DEFAULT '0',
  `6_t` int(3) NOT NULL DEFAULT '0',
  `6_q` int(3) NOT NULL DEFAULT '0',
  `7` int(6) NOT NULL DEFAULT '0',
  `7_t` int(3) NOT NULL DEFAULT '0',
  `7_q` int(3) NOT NULL DEFAULT '0',
  `8` int(6) NOT NULL DEFAULT '0',
  `8_t` int(3) NOT NULL DEFAULT '0',
  `8_q` int(3) NOT NULL DEFAULT '0',
  `lvl` int(3) NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_receita`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=99 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_combinacoes_forja_aleatorio`
--

CREATE TABLE IF NOT EXISTS `tb_combinacoes_forja_aleatorio` (
  `receita` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) NOT NULL,
  `tipo` int(3) NOT NULL,
  `quant` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_conta`
--

CREATE TABLE IF NOT EXISTS `tb_conta` (
  `conta_id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id_encrip` varchar(32) NOT NULL,
  `tripulacao_id` int(6) unsigned zerofill DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `senha` text NOT NULL,
  `nome` varchar(255) NOT NULL,
  `cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cookie` varchar(32) NOT NULL,
  `ativacao` varchar(8) DEFAULT NULL,
  `gold` int(6) unsigned NOT NULL DEFAULT '0',
  `fbid` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`conta_id`),
  UNIQUE KEY `email` (`email`),
  KEY `tripulacao_id` (`tripulacao_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_enquetes`
--

CREATE TABLE IF NOT EXISTS `tb_enquetes` (
  `cod_enquete` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `op_1` text NOT NULL,
  `op_1_v` int(4) NOT NULL,
  `op_2` text NOT NULL,
  `op_2_v` int(4) NOT NULL,
  `op_3` text NOT NULL,
  `op_3_v` int(4) NOT NULL,
  `op_4` text NOT NULL,
  `op_4_v` int(4) NOT NULL,
  `op_5` text NOT NULL,
  `op_5_v` int(4) NOT NULL,
  PRIMARY KEY (`cod_enquete`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_enquetes_ip`
--

CREATE TABLE IF NOT EXISTS `tb_enquetes_ip` (
  `id` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_equipamentos`
--

CREATE TABLE IF NOT EXISTS `tb_equipamentos` (
  `item` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int(4) NOT NULL,
  `cat_dano` int(1) NOT NULL,
  `b_1` int(1) NOT NULL,
  `b_2` int(1) NOT NULL,
  `categoria` int(2) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `lvl` int(3) NOT NULL,
  `treino_max` int(6) NOT NULL,
  `slot` int(2) NOT NULL,
  `requisito` int(1) NOT NULL,
  PRIMARY KEY (`item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=478 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fight`
--

CREATE TABLE IF NOT EXISTS `tb_fight` (
  `fight_id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `modo` tinyint(1) unsigned NOT NULL COMMENT '0 - PvE; 1 - PvP',
  `tipo` tinyint(1) unsigned NOT NULL COMMENT '1: ataque, 2: saque, 3: amigavel',
  `data_inicio` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_fim` timestamp NULL DEFAULT NULL,
  `vencedor` int(6) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`fight_id`),
  KEY `vencedor` (`vencedor`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fight_personagens`
--

CREATE TABLE IF NOT EXISTS `tb_fight_personagens` (
  `fight_id` bigint(20) unsigned zerofill NOT NULL,
  `tripulacao_fight_id` int(6) unsigned zerofill NOT NULL,
  `personagem_id` int(6) unsigned zerofill NOT NULL,
  `hp` int(4) unsigned NOT NULL,
  `mp` int(4) unsigned NOT NULL,
  `atk` int(4) unsigned NOT NULL,
  `def` int(4) unsigned NOT NULL,
  `agl` int(4) unsigned NOT NULL,
  `res` int(4) unsigned NOT NULL,
  `pre` int(4) unsigned NOT NULL,
  `dex` int(4) unsigned NOT NULL,
  `con` int(4) unsigned NOT NULL,
  `vit` int(4) unsigned NOT NULL,
  `dano` int(4) unsigned NOT NULL,
  `armadura` int(4) unsigned NOT NULL,
  `x` int(2) unsigned NOT NULL,
  `y` int(2) unsigned NOT NULL,
  PRIMARY KEY (`personagem_id`),
  KEY `tripulacao_id` (`tripulacao_fight_id`),
  KEY `fight_id` (`fight_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_fight_tripulacao`
--

CREATE TABLE IF NOT EXISTS `tb_fight_tripulacao` (
  `fight_id` bigint(20) unsigned zerofill NOT NULL,
  `tripulacao_id` int(6) unsigned zerofill NOT NULL,
  `ip` varchar(15) NOT NULL,
  `time` tinyint(1) unsigned NOT NULL,
  `x` tinyint(1) unsigned NOT NULL,
  `y` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`tripulacao_id`),
  KEY `fight_id` (`fight_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_habilidades`
--

CREATE TABLE IF NOT EXISTS `tb_habilidades` (
  `habilidade_id` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `categoria` int(1) unsigned NOT NULL DEFAULT '1' COMMENT '1-Classe, 2-Profissao, 3-Akuma',
  `tipo` int(1) unsigned NOT NULL COMMENT '0-passiva, 1-ataque, 2-buff, 3-cura',
  `requisito_pontos` int(1) unsigned NOT NULL DEFAULT '1',
  `requisito_classe` int(1) unsigned NOT NULL DEFAULT '0',
  `arvore` int(1) unsigned NOT NULL DEFAULT '0',
  `sequencia` int(2) unsigned NOT NULL DEFAULT '0',
  `consumo` int(3) unsigned NOT NULL DEFAULT '0',
  `espera` int(2) unsigned NOT NULL DEFAULT '0',
  `alcance` int(2) unsigned NOT NULL DEFAULT '1',
  `area` int(1) unsigned NOT NULL DEFAULT '1',
  `cura_hp` int(4) unsigned NOT NULL DEFAULT '0',
  `cura_mp` int(3) unsigned NOT NULL DEFAULT '0',
  `mod_dano` int(2) NOT NULL DEFAULT '0',
  `mod_alcance` int(2) NOT NULL DEFAULT '0',
  `bonus_attr` int(1) unsigned NOT NULL DEFAULT '0',
  `bonus_attr_quant` int(2) NOT NULL DEFAULT '0',
  `duracao` int(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`habilidade_id`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=398 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_haki_treino`
--

CREATE TABLE IF NOT EXISTS `tb_haki_treino` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `fim` double NOT NULL,
  `pts` int(6) NOT NULL,
  PRIMARY KEY (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ilha_itens`
--

CREATE TABLE IF NOT EXISTS `tb_ilha_itens` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int(2) NOT NULL,
  PRIMARY KEY (`ilha`,`cod_item`,`tipo_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ilha_missoes`
--

CREATE TABLE IF NOT EXISTS `tb_ilha_missoes` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`ilha`,`cod_missao`),
  KEY `cod_missao` (`cod_missao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ilha_mod`
--

CREATE TABLE IF NOT EXISTS `tb_ilha_mod` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `mod` double NOT NULL DEFAULT '1',
  `mod_venda` double NOT NULL DEFAULT '0.8',
  PRIMARY KEY (`ilha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ilha_personagens`
--

CREATE TABLE IF NOT EXISTS `tb_ilha_personagens` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  PRIMARY KEY (`ilha`,`img`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ilha_profissoes`
--

CREATE TABLE IF NOT EXISTS `tb_ilha_profissoes` (
  `ilha` int(4) unsigned zerofill NOT NULL,
  `profissao` int(3) NOT NULL,
  `profissao_lvl_max` int(2) NOT NULL,
  PRIMARY KEY (`ilha`,`profissao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_inimigos`
--

CREATE TABLE IF NOT EXISTS `tb_inimigos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `personagem` int(6) unsigned zerofill NOT NULL,
  `inimigo` int(6) unsigned zerofill NOT NULL,
  `fa` int(6) NOT NULL,
  KEY `id` (`id`),
  KEY `personagem` (`personagem`),
  KEY `inimigo` (`inimigo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_acessorio`
--

CREATE TABLE IF NOT EXISTS `tb_item_acessorio` (
  `cod_acessorio` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_qnt` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_acessorio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=153 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_comida`
--

CREATE TABLE IF NOT EXISTS `tb_item_comida` (
  `cod_comida` int(4) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `hp_recuperado` int(4) NOT NULL DEFAULT '0',
  `mp_recuperado` int(4) NOT NULL DEFAULT '0',
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL DEFAULT '1',
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_comida`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_equipamentos`
--

CREATE TABLE IF NOT EXISTS `tb_item_equipamentos` (
  `item` int(6) unsigned zerofill NOT NULL,
  `cod_equipamento` int(10) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int(4) NOT NULL,
  `cat_dano` int(1) NOT NULL,
  `b_1` int(1) NOT NULL,
  `b_2` int(1) NOT NULL,
  `categoria` int(2) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `lvl` int(3) NOT NULL,
  `upgrade` int(3) NOT NULL DEFAULT '0',
  `treino_max` int(6) NOT NULL,
  `slot` int(2) NOT NULL,
  `requisito` int(1) NOT NULL,
  PRIMARY KEY (`cod_equipamento`),
  KEY `item` (`item`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_mapa`
--

CREATE TABLE IF NOT EXISTS `tb_item_mapa` (
  `cod_mapa` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `id` int(6) unsigned zerofill NOT NULL,
  `img` int(4) NOT NULL DEFAULT '22',
  `nome` varchar(15) NOT NULL DEFAULT 'Mapa',
  `desenho` longtext,
  PRIMARY KEY (`cod_mapa`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_mapa_visivel`
--

CREATE TABLE IF NOT EXISTS `tb_item_mapa_visivel` (
  `cod_mapa` int(6) unsigned zerofill NOT NULL,
  `x` int(3) NOT NULL,
  `y` int(3) NOT NULL,
  `mar` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_navio_canhao`
--

CREATE TABLE IF NOT EXISTS `tb_item_navio_canhao` (
  `cod_canhao` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `bonus` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL,
  `preco` int(8) NOT NULL,
  `mergulho` int(1) NOT NULL,
  PRIMARY KEY (`cod_canhao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_navio_casco`
--

CREATE TABLE IF NOT EXISTS `tb_item_navio_casco` (
  `cod_casco` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(32) NOT NULL,
  `descricao` text NOT NULL,
  `bonus` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL DEFAULT '1',
  `preco` int(8) NOT NULL DEFAULT '1000',
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_casco`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_navio_leme`
--

CREATE TABLE IF NOT EXISTS `tb_item_navio_leme` (
  `cod_leme` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  `descricao` text NOT NULL,
  `bonus` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL DEFAULT '1',
  `preco` int(8) NOT NULL DEFAULT '1000',
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_leme`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_navio_velas`
--

CREATE TABLE IF NOT EXISTS `tb_item_navio_velas` (
  `cod_velas` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(15) NOT NULL,
  `descricao` text NOT NULL,
  `bonus` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL DEFAULT '1',
  `preco` int(8) NOT NULL DEFAULT '1000',
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_velas`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_pose`
--

CREATE TABLE IF NOT EXISTS `tb_item_pose` (
  `cod_pose` int(6) NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL,
  `apontando` varchar(15) NOT NULL,
  `img` int(4) NOT NULL,
  PRIMARY KEY (`cod_pose`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_reagents`
--

CREATE TABLE IF NOT EXISTS `tb_item_reagents` (
  `cod_reagent` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int(4) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `mergulho` int(2) NOT NULL DEFAULT '0',
  `zona` int(3) NOT NULL,
  `mining` int(2) NOT NULL,
  `madeira` int(2) NOT NULL,
  `preco` bigint(20) NOT NULL DEFAULT '100000',
  PRIMARY KEY (`cod_reagent`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_item_remedio`
--

CREATE TABLE IF NOT EXISTS `tb_item_remedio` (
  `cod_remedio` int(4) NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `hp_recuperado` int(4) NOT NULL,
  `mp_recuperado` int(4) NOT NULL,
  `img` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL DEFAULT '1',
  `mergulho` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cod_remedio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_jardim_laftel`
--

CREATE TABLE IF NOT EXISTS `tb_jardim_laftel` (
  `id` int(6) unsigned zerofill NOT NULL,
  `tempo` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_mapa`
--

CREATE TABLE IF NOT EXISTS `tb_mapa` (
  `x` int(3) NOT NULL,
  `y` int(3) NOT NULL,
  `navegavel` tinyint(1) NOT NULL,
  `ilha` int(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `tipo_vento` int(1) NOT NULL DEFAULT '0',
  `dir_vento` int(1) NOT NULL DEFAULT '0',
  `tipo_corrente` int(1) NOT NULL DEFAULT '0',
  `dir_corrente` int(1) NOT NULL DEFAULT '0',
  `mar` int(1) NOT NULL,
  `nevoa` int(1) NOT NULL DEFAULT '0',
  `damage` int(1) NOT NULL DEFAULT '0',
  `tele` int(1) NOT NULL DEFAULT '0',
  `tele_x` int(3) NOT NULL DEFAULT '0',
  `tele_y` int(3) NOT NULL DEFAULT '0',
  `zona` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`x`,`y`),
  KEY `x` (`x`),
  KEY `y` (`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_mapa_contem`
--

CREATE TABLE IF NOT EXISTS `tb_mapa_contem` (
  `x` int(3) NOT NULL,
  `y` int(3) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_marcenaria_reparos`
--

CREATE TABLE IF NOT EXISTS `tb_marcenaria_reparos` (
  `id` int(6) unsigned zerofill NOT NULL,
  `tempo` double NOT NULL,
  `tipo` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_mensagens`
--

CREATE TABLE IF NOT EXISTS `tb_mensagens` (
  `cod_mensagem` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `remetente` int(6) unsigned zerofill NOT NULL,
  `destinatario` int(6) unsigned zerofill NOT NULL,
  `assunto` varchar(40) CHARACTER SET latin1 NOT NULL,
  `texto` text CHARACTER SET latin1 NOT NULL,
  `lido` int(1) NOT NULL DEFAULT '0',
  `hora` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`cod_mensagem`),
  KEY `remetente` (`remetente`),
  KEY `destinatario` (`destinatario`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_missoes`
--

CREATE TABLE IF NOT EXISTS `tb_missoes` (
  `cod_missao` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `respectiva` int(6) unsigned zerofill DEFAULT NULL,
  `faccao` int(1) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `descricao_2` text NOT NULL,
  `descricao_3` text NOT NULL,
  `recompensa_xp` int(4) NOT NULL,
  `recompensa_berries` int(4) NOT NULL,
  `requisito_lvl` int(3) NOT NULL,
  `requisito_missao` int(6) unsigned zerofill NOT NULL,
  `duracao` double NOT NULL,
  `img` int(4) NOT NULL,
  PRIMARY KEY (`cod_missao`),
  KEY `respectiva` (`respectiva`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=512 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_missoes_concluidas`
--

CREATE TABLE IF NOT EXISTS `tb_missoes_concluidas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  PRIMARY KEY (`id`,`cod_missao`),
  KEY `cod_missao` (`cod_missao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_missoes_iniciadas`
--

CREATE TABLE IF NOT EXISTS `tb_missoes_iniciadas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_missao` int(6) unsigned zerofill NOT NULL,
  `fim` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cod_missao` (`cod_missao`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_missoes_r`
--

CREATE TABLE IF NOT EXISTS `tb_missoes_r` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int(3) NOT NULL,
  `y` int(3) NOT NULL,
  `fim` double NOT NULL,
  `modif` int(1) NOT NULL,
  PRIMARY KEY (`id`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_missoes_r_dia`
--

CREATE TABLE IF NOT EXISTS `tb_missoes_r_dia` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int(3) NOT NULL,
  `y` int(3) NOT NULL,
  PRIMARY KEY (`id`,`x`,`y`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_natal`
--

CREATE TABLE IF NOT EXISTS `tb_natal` (
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int(12) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_navio`
--

CREATE TABLE IF NOT EXISTS `tb_navio` (
  `cod_navio` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) NOT NULL,
  `descricao` text NOT NULL,
  `limite` int(2) NOT NULL,
  `img` int(4) NOT NULL,
  `preco` int(12) unsigned NOT NULL,
  PRIMARY KEY (`cod_navio`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_noticias`
--

CREATE TABLE IF NOT EXISTS `tb_noticias` (
  `cod_noticia` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `nome` varchar(30) CHARACTER SET latin1 NOT NULL,
  `texto` text CHARACTER SET latin1 NOT NULL,
  `autor` varchar(15) CHARACTER SET latin1 NOT NULL,
  `criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cod_noticia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_noticias_banner`
--

CREATE TABLE IF NOT EXISTS `tb_noticias_banner` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `img` varchar(30) NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_passaros`
--

CREATE TABLE IF NOT EXISTS `tb_passaros` (
  `id` int(6) unsigned zerofill NOT NULL,
  `quant` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagem_equipamentos`
--

CREATE TABLE IF NOT EXISTS `tb_personagem_equipamentos` (
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
  KEY `8` (`8`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagem_equip_treino`
--

CREATE TABLE IF NOT EXISTS `tb_personagem_equip_treino` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `item` int(6) unsigned zerofill NOT NULL,
  `xp` int(6) unsigned NOT NULL,
  PRIMARY KEY (`cod`,`item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagem_habilidade`
--

CREATE TABLE IF NOT EXISTS `tb_personagem_habilidade` (
  `personagem_habilidade_id` bigint(20) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `personagem_id` int(6) unsigned zerofill NOT NULL,
  `habilidade_id` int(4) unsigned zerofill NOT NULL,
  `img` int(3) unsigned NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  PRIMARY KEY (`personagem_habilidade_id`),
  UNIQUE KEY `personagem_id` (`personagem_id`,`habilidade_id`),
  KEY `habilidade_id` (`habilidade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagem_habilidade_pontos`
--

CREATE TABLE IF NOT EXISTS `tb_personagem_habilidade_pontos` (
  `personagem_id` int(6) unsigned zerofill NOT NULL,
  `habilidade_id` int(4) unsigned zerofill NOT NULL,
  `pontos` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`personagem_id`,`habilidade_id`),
  KEY `habilidade_id` (`habilidade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagem_titulo`
--

CREATE TABLE IF NOT EXISTS `tb_personagem_titulo` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `titulo` int(5) unsigned zerofill NOT NULL,
  UNIQUE KEY `cod` (`cod`,`titulo`),
  KEY `titulo` (`titulo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagens`
--

CREATE TABLE IF NOT EXISTS `tb_personagens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `img` int(4) unsigned zerofill NOT NULL,
  `skin_c` int(2) unsigned NOT NULL DEFAULT '0',
  `skin_r` int(2) unsigned NOT NULL DEFAULT '0',
  `hp` int(4) unsigned NOT NULL DEFAULT '2500',
  `hp_max` int(4) unsigned NOT NULL DEFAULT '2500',
  `mp` int(4) unsigned NOT NULL DEFAULT '100',
  `mp_max` int(4) unsigned NOT NULL DEFAULT '100',
  `xp` int(6) NOT NULL DEFAULT '0',
  `xp_max` int(6) NOT NULL DEFAULT '500',
  `fama_ameaca` int(6) unsigned NOT NULL DEFAULT '0',
  `lvl` int(2) NOT NULL DEFAULT '1',
  `nome` varchar(15) NOT NULL,
  `titulo` int(5) unsigned zerofill DEFAULT NULL,
  `classe` int(3) NOT NULL DEFAULT '0',
  `classe_treino` double NOT NULL DEFAULT '0',
  `classe_aprender` int(1) NOT NULL DEFAULT '0',
  `classe_score` int(6) NOT NULL DEFAULT '0',
  `profissao` int(3) NOT NULL DEFAULT '0',
  `profissao_lvl` int(2) NOT NULL DEFAULT '0',
  `profissao_xp` double NOT NULL DEFAULT '0',
  `profissao_xp_max` double NOT NULL DEFAULT '0',
  `akuma` int(6) unsigned zerofill DEFAULT NULL,
  `atk` int(4) NOT NULL DEFAULT '1',
  `def` int(4) NOT NULL DEFAULT '1',
  `agl` int(4) NOT NULL DEFAULT '1',
  `res` int(4) NOT NULL DEFAULT '1',
  `pre` int(4) NOT NULL DEFAULT '1',
  `dex` int(4) NOT NULL DEFAULT '1',
  `con` int(4) NOT NULL DEFAULT '1',
  `vit` int(4) NOT NULL DEFAULT '1',
  `pts` int(4) NOT NULL DEFAULT '118',
  `cod_acessorio` int(4) NOT NULL DEFAULT '0',
  `respawn` double unsigned NOT NULL DEFAULT '0',
  `respawn_tipo` int(1) NOT NULL DEFAULT '0',
  `haki_lvl` int(3) NOT NULL DEFAULT '1',
  `haki_xp` int(5) NOT NULL DEFAULT '0',
  `haki_xp_max` int(5) NOT NULL DEFAULT '1000',
  `haki_pts` int(3) NOT NULL DEFAULT '1',
  `haki_esq` int(3) NOT NULL DEFAULT '0',
  `haki_blo` int(3) NOT NULL DEFAULT '0',
  `haki_cri` int(3) NOT NULL DEFAULT '0',
  `tatic_a` varchar(5) NOT NULL DEFAULT '0',
  `tatic_d` varchar(5) NOT NULL DEFAULT '0',
  `tatic_p` varchar(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cod`),
  UNIQUE KEY `cod` (`nome`),
  KEY `id` (`id`),
  KEY `titulo` (`titulo`),
  KEY `akuma` (`akuma`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_personagens_skil`
--

CREATE TABLE IF NOT EXISTS `tb_personagens_skil` (
  `cod` int(6) unsigned zerofill NOT NULL,
  `cod_skil` int(4) unsigned zerofill NOT NULL,
  `tipo` int(1) NOT NULL,
  `nome` varchar(20) NOT NULL,
  `descricao` text NOT NULL,
  `icon` int(4) unsigned NOT NULL DEFAULT '1',
  `lvl` int(2) NOT NULL DEFAULT '0',
  `xp` int(4) NOT NULL DEFAULT '0',
  `xp_max` int(4) NOT NULL DEFAULT '100',
  KEY `cod` (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_pve`
--

CREATE TABLE IF NOT EXISTS `tb_pve` (
  `id` int(6) unsigned zerofill NOT NULL,
  `zona` int(2) NOT NULL,
  `quant` int(6) NOT NULL,
  UNIQUE KEY `id` (`id`,`zona`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_fa`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_fa` (
  `posicao` int(6) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `fama_ameaca` int(6) NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_fugas`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_fugas` (
  `posicao` int(6) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `fugas` int(6) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_reputacao`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_reputacao` (
  `posicao` int(6) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `reputacao` int(6) NOT NULL,
  `nome` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_score_atirador`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_score_atirador` (
  `posicao` int(6) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `score` int(6) NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_score_espadachim`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_score_espadachim` (
  `posicao` int(6) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `score` int(6) NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_score_lutador`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_score_lutador` (
  `posicao` int(6) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `score` int(6) NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `cod` (`cod`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_vd`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_vd` (
  `posicao` int(6) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `v_d` int(6) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_ranking_vitorias`
--

CREATE TABLE IF NOT EXISTS `tb_ranking_vitorias` (
  `posicao` int(6) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `vitorias` int(6) NOT NULL,
  `bandeira` varchar(36) NOT NULL,
  `faccao` int(2) NOT NULL,
  PRIMARY KEY (`posicao`),
  UNIQUE KEY `id` (`id`,`nome`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_realizacoes`
--

CREATE TABLE IF NOT EXISTS `tb_realizacoes` (
  `cod_realizacao` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `tipo` int(1) NOT NULL DEFAULT '0',
  `categoria` int(3) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text NOT NULL,
  `pontos` int(6) NOT NULL,
  `titulo` int(5) unsigned zerofill NOT NULL DEFAULT '00000',
  PRIMARY KEY (`cod_realizacao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=232 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_realizacoes_concluidas`
--

CREATE TABLE IF NOT EXISTS `tb_realizacoes_concluidas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_realizacao` int(5) unsigned zerofill NOT NULL,
  `tipo` int(1) NOT NULL DEFAULT '0',
  `personagem` int(6) unsigned zerofill DEFAULT NULL,
  `momento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`,`cod_realizacao`,`tipo`,`personagem`),
  KEY `cod_realizacao` (`cod_realizacao`),
  KEY `personagem` (`personagem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_relatorio`
--

CREATE TABLE IF NOT EXISTS `tb_relatorio` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `relatorio` bigint(20) NOT NULL,
  `id` int(6) unsigned zerofill NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `tipo` int(1) NOT NULL,
  `nome_skil` varchar(30) NOT NULL,
  `descricao_skil` text NOT NULL,
  `img_skil` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_relatorio_afetados`
--

CREATE TABLE IF NOT EXISTS `tb_relatorio_afetados` (
  `combate` bigint(20) unsigned zerofill NOT NULL,
  `relatorio` bigint(20) NOT NULL,
  `acerto` int(1) NOT NULL,
  `quadro` varchar(5) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  `img` int(4) unsigned zerofill NOT NULL,
  `nome` varchar(15) NOT NULL,
  `tipo` int(1) unsigned NOT NULL,
  `efeito` int(5) NOT NULL,
  `atributo` int(1) unsigned NOT NULL,
  `cura_h` int(5) unsigned NOT NULL,
  `cura_m` int(5) unsigned NOT NULL,
  `derrotado` int(1) NOT NULL,
  `bloq` int(1) NOT NULL,
  `esq` int(1) NOT NULL,
  `cri` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_resets`
--

CREATE TABLE IF NOT EXISTS `tb_resets` (
  `tipo` int(2) NOT NULL,
  `cod` int(6) unsigned zerofill NOT NULL,
  KEY `cod` (`cod`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_reset_senha_token`
--

CREATE TABLE IF NOT EXISTS `tb_reset_senha_token` (
  `conta_id` int(10) unsigned zerofill NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiration` timestamp NOT NULL,
  UNIQUE KEY `tb_reset_senha_token_token_uindex` (`token`),
  KEY `tb_reset_senha_token_tb_conta_conta_id_fk` (`conta_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_rnk_patente`
--

CREATE TABLE IF NOT EXISTS `tb_rnk_patente` (
  `patente_id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `nome_0` varchar(50) NOT NULL,
  `nome_1` varchar(50) NOT NULL,
  `reputacao` int(6) NOT NULL DEFAULT '0',
  `ranking` int(10) NOT NULL DEFAULT '0',
  `lvl` int(2) NOT NULL DEFAULT '0',
  `reputacao_base` int(5) NOT NULL,
  PRIMARY KEY (`patente_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_rotas`
--

CREATE TABLE IF NOT EXISTS `tb_rotas` (
  `id` int(6) unsigned zerofill NOT NULL,
  `x` int(1) NOT NULL,
  `y` int(1) NOT NULL,
  `indice` int(2) NOT NULL,
  `momento` double NOT NULL,
  UNIQUE KEY `id` (`id`,`x`,`y`,`indice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_skil_atk`
--

CREATE TABLE IF NOT EXISTS `tb_skil_atk` (
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `consumo` int(4) NOT NULL DEFAULT '0',
  `requisito_atr_1` int(1) NOT NULL,
  `requisito_atr_1_qnt` int(4) NOT NULL,
  `requisito_atr_2` int(1) NOT NULL,
  `requisito_atr_2_qnt` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL,
  `requisito_berries` int(10) NOT NULL,
  `requisito_classe` int(3) NOT NULL DEFAULT '0',
  `requisito_prof` int(4) NOT NULL DEFAULT '0',
  `dano` int(6) NOT NULL,
  `alcance` int(2) NOT NULL DEFAULT '1',
  `area` int(2) NOT NULL DEFAULT '1',
  `espera` int(1) NOT NULL,
  `categoria` int(1) NOT NULL,
  PRIMARY KEY (`cod_skil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=166 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_skil_buff`
--

CREATE TABLE IF NOT EXISTS `tb_skil_buff` (
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `consumo` int(4) NOT NULL DEFAULT '0',
  `requisito_atr_1` int(1) NOT NULL,
  `requisito_atr_1_qnt` int(4) NOT NULL,
  `requisito_atr_2` int(1) NOT NULL,
  `requisito_atr_2_qnt` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL,
  `requisito_berries` int(10) NOT NULL,
  `requisito_classe` int(3) NOT NULL,
  `requisito_prof` int(4) NOT NULL,
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_qnt` int(4) NOT NULL,
  `duracao` int(1) NOT NULL,
  `alcance` int(2) NOT NULL DEFAULT '0',
  `area` int(2) NOT NULL DEFAULT '1',
  `espera` int(1) NOT NULL,
  `categoria` int(1) NOT NULL,
  PRIMARY KEY (`cod_skil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_skil_passiva`
--

CREATE TABLE IF NOT EXISTS `tb_skil_passiva` (
  `cod_skil` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `requisito_atr_1` int(1) NOT NULL,
  `requisito_atr_1_qnt` int(4) NOT NULL,
  `requisito_atr_2` int(1) NOT NULL,
  `requisito_atr_2_qnt` int(4) NOT NULL,
  `requisito_lvl` int(2) NOT NULL,
  `requisito_berries` int(10) NOT NULL,
  `requisito_classe` int(3) NOT NULL,
  `requisito_prof` int(4) NOT NULL,
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_qnt` int(4) NOT NULL,
  `categoria` int(1) NOT NULL,
  PRIMARY KEY (`cod_skil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_titulos`
--

CREATE TABLE IF NOT EXISTS `tb_titulos` (
  `cod_titulo` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `bonus_atr` int(1) NOT NULL,
  `bonus_atr_quant` int(3) NOT NULL,
  `nome` varchar(100) NOT NULL,
  PRIMARY KEY (`cod_titulo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_torneio`
--

CREATE TABLE IF NOT EXISTS `tb_torneio` (
  `id` int(8) unsigned zerofill NOT NULL,
  `categoria` int(1) NOT NULL,
  `pontos` int(3) unsigned NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_torneio_inscricao`
--

CREATE TABLE IF NOT EXISTS `tb_torneio_inscricao` (
  `id` int(7) unsigned zerofill NOT NULL,
  `facebook` text NOT NULL,
  `nome` varchar(100) NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `status` int(1) unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuarios`
--

CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `conta_id` int(11) unsigned zerofill NOT NULL,
  `tripulacao` varchar(15) NOT NULL,
  `cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_logon` double NOT NULL,
  `faccao` int(1) NOT NULL,
  `reputacao` int(6) NOT NULL DEFAULT '0',
  `coord_x_navio` int(3) NOT NULL,
  `coord_y_navio` int(3) NOT NULL,
  `res_x` int(3) NOT NULL,
  `res_y` int(3) NOT NULL,
  `cod_personagem` int(4) unsigned zerofill NOT NULL,
  `berries` double unsigned NOT NULL DEFAULT '5000',
  `gold` int(6) NOT NULL DEFAULT '0',
  `recrutando` double NOT NULL DEFAULT '0',
  `mergulho` double NOT NULL DEFAULT '0',
  `mergulho_cod` int(6) unsigned zerofill NOT NULL,
  `expedicao` double NOT NULL,
  `expedicao_cod` int(6) unsigned zerofill NOT NULL,
  `desenho` double NOT NULL,
  `desenho_cod` int(6) unsigned zerofill NOT NULL,
  `mining` double NOT NULL DEFAULT '0',
  `mining_cod` int(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `madeira` double NOT NULL DEFAULT '0',
  `madeira_cod` int(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `adm` int(1) NOT NULL DEFAULT '0',
  `M` int(1) NOT NULL DEFAULT '0',
  `vitorias` int(6) NOT NULL DEFAULT '0',
  `derrotas` int(11) NOT NULL DEFAULT '0',
  `fugas` int(11) NOT NULL DEFAULT '0',
  `bandeira` varchar(36) NOT NULL DEFAULT '010113046758010128123542010115204020',
  `kai` int(1) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL,
  `realizacoes` int(9) NOT NULL DEFAULT '0',
  `disposicao` int(5) NOT NULL DEFAULT '10000',
  `isca` int(3) NOT NULL DEFAULT '0',
  `inativo` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tripulacao` (`tripulacao`),
  KEY `conta_id` (`conta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario_itens`
--

CREATE TABLE IF NOT EXISTS `tb_usuario_itens` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_item` int(6) unsigned zerofill NOT NULL,
  `tipo_item` int(2) NOT NULL,
  `quant` int(4) NOT NULL DEFAULT '1',
  KEY `id` (`id`,`cod_item`,`tipo_item`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuario_navio`
--

CREATE TABLE IF NOT EXISTS `tb_usuario_navio` (
  `id` int(6) unsigned zerofill NOT NULL,
  `cod_navio` int(4) unsigned zerofill DEFAULT NULL,
  `cod_casco` int(4) unsigned NOT NULL DEFAULT '0',
  `cod_leme` int(4) NOT NULL DEFAULT '0',
  `cod_velas` int(4) NOT NULL DEFAULT '0',
  `cod_canhao` int(4) unsigned zerofill NOT NULL,
  `hp` int(4) NOT NULL DEFAULT '100',
  `hp_max` int(4) NOT NULL DEFAULT '100',
  `lvl` int(2) NOT NULL DEFAULT '1',
  `reparo` double NOT NULL DEFAULT '0',
  `reparo_tipo` int(1) NOT NULL,
  `reparo_quant` int(4) NOT NULL,
  `xp` int(6) NOT NULL DEFAULT '0',
  `xp_max` int(6) NOT NULL DEFAULT '250',
  `capacidade_inventario` int(11) DEFAULT '55',
  PRIMARY KEY (`id`),
  KEY `cod_navio` (`cod_navio`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vip`
--

CREATE TABLE IF NOT EXISTS `tb_vip` (
  `id` int(6) unsigned zerofill NOT NULL,
  `luneta` int(1) NOT NULL DEFAULT '0',
  `luneta_duracao` double NOT NULL DEFAULT '0',
  `sense` int(1) NOT NULL,
  `sense_duracao` double NOT NULL,
  `tatic` int(1) NOT NULL,
  `tatic_duracao` double NOT NULL,
  `reset_personagem` int(3) unsigned NOT NULL DEFAULT '0',
  `reset_nome` int(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vip_confirmar`
--

CREATE TABLE IF NOT EXISTS `tb_vip_confirmar` (
  `id` int(6) unsigned zerofill NOT NULL,
  `plano` int(1) NOT NULL,
  `metodo` varchar(30) NOT NULL DEFAULT 'deposito',
  `data` varchar(50) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `identificacao` varchar(200) NOT NULL,
  `adicional` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vip_pagamentos`
--

CREATE TABLE IF NOT EXISTS `tb_vip_pagamentos` (
  `id` int(11) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `mensagem` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `tb_afilhados`
--
ALTER TABLE `tb_afilhados`
  ADD CONSTRAINT `tb_afilhados_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_afilhados_ibfk_2` FOREIGN KEY (`afilhado`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_afilhados_recrutados`
--
ALTER TABLE `tb_afilhados_recrutados`
  ADD CONSTRAINT `tb_afilhados_recrutados_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_akuma_skil_atk`
--
ALTER TABLE `tb_akuma_skil_atk`
  ADD CONSTRAINT `tb_akuma_skil_atk_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_akuma_skil_buff`
--
ALTER TABLE `tb_akuma_skil_buff`
  ADD CONSTRAINT `tb_akuma_skil_buff_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_akuma_skil_passiva`
--
ALTER TABLE `tb_akuma_skil_passiva`
  ADD CONSTRAINT `tb_akuma_skil_passiva_ibfk_1` FOREIGN KEY (`cod_akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_aliados`
--
ALTER TABLE `tb_alianca_aliados`
  ADD CONSTRAINT `tb_alianca_aliados_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_alianca_aliados_ibfk_2` FOREIGN KEY (`cod_aliado`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_banco`
--
ALTER TABLE `tb_alianca_banco`
  ADD CONSTRAINT `tb_alianca_banco_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_banco_log`
--
ALTER TABLE `tb_alianca_banco_log`
  ADD CONSTRAINT `tb_alianca_banco_log_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_convite`
--
ALTER TABLE `tb_alianca_convite`
  ADD CONSTRAINT `tb_alianca_convite_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_alianca_convite_ibfk_2` FOREIGN KEY (`convidado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_guerra_ajuda`
--
ALTER TABLE `tb_alianca_guerra_ajuda`
  ADD CONSTRAINT `tb_alianca_guerra_ajuda_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_alianca_guerra_ajuda_ibfk_2` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_guerra_pedidos`
--
ALTER TABLE `tb_alianca_guerra_pedidos`
  ADD CONSTRAINT `tb_alianca_guerra_pedidos_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_alianca_guerra_pedidos_ibfk_2` FOREIGN KEY (`convidado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_membros`
--
ALTER TABLE `tb_alianca_membros`
  ADD CONSTRAINT `tb_alianca_membros_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_alianca_membros_ibfk_2` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_alianca_missoes`
--
ALTER TABLE `tb_alianca_missoes`
  ADD CONSTRAINT `tb_alianca_missoes_ibfk_1` FOREIGN KEY (`cod_alianca`) REFERENCES `tb_alianca` (`cod_alianca`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_amigos`
--
ALTER TABLE `tb_amigos`
  ADD CONSTRAINT `tb_amigos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_amigos_ibfk_2` FOREIGN KEY (`amigo`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_chat`
--
ALTER TABLE `tb_chat`
  ADD CONSTRAINT `tb_chat_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_chat_bloqueio`
--
ALTER TABLE `tb_chat_bloqueio`
  ADD CONSTRAINT `tb_chat_bloqueio_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_coliseu_cp`
--
ALTER TABLE `tb_coliseu_cp`
  ADD CONSTRAINT `tb_coliseu_cp_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_coliseu_fila`
--
ALTER TABLE `tb_coliseu_fila`
  ADD CONSTRAINT `tb_coliseu_fila_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_coliseu_ranking`
--
ALTER TABLE `tb_coliseu_ranking`
  ADD CONSTRAINT `tb_coliseu_ranking_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate`
--
ALTER TABLE `tb_combate`
  ADD CONSTRAINT `tb_combate_ibfk_1` FOREIGN KEY (`id_1`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_combate_ibfk_2` FOREIGN KEY (`id_2`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_buff`
--
ALTER TABLE `tb_combate_buff`
  ADD CONSTRAINT `tb_combate_buff_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_desafio`
--
ALTER TABLE `tb_combate_desafio`
  ADD CONSTRAINT `tb_combate_desafio_ibfk_1` FOREIGN KEY (`desafiante`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_combate_desafio_ibfk_2` FOREIGN KEY (`desafiado`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_log`
--
ALTER TABLE `tb_combate_log`
  ADD CONSTRAINT `tb_combate_log_ibfk_1` FOREIGN KEY (`id_1`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_combate_log_ibfk_2` FOREIGN KEY (`id_2`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_npc`
--
ALTER TABLE `tb_combate_npc`
  ADD CONSTRAINT `tb_combate_npc_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_personagens`
--
ALTER TABLE `tb_combate_personagens`
  ADD CONSTRAINT `tb_combate_personagens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_combate_personagens_ibfk_2` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_combate_skil_espera`
--
ALTER TABLE `tb_combate_skil_espera`
  ADD CONSTRAINT `tb_combate_skil_espera_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_combate_skil_espera_ibfk_2` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_conta`
--
ALTER TABLE `tb_conta`
  ADD CONSTRAINT `tb_conta_ibfk_1` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_enquetes_ip`
--
ALTER TABLE `tb_enquetes_ip`
  ADD CONSTRAINT `tb_enquetes_ip_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_fight`
--
ALTER TABLE `tb_fight`
  ADD CONSTRAINT `tb_fight_ibfk_1` FOREIGN KEY (`vencedor`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_fight_personagens`
--
ALTER TABLE `tb_fight_personagens`
  ADD CONSTRAINT `tb_fight_personagens_ibfk_2` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_fight_personagens_ibfk_3` FOREIGN KEY (`tripulacao_fight_id`) REFERENCES `tb_fight_tripulacao` (`tripulacao_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_fight_personagens_ibfk_4` FOREIGN KEY (`fight_id`) REFERENCES `tb_fight` (`fight_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_fight_tripulacao`
--
ALTER TABLE `tb_fight_tripulacao`
  ADD CONSTRAINT `tb_fight_tripulacao_ibfk_1` FOREIGN KEY (`fight_id`) REFERENCES `tb_fight` (`fight_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_fight_tripulacao_ibfk_2` FOREIGN KEY (`tripulacao_id`) REFERENCES `tb_usuarios` (`id`) ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_haki_treino`
--
ALTER TABLE `tb_haki_treino`
  ADD CONSTRAINT `tb_haki_treino_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_ilha_missoes`
--
ALTER TABLE `tb_ilha_missoes`
  ADD CONSTRAINT `tb_ilha_missoes_ibfk_1` FOREIGN KEY (`cod_missao`) REFERENCES `tb_missoes` (`cod_missao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_inimigos`
--
ALTER TABLE `tb_inimigos`
  ADD CONSTRAINT `tb_inimigos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_inimigos_ibfk_2` FOREIGN KEY (`personagem`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_item_equipamentos`
--
ALTER TABLE `tb_item_equipamentos`
  ADD CONSTRAINT `tb_item_equipamentos_ibfk_1` FOREIGN KEY (`cod_equipamento`) REFERENCES `tb_equipamentos` (`item`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_jardim_laftel`
--
ALTER TABLE `tb_jardim_laftel`
  ADD CONSTRAINT `tb_jardim_laftel_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_mapa_contem`
--
ALTER TABLE `tb_mapa_contem`
  ADD CONSTRAINT `tb_mapa_contem_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_marcenaria_reparos`
--
ALTER TABLE `tb_marcenaria_reparos`
  ADD CONSTRAINT `tb_marcenaria_reparos_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_mensagens`
--
ALTER TABLE `tb_mensagens`
  ADD CONSTRAINT `tb_mensagens_ibfk_1` FOREIGN KEY (`remetente`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_mensagens_ibfk_2` FOREIGN KEY (`destinatario`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_missoes`
--
ALTER TABLE `tb_missoes`
  ADD CONSTRAINT `tb_missoes_ibfk_1` FOREIGN KEY (`respectiva`) REFERENCES `tb_missoes` (`cod_missao`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_missoes_concluidas`
--
ALTER TABLE `tb_missoes_concluidas`
  ADD CONSTRAINT `tb_missoes_concluidas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_missoes_concluidas_ibfk_2` FOREIGN KEY (`cod_missao`) REFERENCES `tb_missoes` (`cod_missao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_missoes_iniciadas`
--
ALTER TABLE `tb_missoes_iniciadas`
  ADD CONSTRAINT `tb_missoes_iniciadas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_missoes_iniciadas_ibfk_2` FOREIGN KEY (`cod_missao`) REFERENCES `tb_missoes` (`cod_missao`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_missoes_r`
--
ALTER TABLE `tb_missoes_r`
  ADD CONSTRAINT `tb_missoes_r_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_missoes_r_dia`
--
ALTER TABLE `tb_missoes_r_dia`
  ADD CONSTRAINT `tb_missoes_r_dia_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagem_equipamentos`
--
ALTER TABLE `tb_personagem_equipamentos`
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_10` FOREIGN KEY (`4`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_11` FOREIGN KEY (`5`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_12` FOREIGN KEY (`6`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_13` FOREIGN KEY (`7`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_14` FOREIGN KEY (`8`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_2` FOREIGN KEY (`1`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_3` FOREIGN KEY (`2`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_4` FOREIGN KEY (`3`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_5` FOREIGN KEY (`4`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_6` FOREIGN KEY (`5`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_7` FOREIGN KEY (`6`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_8` FOREIGN KEY (`7`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_equipamentos_ibfk_9` FOREIGN KEY (`3`) REFERENCES `tb_item_equipamentos` (`cod_equipamento`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagem_equip_treino`
--
ALTER TABLE `tb_personagem_equip_treino`
  ADD CONSTRAINT `tb_personagem_equip_treino_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagem_habilidade`
--
ALTER TABLE `tb_personagem_habilidade`
  ADD CONSTRAINT `tb_personagem_habilidade_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_habilidade_ibfk_2` FOREIGN KEY (`habilidade_id`) REFERENCES `tb_habilidades` (`habilidade_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagem_habilidade_pontos`
--
ALTER TABLE `tb_personagem_habilidade_pontos`
  ADD CONSTRAINT `tb_personagem_habilidade_pontos_ibfk_1` FOREIGN KEY (`personagem_id`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_habilidade_pontos_ibfk_2` FOREIGN KEY (`habilidade_id`) REFERENCES `tb_habilidades` (`habilidade_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagem_titulo`
--
ALTER TABLE `tb_personagem_titulo`
  ADD CONSTRAINT `tb_personagem_titulo_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagem_titulo_ibfk_2` FOREIGN KEY (`titulo`) REFERENCES `tb_titulos` (`cod_titulo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagens`
--
ALTER TABLE `tb_personagens`
  ADD CONSTRAINT `tb_personagens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_personagens_ibfk_2` FOREIGN KEY (`titulo`) REFERENCES `tb_titulos` (`cod_titulo`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `tb_personagens_ibfk_3` FOREIGN KEY (`akuma`) REFERENCES `tb_akuma` (`cod_akuma`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_personagens_skil`
--
ALTER TABLE `tb_personagens_skil`
  ADD CONSTRAINT `tb_personagens_skil_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_pve`
--
ALTER TABLE `tb_pve`
  ADD CONSTRAINT `tb_pve_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_realizacoes_concluidas`
--
ALTER TABLE `tb_realizacoes_concluidas`
  ADD CONSTRAINT `tb_realizacoes_concluidas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_realizacoes_concluidas_ibfk_2` FOREIGN KEY (`cod_realizacao`) REFERENCES `tb_realizacoes` (`cod_realizacao`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_realizacoes_concluidas_ibfk_3` FOREIGN KEY (`personagem`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_resets`
--
ALTER TABLE `tb_resets`
  ADD CONSTRAINT `tb_resets_ibfk_1` FOREIGN KEY (`cod`) REFERENCES `tb_personagens` (`cod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_reset_senha_token`
--
ALTER TABLE `tb_reset_senha_token`
  ADD CONSTRAINT `tb_reset_senha_token_tb_conta_conta_id_fk` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_rotas`
--
ALTER TABLE `tb_rotas`
  ADD CONSTRAINT `tb_rotas_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD CONSTRAINT `tb_usuarios_ibfk_1` FOREIGN KEY (`conta_id`) REFERENCES `tb_conta` (`conta_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_usuario_itens`
--
ALTER TABLE `tb_usuario_itens`
  ADD CONSTRAINT `tb_usuario_itens_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_usuario_navio`
--
ALTER TABLE `tb_usuario_navio`
  ADD CONSTRAINT `tb_usuario_navio_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_usuario_navio_ibfk_2` FOREIGN KEY (`cod_navio`) REFERENCES `tb_navio` (`cod_navio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `tb_vip`
--
ALTER TABLE `tb_vip`
  ADD CONSTRAINT `tb_vip_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tb_usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
