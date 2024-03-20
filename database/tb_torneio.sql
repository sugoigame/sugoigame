-- sugoi_v2.tb_torneio definition

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
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- sugoi_v2.tb_torneio_inscricao definition

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- sugoi_v2.tb_torneio_chave definition

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
) ENGINE=InnoDB AUTO_INCREMENT=1247 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
