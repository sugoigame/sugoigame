ALTER TABLE tb_missoes_iniciadas
  ADD log LONGTEXT NULL;
ALTER TABLE tb_missoes_iniciadas
  ADD venceu TINYINT NULL;
ALTER TABLE tb_missoes_iniciadas
  ADD hp_final LONGTEXT NULL;
ALTER TABLE tb_missoes_iniciadas
  ADD mp_final LONGTEXT NULL;

ALTER TABLE tb_usuarios
  ADD missao_rotation TEXT NULL;

UPDATE tb_usuarios
SET progress = progress + 1
WHERE progress >= 2;

UPDATE tb_missoes
SET duracao = 5
WHERE cod_missao <= 4;

UPDATE tb_missoes
SET duracao = 10
WHERE cod_missao >= 5 AND cod_missao <= 7;

UPDATE tb_missoes
SET duracao = 30
WHERE cod_missao >= 8 AND cod_missao <= 9;

UPDATE tb_missoes
SET duracao = 60
WHERE cod_missao >= 10 AND cod_missao <= 11;

CREATE TABLE tb_missoes_chefe_ilha
(
  id             INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id  INT UNSIGNED ZEROFILL    NOT NULL,
  ilha_derrotado INT UNSIGNED             NOT NULL,
  data           TIMESTAMP                         DEFAULT current_timestamp(),
  CONSTRAINT tb_missoes_chefe_ilha_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
ALTER TABLE tb_combate_npc
  ADD battle_back INT UNSIGNED NULL;
ALTER TABLE tb_combate_npc
  ADD chefe_ilha TINYINT DEFAULT 0 NULL;

UPDATE tb_missoes
SET recompensa_xp = 200
WHERE cod_missao = 1;

UPDATE tb_missoes
SET recompensa_xp = 300, recompensa_berries = 4000
WHERE cod_missao = 2;

UPDATE tb_missoes
SET requisito_missao = 2, recompensa_xp = 750, recompensa_berries = 4000
WHERE cod_missao = 6;

DELETE FROM tb_missoes
WHERE cod_missao = 3
      OR cod_missao = 4
      OR cod_missao = 5
      OR cod_missao = 7
      OR cod_missao = 8
      OR cod_missao = 9
      OR cod_missao = 10
      OR cod_missao = 11;

UPDATE tb_missoes
SET recompensa_xp = 300
WHERE requisito_lvl = 3;

TRUNCATE tb_missoes_concluidas;

ALTER TABLE tb_missoes_concluidas
  DROP FOREIGN KEY tb_missoes_concluidas_ibfk_1;
ALTER TABLE tb_missoes_concluidas
  DROP FOREIGN KEY tb_missoes_concluidas_ibfk_2;
ALTER TABLE tb_missoes_concluidas
  DROP PRIMARY KEY;
ALTER TABLE tb_missoes_concluidas
  ADD increment_id BIGINT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT;
ALTER TABLE tb_missoes_concluidas
  MODIFY COLUMN increment_id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT
  FIRST;
ALTER TABLE tb_missoes_concluidas
  ADD CONSTRAINT tb_missoes_concluidas_tb_usuarios_id_fk
FOREIGN KEY (id) REFERENCES tb_usuarios (id)
  ON DELETE CASCADE
  ON UPDATE CASCADE;

TRUNCATE tb_missoes_iniciadas;

ALTER TABLE tb_missoes_iniciadas
  DROP FOREIGN KEY tb_missoes_iniciadas_ibfk_2;
ALTER TABLE tb_missoes_iniciadas
  ADD tipo_karma VARCHAR(3) NOT NULL;

TRUNCATE tb_ilha_missoes;

ALTER TABLE tb_ilha_missoes
  DROP FOREIGN KEY tb_ilha_missoes_ibfk_1;

INSERT IGNORE INTO tb_ilha_missoes (ilha, cod_missao) VALUES
  (1, 1),
  (8, 1),
  (15, 1),
  (22, 1),
  (1, 2),
  (8, 2),
  (15, 2),
  (22, 2),
  (1, 3),
  (8, 3),
  (15, 3),
  (22, 3),
  (1, 4),
  (8, 4),
  (15, 4),
  (22, 4),
  (1, 5),
  (8, 5),
  (15, 5),
  (22, 5),
  (1, 6),
  (8, 6),
  (15, 6),
  (22, 6),
  (1, 7),
  (8, 7),
  (15, 7),
  (22, 7),
  (2, 8),
  (9, 8),
  (16, 8),
  (23, 8),
  (2, 9),
  (9, 9),
  (16, 9),
  (23, 9),
  (2, 10),
  (9, 10),
  (16, 10),
  (23, 10),
  (2, 11),
  (9, 11),
  (16, 11),
  (23, 11),
  (2, 12),
  (9, 12),
  (16, 12),
  (23, 12),
  (2, 13),
  (9, 13),
  (16, 13),
  (23, 13),
  (2, 14),
  (9, 14),
  (16, 14),
  (23, 14),
  (2, 15),
  (9, 15),
  (16, 15),
  (23, 15),
  (3, 16),
  (10, 16),
  (17, 16),
  (24, 16),
  (3, 17),
  (10, 17),
  (17, 17),
  (24, 17),
  (3, 18),
  (10, 18),
  (17, 18),
  (24, 18),
  (3, 19),
  (10, 19),
  (17, 19),
  (24, 19),
  (3, 20),
  (10, 20),
  (17, 20),
  (24, 20),
  (3, 21),
  (10, 21),
  (17, 21),
  (24, 21),
  (3, 22),
  (10, 22),
  (17, 22),
  (24, 22),
  (3, 23),
  (10, 23),
  (17, 23),
  (24, 23),
  (3, 24),
  (10, 24),
  (17, 24),
  (24, 24),
  (3, 25),
  (10, 25),
  (17, 25),
  (24, 25),
  (4, 26),
  (11, 26),
  (18, 26),
  (25, 26),
  (4, 27),
  (11, 27),
  (18, 27),
  (25, 27),
  (4, 28),
  (11, 28),
  (18, 28),
  (25, 28),
  (4, 29),
  (11, 29),
  (18, 29),
  (25, 29),
  (4, 30),
  (11, 30),
  (18, 30),
  (25, 30),
  (4, 31),
  (11, 31),
  (18, 31),
  (25, 31),
  (4, 32),
  (11, 32),
  (18, 32),
  (25, 32),
  (4, 33),
  (11, 33),
  (18, 33),
  (25, 33),
  (4, 34),
  (11, 34),
  (18, 34),
  (25, 34),
  (4, 35),
  (11, 35),
  (18, 35),
  (25, 35),
  (5, 36),
  (12, 36),
  (19, 36),
  (26, 36),
  (5, 37),
  (12, 37),
  (19, 37),
  (26, 37),
  (5, 38),
  (12, 38),
  (19, 38),
  (26, 38),
  (5, 39),
  (12, 39),
  (19, 39),
  (26, 39),
  (5, 40),
  (12, 40),
  (19, 40),
  (26, 40),
  (5, 41),
  (12, 41),
  (19, 41),
  (26, 41),
  (5, 42),
  (12, 42),
  (19, 42),
  (26, 42),
  (5, 43),
  (12, 43),
  (19, 43),
  (26, 43),
  (5, 44),
  (12, 44),
  (19, 44),
  (26, 44),
  (5, 45),
  (12, 45),
  (19, 45),
  (26, 45),
  (5, 46),
  (12, 46),
  (19, 46),
  (26, 46),
  (5, 47),
  (12, 47),
  (19, 47),
  (26, 47),
  (6, 48),
  (13, 48),
  (20, 48),
  (27, 48),
  (6, 49),
  (13, 49),
  (20, 49),
  (27, 49),
  (6, 50),
  (13, 50),
  (20, 50),
  (27, 50),
  (6, 51),
  (13, 51),
  (20, 51),
  (27, 51),
  (6, 52),
  (13, 52),
  (20, 52),
  (27, 52),
  (6, 53),
  (13, 53),
  (20, 53),
  (27, 53),
  (6, 54),
  (13, 54),
  (20, 54),
  (27, 54),
  (6, 55),
  (13, 55),
  (20, 55),
  (27, 55),
  (6, 56),
  (13, 56),
  (20, 56),
  (27, 56),
  (6, 57),
  (13, 57),
  (20, 57),
  (27, 57),
  (6, 58),
  (13, 58),
  (20, 58),
  (27, 58),
  (6, 59),
  (13, 59),
  (20, 59),
  (27, 59),
  (7, 60),
  (14, 60),
  (21, 60),
  (28, 60),
  (7, 61),
  (14, 61),
  (21, 61),
  (28, 61),
  (7, 62),
  (14, 62),
  (21, 62),
  (28, 62),
  (7, 63),
  (14, 63),
  (21, 63),
  (28, 63),
  (7, 64),
  (14, 64),
  (21, 64),
  (28, 64),
  (7, 65),
  (14, 65),
  (21, 65),
  (28, 65),
  (7, 66),
  (14, 66),
  (21, 66),
  (28, 66),
  (7, 67),
  (14, 67),
  (21, 67),
  (28, 67),
  (7, 68),
  (14, 68),
  (21, 68),
  (28, 68),
  (7, 69),
  (14, 69),
  (21, 69),
  (28, 69),
  (7, 70),
  (14, 70),
  (21, 70),
  (28, 70),
  (7, 71),
  (14, 71),
  (21, 71),
  (28, 71),
  (29, 72),
  (29, 73),
  (29, 74),
  (29, 75),
  (29, 76),
  (29, 77),
  (30, 78),
  (30, 79),
  (30, 80),
  (30, 81),
  (30, 82),
  (30, 83),
  (31, 84),
  (31, 85),
  (31, 86),
  (31, 87),
  (31, 88),
  (31, 89),
  (32, 90),
  (32, 91),
  (32, 92),
  (32, 93),
  (32, 94),
  (32, 95),
  (33, 96),
  (33, 97),
  (33, 98),
  (33, 99),
  (33, 100),
  (33, 101),
  (34, 102),
  (34, 103),
  (34, 104),
  (34, 105),
  (34, 106),
  (34, 107),
  (35, 108),
  (35, 109),
  (35, 110),
  (35, 111),
  (35, 112),
  (35, 113),
  (36, 114),
  (36, 115),
  (36, 116),
  (36, 117),
  (36, 118),
  (36, 119),
  (37, 120),
  (37, 121),
  (37, 122),
  (37, 123),
  (37, 124),
  (37, 125),
  (38, 126),
  (38, 127),
  (38, 128),
  (38, 129),
  (38, 130),
  (38, 131),
  (39, 132),
  (39, 133),
  (39, 134),
  (39, 135),
  (39, 136),
  (39, 137),
  (40, 138),
  (40, 139),
  (40, 140),
  (40, 141),
  (40, 142),
  (40, 143),
  (41, 144),
  (41, 145),
  (41, 146),
  (41, 147),
  (41, 148),
  (41, 149),
  (42, 150),
  (42, 151),
  (42, 152),
  (42, 153),
  (42, 154),
  (42, 155),
  (43, 156),
  (43, 157),
  (43, 158),
  (43, 159),
  (43, 160),
  (43, 161),
  (44, 162),
  (44, 163),
  (44, 164),
  (44, 165),
  (44, 166),
  (44, 167),
  (45, 168),
  (45, 169),
  (45, 170),
  (45, 171),
  (45, 172),
  (45, 173),
  (46, 174),
  (46, 175),
  (46, 176),
  (47, 174),
  (47, 175),
  (47, 176);

ALTER TABLE tb_usuarios
  ADD karma_bom INT UNSIGNED DEFAULT 0 NOT NULL;
ALTER TABLE tb_usuarios
  ADD karma_mau INT UNSIGNED DEFAULT 0 NOT NULL;

CREATE TABLE tb_missoes_concluidas_dia
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  ilha          INT UNSIGNED                NOT NULL,
  quant         INT UNSIGNED DEFAULT 0      NOT NULL,
  CONSTRAINT tb_missoes_concluidas_dia_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE PROCEDURE `reseta_missao_dia`() NOT DETERMINISTIC MODIFIES SQL DATA SQL SECURITY DEFINER
  TRUNCATE TABLE tb_missoes_concluidas_dia;

CREATE EVENT `resets_missoes_dia`
  ON SCHEDULE EVERY 1 DAY
  STARTS '2017-06-18 00:00:00'
  ON COMPLETION NOT PRESERVE ENABLE DO
  BEGIN
    CALL reseta_missao_dia();
  END;

CREATE TABLE tb_tripulacao_buff
(
  id            BIGINT PRIMARY KEY    NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL NOT NULL,
  buff_id       INT                   NOT NULL,
  expiracao     INT                   NOT NULL,
  CONSTRAINT tb_tripulacao_buff_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_usuario_itens
  ADD novo TINYINT DEFAULT 1 NULL;

ALTER TABLE tb_titulos
  ADD compartilhavel TINYINT DEFAULT 0 NULL;

INSERT INTO tb_titulos (cod_titulo, bonus_atr, bonus_atr_quant, nome, compartilhavel) VALUES
  (35, 0, 0, 'O Caçador de Ladrões', 1),
  (36, 0, 0, 'O Justiceiro', 1),
  (37, 0, 0, 'O Palhaço', 1),
  (38, 0, 0, 'Unhas de Gato', 1),
  (39, 0, 0, 'O cozinheiro', 1),
  (40, 0, 0, 'Dentes de Serrote', 1),
  (41, 0, 0, 'O Sonhador', 1),
  (42, 0, 0, 'O Mentiroso', 1),
  (43, 0, 0, 'O Trapaceiro', 1),
  (44, 0, 0, 'G', 1),
  (45, 0, 0, 'O Catarrento', 1),
  (46, 0, 0, 'O Friorento', 1),
  (47, 0, 0, 'O Boxeador', 1),
  (48, 0, 0, 'O Musculoso', 1),
  (49, 0, 0, 'Pena de Pardal', 1),
  (50, 0, 0, 'O Rei do Pop', 1),
  (51, 0, 0, 'Punhos de Ferro', 1),
  (52, 0, 0, 'Mãos de tesoura', 1),
  (53, 0, 0, 'Gênio Tático', 1),
  (54, 0, 0, 'O Vião', 1),
  (55, 0, 0, 'O Bandido', 1),
  (56, 0, 0, 'Artista Marcial', 1),
  (57, 0, 0, 'O Explorador', 1),
  (58, 0, 0, 'O Assutador', 1),
  (59, 0, 0, 'O Homem de Ferro', 1),
  (60, 0, 0, 'O Domador de Leões', 1),
  (61, 0, 0, 'O Homem Macaco', 1),
  (62, 0, 0, 'O Poderoso Chefão', 1),
  (63, 0, 0, 'Envolvido em uma grande promessa', 1),
  (64, 0, 0, 'O Caçador de Recompensas', 1),
  (65, 0, 0, 'O Gigante', 1),
  (66, 0, 0, 'O Médico', 1),
  (67, 0, 0, 'Metamorfose Ambulante', 1),
  (68, 0, 0, 'O Grande', 1),
  (69, 0, 0, 'O Impaciente', 1),
  (70, 0, 0, 'O Rebelde', 1),
  (71, 0, 0, 'O Impiedoso', 1),
  (72, 0, 0, 'O Verdadeiro', 1),
  (73, 0, 0, 'A Raposa', 1),
  (74, 0, 0, 'O Cirurgião', 1),
  (75, 0, 0, 'O Carpinteiro', 1),
  (76, 0, 0, 'Da Pior Geração', 1),
  (77, 0, 0, 'O Anti-Tenryuubito', 1),
  (78, 0, 0, 'Choque Térmico', 1),
  (79, 0, 0, 'Sopro Congelante', 1),
  (80, 0, 0, 'Senhor dos Raios', 1),
  (81, 0, 0, 'Chefe de Laftel', 1);

INSERT INTO tb_realizacoes (cod_realizacao, tipo, categoria, nome, descricao, pontos, titulo)
VALUES
  (232, 0, 4, 'Chefe de Ilha Dawn', 'Derrotar o chefe de Ilha Dawn', 15, 35),
  (233, 0, 4, 'Chefe de Shells Town', 'Derrotar o chefe de Shells Town', 15, 36),
  (234, 0, 4, 'Chefe de Orange Town', 'Derrotar o chefe de Orange Town', 15, 37),
  (235, 0, 4, 'Chefe de Vila Syrup', 'Derrotar o chefe de Vila Syrup', 15, 38),
  (236, 0, 4, 'Chefe de Baratie', 'Derrotar o chefe de Baratie', 15, 39),
  (237, 0, 4, 'Chefe de Ilha Cocoyashi', 'Derrotar o chefe de Ilha Cocoyashi', 15, 40),
  (238, 0, 4, 'Chefe de Loguetown', 'Derrotar o chefe de Loguetown', 15, 41),
  (239, 0, 4, 'Chefe de Lvneel Kingdom', 'Derrotar o chefe de Lvneel Kingdom', 15, 42),
  (240, 0, 4, 'Chefe de Burlywood Town', 'Derrotar o chefe de Burlywood Town', 15, 43),
  (241, 0, 4, 'Chefe de GoldenRod Town', 'Derrotar o chefe de GoldenRod Town', 15, 44),
  (242, 0, 4, 'Chefe de Oubliette Town', 'Derrotar o chefe de Oubliette Town', 15, 45),
  (243, 0, 4, 'Chefe de Vila Whitewood', 'Derrotar o chefe de Vila Whitewood', 15, 46),
  (244, 0, 4, 'Chefe de Ilha North Coral', 'Derrotar o chefe de Ilha North Coral', 15, 47),
  (245, 0, 4, 'Chefe de Cartigen', 'Derrotar o chefe de Cartigen', 15, 48),
  (246, 0, 4, 'Chefe de Baterilla', 'Derrotar o chefe de Baterilla', 15, 49),
  (247, 0, 4, 'Chefe de Zozu Town', 'Derrotar o chefe de Zozu Town', 15, 50),
  (248, 0, 4, 'Chefe de Karatê island', 'Derrotar o chefe de Karatê island', 15, 51),
  (249, 0, 4, 'Chefe de Avalien Town', 'Derrotar o chefe de Avalien Town', 15, 52),
  (250, 0, 4, 'Chefe de Torino', 'Derrotar o chefe de Torino', 15, 53),
  (251, 0, 4, 'Chefe de Cinturão das luas', 'Derrotar o chefe de Cinturão das luas', 15, 54),
  (252, 0, 4, 'Chefe de Kimotsu Town', 'Derrotar o chefe de Kimotsu Town', 15, 55),
  (253, 0, 4, 'Chefe de Ilusia Kingdom', 'Derrotar o chefe de Ilusia Kingdom', 15, 56),
  (254, 0, 4, 'Chefe de Ohara', 'Derrotar o chefe de Ohara', 15, 57),
  (255, 0, 4, 'Chefe de Ilha Toroa', 'Derrotar o chefe de Ilha Toroa', 15, 58),
  (256, 0, 4, 'Chefe de Las Camp', 'Derrotar o chefe de Las Camp', 15, 59),
  (257, 0, 4, 'Chefe de Kima Town', 'Derrotar o chefe de Kima Town', 15, 60),
  (258, 0, 4, 'Chefe de Jumbo Town', 'Derrotar o chefe de Jumbo Town', 15, 61),
  (259, 0, 4, 'Chefe de Ilha Kagero', 'Derrotar o chefe de Ilha Kagero', 15, 62),
  (260, 0, 4, 'Chefe de Farol', 'Derrotar o chefe de Farol', 15, 63),
  (261, 0, 4, 'Chefe de Whiskey peaks', 'Derrotar o chefe de Whiskey peaks', 15, 64),
  (262, 0, 4, 'Chefe de Litle Garden', 'Derrotar o chefe de Litle Garden', 15, 65),
  (263, 0, 4, 'Chefe de Drum', 'Derrotar o chefe de Drum', 15, 66),
  (264, 0, 4, 'Chefe de Rainbase', 'Derrotar o chefe de Rainbase', 15, 67),
  (265, 0, 4, 'Chefe de Yuba', 'Derrotar o chefe de Yuba', 15, 68),
  (266, 0, 4, 'Chefe de Alubarna', 'Derrotar o chefe de Alubarna', 15, 69),
  (267, 0, 4, 'Chefe de Nanohana', 'Derrotar o chefe de Nanohana', 15, 70),
  (268, 0, 4, 'Chefe de MockTown', 'Derrotar o chefe de MockTown', 15, 71),
  (269, 0, 4, 'Chefe de Cricket House', 'Derrotar o chefe de Cricket House', 15, 72),
  (270, 0, 4, 'Chefe de Long Ring Long Land', 'Derrotar o chefe de Long Ring Long Land', 15, 73),
  (271, 0, 4, 'Chefe de Triller bark', 'Derrotar o chefe de Triller bark', 15, 74),
  (272, 0, 4, 'Chefe de Water 7', 'Derrotar o chefe de Water 7', 15, 75),
  (273, 0, 4, 'Chefe de Arquipélago Sabaody', 'Derrotar o chefe de Arquipélago Sabaody', 15, 76),
  (274, 0, 4, 'Chefe de Mariejois', 'Derrotar o chefe de Mariejois', 15, 77),
  (275, 0, 4, 'Chefe de Punk Hazard', 'Derrotar o chefe de Punk Hazard', 15, 78),
  (276, 0, 4, 'Chefe de Ilha Yukiryu', 'Derrotar o chefe de Ilha Yukiryu', 15, 79),
  (277, 0, 4, 'Chefe de Ilha Raijin', 'Derrotar o chefe de Ilha Raijin', 15, 80),
  (278, 0, 4, 'Chefe de Laftel', 'Derrotar o chefe de Laftel', 15, 81);

ALTER TABLE tb_usuarios
  ADD tempo_missao INT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_combate_log
  ADD relatorio LONGTEXT NULL;

CREATE TABLE tb_combate_log_npc
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  rdm_id        INT UNSIGNED                NOT NULL,
  relatorio     LONGTEXT                    NOT NULL,
  data          TIMESTAMP                            DEFAULT current_timestamp,
  CONSTRAINT tb_combate_log_npc_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

ALTER TABLE tb_combate_npc
  ADD mira INT DEFAULT 0 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (48);