ALTER TABLE tb_combate_personagens
  ADD medico_usado TINYINT DEFAULT 0 NULL;

ALTER TABLE tb_skil_atk
  ADD maestria TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_skil_buff
  ADD maestria TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_skil_passiva
  ADD maestria TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_skil_atk
  ADD requisito_maestria INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_skil_buff
  ADD requisito_maestria INT UNSIGNED DEFAULT 0 NULL;
ALTER TABLE tb_skil_passiva
  ADD requisito_maestria INT UNSIGNED DEFAULT 0 NULL;

ALTER TABLE tb_personagens
  ADD maestria INT UNSIGNED DEFAULT 0 NULL;

# Maestria lvl 1
INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 25,
  requisito_lvl       = 1,
  requisito_classe    = 1,
  alcance             = 7,
  area                = 4,
  consumo             = 10,
  dano                = 1,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 25,
  requisito_lvl       = 1,
  requisito_classe    = 2,
  alcance             = 7,
  area                = 4,
  consumo             = 10,
  dano                = 1,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 25,
  requisito_lvl       = 1,
  requisito_classe    = 3,
  alcance             = 7,
  area                = 4,
  consumo             = 10,
  dano                = 1,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 2
INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 50,
  requisito_lvl       = 10,
  requisito_classe    = 1,
  alcance             = 7,
  area                = 4,
  consumo             = 28,
  dano                = 4,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 50,
  requisito_lvl       = 10,
  requisito_classe    = 2,
  alcance             = 7,
  area                = 4,
  consumo             = 28,
  dano                = 4,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_atk
SET
  maestria            = 1,
  requisito_maestria  = 50,
  requisito_lvl       = 10,
  requisito_classe    = 3,
  alcance             = 7,
  area                = 4,
  consumo             = 28,
  dano                = 4,
  espera              = 1,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 3
INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 100,
  requisito_lvl       = 20,
  requisito_classe    = 1,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 2,
  bonus_atr_qnt       = 75,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 100,
  requisito_lvl       = 20,
  requisito_classe    = 2,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 2,
  bonus_atr_qnt       = 75,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 100,
  requisito_lvl       = 20,
  requisito_classe    = 3,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 2,
  bonus_atr_qnt       = 75,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 4
INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 200,
  requisito_lvl       = 25,
  requisito_classe    = 1,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 5,
  bonus_atr_qnt       = 80,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 200,
  requisito_lvl       = 25,
  requisito_classe    = 2,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 5,
  bonus_atr_qnt       = 80,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 200,
  requisito_lvl       = 25,
  requisito_classe    = 3,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 5,
  bonus_atr_qnt       = 80,
  duracao             = 3,
  espera              = 6,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 5
INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 500,
  requisito_lvl       = 30,
  requisito_classe    = 1,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 1,
  bonus_atr_qnt       = 85,
  duracao             = 4,
  espera              = 8,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 500,
  requisito_lvl       = 30,
  requisito_classe    = 2,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 1,
  bonus_atr_qnt       = 85,
  duracao             = 4,
  espera              = 8,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_buff
SET
  maestria            = 1,
  requisito_maestria  = 500,
  requisito_lvl       = 30,
  requisito_classe    = 3,
  alcance             = 1,
  area                = 1,
  consumo             = 100,
  bonus_atr           = 1,
  bonus_atr_qnt       = 85,
  duracao             = 4,
  espera              = 8,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 6
INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 1000,
  requisito_lvl       = 35,
  requisito_classe    = 1,
  bonus_atr           = 1,
  bonus_atr_qnt       = 16,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 1000,
  requisito_lvl       = 35,
  requisito_classe    = 2,
  bonus_atr           = 2,
  bonus_atr_qnt       = 16,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 1000,
  requisito_lvl       = 35,
  requisito_classe    = 3,
  bonus_atr           = 5,
  bonus_atr_qnt       = 16,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 7
INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 2000,
  requisito_lvl       = 40,
  requisito_classe    = 1,
  bonus_atr           = 1,
  bonus_atr_qnt       = 18,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 2000,
  requisito_lvl       = 40,
  requisito_classe    = 2,
  bonus_atr           = 2,
  bonus_atr_qnt       = 18,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 2000,
  requisito_lvl       = 40,
  requisito_classe    = 3,
  bonus_atr           = 5,
  bonus_atr_qnt       = 18,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 8
INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 3000,
  requisito_lvl       = 45,
  requisito_classe    = 1,
  bonus_atr           = 1,
  bonus_atr_qnt       = 20,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 3000,
  requisito_lvl       = 45,
  requisito_classe    = 2,
  bonus_atr           = 2,
  bonus_atr_qnt       = 20,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 3000,
  requisito_lvl       = 45,
  requisito_classe    = 3,
  bonus_atr           = 5,
  bonus_atr_qnt       = 20,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

# Maestria lvl 9
INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 5000,
  requisito_lvl       = 50,
  requisito_classe    = 1,
  bonus_atr           = 1,
  bonus_atr_qnt       = 22,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 5000,
  requisito_lvl       = 50,
  requisito_classe    = 2,
  bonus_atr           = 2,
  bonus_atr_qnt       = 22,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

INSERT INTO tb_skil_passiva
SET
  maestria            = 1,
  requisito_maestria  = 5000,
  requisito_lvl       = 50,
  requisito_classe    = 3,
  bonus_atr           = 5,
  bonus_atr_qnt       = 22,
  requisito_atr_1     = 0,
  requisito_atr_1_qnt = 0,
  requisito_atr_2     = 0,
  requisito_atr_2_qnt = 0,
  requisito_berries   = 0,
  requisito_prof      = 0,
  categoria           = 0;

ALTER TABLE tb_item_navio_casco
  ADD kairouseki TINYINT DEFAULT 0 NULL;
ALTER TABLE tb_usuario_navio
  ADD ultimo_disparo_sofrido BIGINT DEFAULT 0 NULL;
DELETE FROM tb_mapa
WHERE ilha = 0;
UPDATE tb_mapa
SET x = 428, y = 31
WHERE ilha = 1;
UPDATE tb_mapa
SET x = 373, y = 30
WHERE ilha = 2;
UPDATE tb_mapa
SET x = 352, y = 52
WHERE ilha = 3;
UPDATE tb_mapa
SET x = 368, y = 76
WHERE ilha = 4;
UPDATE tb_mapa
SET x = 335, y = 48
WHERE ilha = 5;
UPDATE tb_mapa
SET x = 331, y = 19
WHERE ilha = 6;
UPDATE tb_mapa
SET x = 309, y = 71
WHERE ilha = 7;
UPDATE tb_mapa
SET x = 70, y = 51
WHERE ilha = 8;
UPDATE tb_mapa
SET x = 107, y = 25
WHERE ilha = 9;
UPDATE tb_mapa
SET x = 124, y = 26
WHERE ilha = 10;
UPDATE tb_mapa
SET x = 170, y = 31
WHERE ilha = 11;
UPDATE tb_mapa
SET x = 149, y = 48
WHERE ilha = 12;
UPDATE tb_mapa
SET x = 130, y = 70
WHERE ilha = 13;
UPDATE tb_mapa
SET x = 144, y = 85
WHERE ilha = 14;
UPDATE tb_mapa
SET x = 424, y = 341
WHERE ilha = 15;
UPDATE tb_mapa
SET x = 415, y = 311
WHERE ilha = 16;
UPDATE tb_mapa
SET x = 384, y = 294
WHERE ilha = 17;
UPDATE tb_mapa
SET x = 353, y = 318
WHERE ilha = 18;
UPDATE tb_mapa
SET x = 336, y = 286
WHERE ilha = 19;
UPDATE tb_mapa
SET x = 304, y = 318
WHERE ilha = 20;
UPDATE tb_mapa
SET x = 289, y = 292
WHERE ilha = 21;
UPDATE tb_mapa
SET x = 35, y = 337
WHERE ilha = 22;
UPDATE tb_mapa
SET x = 45, y = 296
WHERE ilha = 23;
UPDATE tb_mapa
SET x = 81, y = 290
WHERE ilha = 24;
UPDATE tb_mapa
SET x = 104, y = 308
WHERE ilha = 25;
UPDATE tb_mapa
SET x = 127, y = 329
WHERE ilha = 26;
UPDATE tb_mapa
SET x = 166, y = 325
WHERE ilha = 27;
UPDATE tb_mapa
SET x = 157, y = 289
WHERE ilha = 28;
UPDATE tb_mapa
SET x = 281, y = 177
WHERE ilha = 29;
UPDATE tb_mapa
SET x = 292, y = 207
WHERE ilha = 30;
UPDATE tb_mapa
SET x = 306, y = 219
WHERE ilha = 31;
UPDATE tb_mapa
SET x = 323, y = 229
WHERE ilha = 32;
UPDATE tb_mapa
SET x = 346, y = 229
WHERE ilha = 33;
UPDATE tb_mapa
SET x = 352, y = 229
WHERE ilha = 34;
UPDATE tb_mapa
SET x = 347, y = 225
WHERE ilha = 35;
UPDATE tb_mapa
SET x = 352, y = 225
WHERE ilha = 36;
UPDATE tb_mapa
SET x = 375, y = 234
WHERE ilha = 37;
UPDATE tb_mapa
SET x = 377, y = 236
WHERE ilha = 38;
UPDATE tb_mapa
SET x = 395, y = 234
WHERE ilha = 39;
UPDATE tb_mapa
SET x = 424, y = 215
WHERE ilha = 40;
UPDATE tb_mapa
SET x = 442, y = 197
WHERE ilha = 41;
UPDATE tb_mapa
SET x = 446, y = 179
WHERE ilha = 42;
UPDATE tb_mapa
SET x = 453, y = 192
WHERE ilha = 43;
UPDATE tb_mapa
SET x = 34, y = 164
WHERE ilha = 44;
UPDATE tb_mapa
SET x = 38, y = 119
WHERE ilha = 45;
UPDATE tb_mapa
SET x = 38, y = 190
WHERE ilha = 46;
UPDATE tb_mapa
SET x = 181, y = 181
WHERE ilha = 47;
UPDATE tb_mapa
SET x = 430, y = 258
WHERE ilha = 101;

CREATE TABLE tb_mapa_rdm
(
  id     BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  x      INT UNSIGNED                NOT NULL,
  y      INT UNSIGNED                NOT NULL,
  rdm_id INT UNSIGNED                NOT NULL
);

INSERT INTO tb_mapa_rdm (x, y, rdm_id) VALUES
  (290, 130, 9),
  (430, 138, 10),
  (91, 192, 12),
  (109, 229, 13),
  (128, 183, 14),

  (447, 250, 11),
  (108, 171, 69),
  (166, 199, 70),
  (162, 240, 71),
  (133, 117, 72);

DELETE FROM tb_usuario_itens
WHERE tipo_item = 18;
TRUNCATE tb_item_missao;

UPDATE tb_usuarios SET x = 428, y = 31 WHERE coord_x_navio >=1 and coord_x_navio <= 100 and coord_y_navio >= 1 and coord_y_navio <= 40;
UPDATE tb_usuarios SET x = 70, y = 51 WHERE coord_x_navio >=101 and coord_x_navio <= 200 and coord_y_navio >= 1 and coord_y_navio <= 40;
UPDATE tb_usuarios SET x = 424, y = 341 WHERE coord_x_navio >=1 and coord_x_navio <= 100 and coord_y_navio >= 61 and coord_y_navio <= 100;
UPDATE tb_usuarios SET x = 35, y = 337 WHERE coord_x_navio >=101 and coord_x_navio <= 200 and coord_y_navio >= 61 and coord_y_navio <= 100;
UPDATE tb_usuarios SET x = 281, y = 177 WHERE coord_x_navio >=1 and coord_x_navio <= 100 and coord_y_navio >= 41 and coord_y_navio <= 60;
UPDATE tb_usuarios SET x = 34, y = 164 WHERE coord_x_navio >=101 and coord_x_navio <= 200 and coord_y_navio >= 41 and coord_y_navio <= 60;

INSERT INTO tb_mapa (x, y, ilha) VALUE (421, 239, 102);
INSERT INTO tb_ilha_recurso (ilha, recurso_gerado) VALUE (102, 0);
ALTER TABLE tb_usuarios ADD campanha_enies_lobby INT UNSIGNED NULL;
ALTER TABLE tb_personagens ADD haki_lvl_ultima_era INT UNSIGNED NULL;

ALTER TABLE tb_mapa_rdm ADD ameaca INT DEFAULT 1 NULL;

INSERT INTO tb_migrations (cod_migration) VALUE (106);