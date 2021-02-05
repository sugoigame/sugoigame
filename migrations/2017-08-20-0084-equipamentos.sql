ALTER TABLE tb_combinacoes_forja
  ADD visivel INT DEFAULT 1 NULL;
ALTER TABLE tb_combinacoes_carpinteiro
  ADD visivel INT DEFAULT 1 NULL;
ALTER TABLE tb_combinacoes_artesao
  ADD visivel INT DEFAULT 1 NULL;

/* Essencias */
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (334, 'Fragmento de Essência Branca',
   'Essência capaz de imbuir equipamentos com grandes poderes. Leve 2 Fragmentos até a forja do navio para obter uma essência maior.',
   0, 0, 0, 0, 10000, 'NULL', 'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (329, 'Essência Branca', 'Essência capaz de imbuir equipamentos com grandes poderes.', 0, 0, 0, 0, 20000, 'NULL',
   'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (333, 'Fragmento de Essência Verde',
   'Essência capaz de imbuir equipamentos com grandes poderes. Leve 2 Fragmentos até a forja do navio para obter uma essência maior.',
   0, 0, 0, 0, 10000, 'NULL', 'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (330, 'Essência Verde', 'Essência capaz de imbuir equipamentos com grandes poderes.', 0, 0, 0, 0, 20000, 'NULL',
   'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (332, 'Fragmento de Essência Azul',
   'Essência capaz de imbuir equipamentos com grandes poderes. Leve 2 Fragmentos até a forja do navio para obter uma essência maior.',
   0, 0, 0, 0, 10000, 'NULL', 'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (331, 'Essência Azul', 'Essência capaz de imbuir equipamentos com grandes poderes.', 0, 0, 0, 0, 20000, 'NULL',
   'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (335, 'Estilhaço de Essência Branca',
   'Essência capaz de imbuir equipamentos com grandes poderes.  Leve 4 Estilhaços até a forja do navio para obter um fragmento de essência.',
   0, 0, 0, 0, 2500, 'NULL',
   'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (337, 'Estilhaço de Essência Verde',
   'Essência capaz de imbuir equipamentos com grandes poderes.  Leve 4 Estilhaços até a forja do navio para obter um fragmento de essência.',
   0, 0, 0, 0, 2500, 'NULL',
   'jpg');
INSERT INTO tb_item_reagents (img, nome, descricao, mergulho, zona, mining, madeira, preco, method, img_format) VALUES
  (336, 'Estilhaço de Essência Azul',
   'Essência capaz de imbuir equipamentos com grandes poderes.  Leve 4 Estilhaços até a forja do navio para obter um fragmento de essência.',
   0, 0, 0, 0, 2500, 'NULL',
   'jpg');

INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 152, 15, 2, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 153, 15, 1, 1);
INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 154, 15, 2, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 155, 15, 1, 1);
INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 156, 15, 2, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 157, 15, 1, 1);
INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 158, 15, 4, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 152, 15, 1, 1);
INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 159, 15, 4, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 154, 15, 1, 1);
INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES
  (0, 160, 15, 4, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
                                                                                 DEFAULT, DEFAULT, DEFAULT, DEFAULT,
   DEFAULT, DEFAULT, 1, 156, 15, 1, 1);


CREATE TABLE tb_combinacoes_forja_conhecidas
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  combinacao_id INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_combinacoes_forja_conhecidas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb__forja_conhecidas_tb_forja_cod_receita_fk FOREIGN KEY (combinacao_id) REFERENCES tb_combinacoes_forja (cod_receita)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE tb_combinacoes_artesao_conhecidas
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  combinacao_id INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_combinacoes_artesao_conhecidas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb__artesao_conhecidas_tb_artesao_cod_receita_fk FOREIGN KEY (combinacao_id) REFERENCES tb_combinacoes_artesao (cod_receita)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE tb_combinacoes_carpinteiro_conhecidas
(
  id            BIGINT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL       NOT NULL,
  combinacao_id INT UNSIGNED                NOT NULL,
  CONSTRAINT tb_combinacoes_carpinteiro_conhecidas_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb__carpinteiro_conhecidas_tb_carpinteiro_cod_receita_fk FOREIGN KEY (combinacao_id) REFERENCES tb_combinacoes_carpinteiro (cod_receita)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE tb_pvp_imune
(
  id            BIGINT UNSIGNED PRIMARY KEY         NOT NULL AUTO_INCREMENT,
  tripulacao_id INT UNSIGNED ZEROFILL               NOT NULL,
  adversario_id INT UNSIGNED ZEROFILL               NOT NULL,
  horario       TIMESTAMP DEFAULT current_timestamp NOT NULL,
  CONSTRAINT tb_pvp_imune_tb_usuarios_id_fk FOREIGN KEY (tripulacao_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT tb_pvp_imune_tb_usuarios_adversario_id_fk FOREIGN KEY (adversario_id) REFERENCES tb_usuarios (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

/* categoria  = cor do equipamento 1 = cinza, 2 = branco, 3 = verde, 4 = azul e 5 = preto */
/* requisito é a classe que irá equipar */


/*** ARMA DE ATIRADOR ***/

/* UMA MÃO */
INSERT INTO tb_equipamentos
SET img      = 44, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Pistola previsível',
  descricao  = 'Essa arma dispara uma bala ao puxar seu gatilho, apenas quando está carregada!', lvl = 50,
  treino_max = 1000, slot = 7, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 44, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Pistola de certeza imprevisível',
  descricao = 'Quando o usuário atacar, ele poderá causar dano ao alvo.', lvl = 50, treino_max = 1000, slot = 7,
  requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 45, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Pistola de cowboy',
  descricao = 'Sempre que o usuário dessa arma acerta um tiro no alvo, ele dispara mais dois tiros par cima apenas por divesão!!',
  lvl       = 50, treino_max = 1000, slot = 7, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 44, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Pistola do menosprezo',
  descricao = 'Essa arma gera no usuário um forte senso de superioridade, querendo menosprezar qualquer um que cruze seu caminho.',
  lvl       = 50, treino_max = 1000, slot = 7, requisito = 3;

/* SEGUNDA MÃO */
INSERT INTO tb_equipamentos
SET img      = 41, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Arma do destino certo',
  descricao  = 'Essa arma concede ao usuário a capacidade de aceitar o fato de que algum dia ele irá morrer.', lvl = 50,
  treino_max = 1000, slot = 8, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 41, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Pistola da precisão',
  descricao = 'O usuário desta arma só erra um tiro quando o alvo esquiva.', lvl = 50, treino_max = 1000, slot = 8,
  requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 43, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Pistola da sonoplastia inadequada',
  descricao = 'Essa arma emite um som de uma espada cortando toda vez que dá um tiro.', lvl = 50, treino_max = 1000,
  slot      = 8, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 43, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Pistola misteriosa',
  descricao = 'Jamais alguém conseguiu ler qual era a verdadeira descrição dessa arma, isso aqui é só embromação mesmo.',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 3;

/* DUAS MÃOS */
INSERT INTO tb_equipamentos
SET img     = 46, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Arma da precisão duvidosa',
  descricao = 'Essa arma faz o usuário ter dúvidas se acertou o ultmo ataque.', lvl = 50, treino_max = 1000, slot = 10,
  requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 50, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Arma do atirador de elite',
  descricao = 'Essa arma permite o usuário atirar em a necessidade de mirar. Ao ser utilizada, todos em volta o elegiam e o parabenizam pelo feito.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 58, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Arma de fogo da precisão absoluta',
  descricao = 'Essa arma faz o usuário pensar que teve êxito em todos os ataques que o alvo esquivou, sem exceção!!',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 3;
INSERT INTO tb_equipamentos
SET img     = 51, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Arma da arrogancia ilimitada',
  descricao = 'Essa arma é tão forte que causa um forte senso de superioridade no usuário, querendo isultar qualquer um que lhe direcione o olhar.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 3;


/*** ARMA DE ESPADACHIM ***/

/* UMA MÃO */
INSERT INTO tb_equipamentos
SET img     = 170, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Katana fatiadora de pudim',
  descricao = 'Qualquer um que essa arma acerte, receberá dano.', lvl = 50, treino_max = 1000, slot = 9, requisito = 1;
INSERT INTO tb_equipamentos
SET img     = 237, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Katana da sonoplastia inadequada',
  descricao = 'Essa Katana emite o som de um tiro toda vez que fatia alguma coisa.', lvl = 50, treino_max = 1000,
  slot      = 9, requisito = 1;
INSERT INTO tb_equipamentos
SET img     = 244, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Katana estremamente afiada',
  descricao = 'Quando equipada, essa katana concede ao usuário um vontade tremenda de cortar melancias ao meio.',
  lvl       = 50, treino_max = 1000, slot = 9, requisito = 1;
INSERT INTO tb_equipamentos
SET img     = 248, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Lâmina da Mortalidade',
  descricao = 'Uma espada longa que, quando tirada da bainha, força o usuário a contemplar a própria mortalidade.',
  lvl       = 50, treino_max = 1000, slot = 9, requisito = 1;

/* DUAS MÃOS */
INSERT INTO tb_equipamentos
SET img     = 220, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Lâmina desafiada',
  descricao = 'Essa lâmina perde o fio mais rápido que o usuário consegue afiar.', lvl = 50, treino_max = 1000,
  slot      = 10, requisito = 1;
INSERT INTO tb_equipamentos
SET img     = 244, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Katana da destino inevitável',
  descricao = 'Essa espada concede ao usuário a capacidade de aceitar o fato de que algum dia ele irá morrer.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 1;
INSERT INTO tb_equipamentos
SET img     = 382, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Espada anti-magia',
  descricao = 'Essa espada provavelmente teria algum efeito mágico muito poderoso se não fosse por toda a anti-magia dentro dela.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 1;

/* Kokuto Yoru Espada do mhawk - já existe no Banco de dados */
/* INSERT INTO tb_equipamentos SET img = 1, cat_dano = 59, b_1 = 1, b_2 = 2, categoria = 6, nome = '', descricao = '', lvl = 50, treino_max = 1000, slot = 10, requisito = 1; */



/*** ARMA DE LUTADOR ***/

/* UMA MÃO */
INSERT INTO tb_equipamentos
SET img     = 340, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Machado da inversão de dominancia',
  descricao = 'Esse machado, quando equipado, inverte a mão dominante do usuário.', lvl = 50, treino_max = 1000,
  slot      = 7, requisito = 2;
INSERT INTO tb_equipamentos
SET img      = 354, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Garras afiadíssimas',
  descricao  = 'Os alvos dos ataques dessa arma geralmente gritam de dor antes mesmo de o ataque acerta-lo.', lvl = 50,
  treino_max = 1000, slot = 7, requisito = 2;
INSERT INTO tb_equipamentos
SET img      = 349, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Gancho negro',
  descricao  = 'Esse gancho deixa o usuário 5 vezes mais temido por pessoas que tem medo de gancho.', lvl = 50,
  treino_max = 1000, slot = 7, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 161, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Gancho dourado',
  descricao = 'Esse gancho tem chance aumentada de se prender nos piores lugares...', lvl = 50, treino_max = 1000,
  slot      = 7, requisito = 2;

/* SEGUNDA MÃO */
INSERT INTO tb_equipamentos
SET img      = 31, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Machado da certeza',
  descricao  = 'Quando esse machado acerta o alvo, este certamente receberá dano a menos que ele esquive.', lvl = 50,
  treino_max = 1000, slot = 8, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 354, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Garras da fúria incontrolável',
  descricao = 'Essa arma faz o usuário ser tomado por uma fúria incontrolável por seus inimigos, tendo a vontade de atacá-los imediatamente, até mesmo causando dano a eles.',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 349, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Gancho da elegancia inigualável',
  descricao = 'Essa arma aplica no usuário uma vontade incontrolável de fazer uma pose magnífica toda vez que atinge um inimigo.',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 161, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Gancho anti-magia',
  descricao = 'Esse gancho provavelmente teria algum efeito mágico muito poderoso se não fosse por toda a anti-magia dentro dele.',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 2;

/* ESCUDO */
INSERT INTO tb_equipamentos
SET img      = 16, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Escudo bloqueante',
  descricao  = 'Esse escudo tem a chance de bloquear um ataque igual a chance de bloqueio do usuário.', lvl = 50,
  treino_max = 1000, slot = 8, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 57, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Escudo da ignoração',
  descricao  = 'Esse escudo dá o poder do usuário permanece invisível enquanto não estiver sendo observado.', lvl = 50,
  treino_max = 1000, slot = 8, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 380, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Escudo muralha',
  descricao = 'O portador desse escudo tem a necessidade de gritar "SHIELD WAAAALL!!" toda vez que bloqueia um ataque.',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 7, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Escudo da certeza duvidosa',
  descricao = 'Esse escudo faz o usuário ter dúvidas se bloqueou o ultmo ataque, apesar de ter certeza que bloqueou... ou será o contrário?',
  lvl       = 50, treino_max = 1000, slot = 8, requisito = 0;

/* DUAS MÃOS */
INSERT INTO tb_equipamentos
SET img     = 32, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Machado do ataque certo',
  descricao = 'Esse machado não impede que o usuário ataque.', lvl = 50, treino_max = 1000, slot = 10, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 29, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Machado duplo da escolhido',
  descricao = 'O portador desse machado tem a necessidade de gritar "VOCÊ NÂO ESTÁ PREPARADO!!" toda vez que desfere um ataque.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 162, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Tacape aterrorizante',
  descricao = 'Esse tacape, quando utilizado em combate, emite uma melodia faz o usuário ser tomado por uma sensação de terror. Ao terminar a melodia, a sensação de terror passa, porém um suave vento começa a soprar do leste…',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 2;
INSERT INTO tb_equipamentos
SET img     = 366, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Martelo FILHO DA P***',
  descricao = 'Essa marreta, ao ser usada em um ataque, possui uma chance de 25% de acertar o dedão do usuário.',
  lvl       = 50, treino_max = 1000, slot = 10, requisito = 2;


/*** EQUIPAMENTOS ***/

/*** CABEÇA ***/
INSERT INTO tb_equipamentos
SET img      = 144, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Chapéu do patife descontrolado',
  descricao  = 'Esse chapéu sempre "salta" da cabeça do usuário quando este está pra receer um golpe.', lvl = 50,
  treino_max = 1000, slot = 1, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 142, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Elmo da visão além do alcance',
  descricao = 'Esse elmo é capaz de ver tudo, mas se ele irá permitir que o usuário veja tudo isso também, ai depende dele.',
  lvl       = 50, treino_max = 1000, slot = 1, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 147, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Elmo da exaustão',
  descricao  = 'Esse elmo abafa totalmente a voz do usuário. Sério, ninguém conseguirá entender...', lvl = 50,
  treino_max = 1000, slot = 1, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 145, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Elmo do volume intermitente',
  descricao  = 'Esse elmo que torna o usuário incapaz de controlar o volume da própria voz.', lvl = 50,
  treino_max = 1000, slot = 1, requisito = 0;

/*** TORSO ***/
INSERT INTO tb_equipamentos
SET img     = 175, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Vestes da dúvida cruel',
  descricao = 'Branco e dourado ou preto e azul??', lvl = 50, treino_max = 1000, slot = 2, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 210, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Camisa da indecisão',
  descricao  = 'Essa camisa faz o usuário querer tirá-la quando coloca, mas querer colocá-la quando tira.', lvl = 50,
  treino_max = 1000, slot = 2, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 206, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Camisa de Schrödinger',
  descricao = 'O usuário, quando não está sendo observado de nenhuma forma, está tanto vivo quanto morto. Quando observado, o usuário imediatamente se torna vivo ou morto, com uma chance de 50% para cada.',
  lvl       = 50, treino_max = 1000, slot = 2, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 195, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Armadura da invisibilidade convincente',
  descricao = 'Essa armadura faz o usuário acreditar que está invisível, apesar de todos as evidência apontar o contrário.',
  lvl       = 50, treino_max = 1000, slot = 2, requisito = 0;

/*** CALÇA ***/
INSERT INTO tb_equipamentos
SET img     = 171, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Calça da Tímidez vergonhosa',
  descricao = 'Essa calça que desaparece quando diretamente observada.', lvl = 50, treino_max = 1000, slot = 3,
  requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 184, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Calça da velocidade',
  descricao = 'Essa calça dá a capacidade de o usuário dê cada passo com a metade da distância no na metade do tempo.',
  lvl       = 50, treino_max = 1000, slot = 3, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 226, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Calça da inteligência',
  descricao  = 'Essa calça faz o usuário aparentar ser mais inteligente do que é, mas só aparentar mesmo.', lvl = 50,
  treino_max = 1000, slot = 3, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 384, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Calça do linguajar primitivo',
  descricao = 'Essa calça faz quem estiver a equipado capaz de falar em terceira pessoa.', lvl = 50, treino_max = 1000,
  slot      = 3, requisito = 0;

/*** BOTA ***/
INSERT INTO tb_equipamentos
SET img     = 194, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Croques',
  descricao = 'Não importa o que digam, elas são muito confortaveis!!', lvl = 50, treino_max = 1000, slot = 4,
  requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 230, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Botas ilusórias',
  descricao = 'Essas botas, quando equipadas, fazem parecer que o usuário está andando para trás quando anda para frente.',
  lvl       = 50, treino_max = 1000, slot = 4, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 131, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Sapatilhas do salto inquietante',
  descricao = 'Essas botas permitem o usuário realizar saltos magníficos, porém o usuário é incapaz de andar ou correr, apenas pular.',
  lvl       = 50, treino_max = 1000, slot = 4, requisito = 0;
INSERT INTO tb_equipamentos
SET img      = 386, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Botas do passo largo',
  descricao  = 'Essas botas permitem que o usuário dê cada passo com o dobro da distância no dobro do tempo.', lvl = 50,
  treino_max = 1000, slot = 4, requisito = 0;

/*** LUVA ***/
INSERT INTO tb_equipamentos
SET img     = 73, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Luvas do espirro',
  descricao = 'Essa luva faz o usuário ter aquela sensação que está prestes a espirrar, mas o espirro nunca sai.',
  lvl       = 50, treino_max = 1000, slot = 5, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 180, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Luvas de crochê',
  descricao = 'Essa luva feita em crochê, enquanto equipada, proporciona ao usuário uma imensa saudade de sua Avó.',
  lvl       = 50, treino_max = 1000, slot = 5, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 72, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Luvas da sorte extrema',
  descricao = 'A luva é muito sortuda, o usuário não!', lvl = 50, treino_max = 1000, slot = 5, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 388, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Luvas da Fúria',
  descricao = 'Essa luva faz o usuário ser tomado por uma fúria incontrolável durante combate. Essa luva não produz nenhum efeito mecânico.',
  lvl       = 50, treino_max = 1000, slot = 5, requisito = 0;

/*** CAPA ***/
INSERT INTO tb_equipamentos
SET img     = 156, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 3, nome = 'Capa da invisibilidade perfeita',
  descricao = 'Quando equipada, essa capa fica invisível. Não o usuário, só a capa.', lvl = 50, treino_max = 1000,
  slot      = 6, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 153, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 4, nome = 'Capa da entrada triunfal',
  descricao = 'Essa capa concede ao usuário uma vontade imensa de fazer uma pose magnífica toda vez que entra em batalha ou é observado.',
  lvl       = 50, treino_max = 1000, slot = 6, requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 159, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 5, nome = 'Capa de se achar o foda',
  descricao = 'Essa capa balança ao vento, mesmo se não houver vento.', lvl = 50, treino_max = 1000, slot = 6,
  requisito = 0;
INSERT INTO tb_equipamentos
SET img     = 390, cat_dano = 1, b_1 = 1, b_2 = 2, categoria = 6, nome = 'Capa do poder insuparável',
  descricao = 'Essa capa libera uma aura mágica extremamente forte, porém é impossível determinar sua utilidade.',
  lvl       = 50, treino_max = 1000, slot = 6, requisito = 0;


UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 1 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 2, b_2 = 3
WHERE slot = 2 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 3, b_2 = 4
WHERE slot = 3 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 4, b_2 = 5
WHERE slot = 4 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 5, b_2 = 6
WHERE slot = 5 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 6, b_2 = 7
WHERE slot = 6 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 7 AND requisito = 2 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 6
WHERE slot = 7 AND requisito = 3 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 2, b_2 = 2
WHERE slot = 8 AND requisito = 0 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 2
WHERE slot = 8 AND requisito = 2 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 6
WHERE slot = 8 AND requisito = 3 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 1
WHERE slot = 9 AND requisito = 1 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 1
WHERE slot = 10 AND requisito = 1 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 3
WHERE slot = 10 AND requisito = 2 AND categoria >= 3;
UPDATE tb_equipamentos
SET b_1 = 1, b_2 = 5
WHERE slot = 10 AND requisito = 3 AND categoria >= 3;

UPDATE tb_equipamentos
SET treino_max = (categoria + 1) * 10
WHERE categoria >= 3;


INSERT INTO tb_combinacoes_forja (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES

  /*** ARMA DE ATIRADOR ***/

  /* UMA MÃO VERDE */
  /*EB, EV, LAço, TabuaMVelha, LPrata, TabuaMadeira*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '79', '15', '1', '0', '0',
   '0', '0', '0', '0', 11, 478, 14, 1, 0),

  /* SEGUNDA MÃO VERDE */
  /*EB, EV, LAço, TabuaMVelha, LCobre, TabuaMadeira*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '60', '15', '1', '79', '15', '1', '0', '0', '0', '0', '0', '0', 11, 482, 14, 1, 0),

  /* DUAS MÃOS VERDE */
  /*EB, EV, LAço, TabuaMVelha, LChumbo, TabuaMadeira*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '79', '15', '1', '0', '0', '0', '0', '0', '0', 11, 486, 14, 1, 0),


  /* UMA MÃO AZUL */
  /*EV, EA, LAço, TabuaMVelha, LPrata, TabuaMadeira, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '79', '15', '1', '74', '15', '1', '73', '15', '1', 11, 479, 14, 1, 0),

  /* SEGUNDA MÃO AZUL */
  /*EV, EA, LAço, TabuaMVelha, LCobre, TabuaMadeira, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '60', '15', '1', '79', '15', '1', '74', '15', '1', '73', '15', '1', 11, 483, 14, 1, 0),

  /* DUAS MÃOS AZUL */
  /*EV, EA, LAço, TabuaMVelha, LChumbo, TabuaMadeira, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '79', '15', '1', '74', '15', '1', '73', '15', '1', 11, 487, 14, 1, 0),


  /*** ARMA DE ESPADACHIM ***/

  /* UMA MÃO VERDE */
  /*EB, EV, LAço, TabuaMVelha, LPrata, LBronze*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '65', '15', '1', '0', '0', '0', '0', '0', '0', 11, 490, 14, 1, 0),

  /* DUAS MÃOS VERDE */
  /*EB, EV, LAço, TabuaMVelha, LChumbo, LBronze*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '65', '15', '1', '0', '0', '0', '0', '0', '0', 11, 494, 14, 1, 0),


  /* UMA MÃO AZUL */
  /*EV, EA, LAço, TabuaMVelha, LPrata, LBronze, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '65', '15', '1', '74', '15', '1', '73', '15', '1', 11, 491, 14, 1, 0),

  /* DUAS MÃOS AZUL */
  /*EV, EA,, LAço, TabuaMVelha, LChumbo, LBronze, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '65', '15', '1', '74', '15', '1', '73', '15', '1', 11, 495, 14, 1, 0),


  /*** ARMA DE LUTADOR ***/

  /* UMA MÃO VERDE */
  /*EB, EV, LAço, TabuaMVelha, LPrata, TabuaMVerde*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '76', '15', '1', '0', '0', '0', '0', '0', '0', 11, 497, 14, 1, 0),

  /* SEGUNDA MÃO VERDE */
  /*EB, EV, LAço, TabuaMVelha, LCobre, TabuaMVerde*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '60', '15', '1', '76', '15', '1', '0', '0',
   '0', '0', '0', '0', 11, 505, 14, 1, 0),

  /* DUAS MÃOS VERDE */
  /*EB, EV, LAço, TabuaMVelha, LChumbo, TabuaMVerde*/
  (0, '153', '15', '1', '155', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '76', '15', '1', '0', '0',
   '0', '0', '0', '0', 11, 509, 14, 1, 0),


  /* UMA MÃO AZUL */
  /*EV, EA, LAço, TabuaMVelha, LPrata, TabuaMVerde, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '68', '15', '1', '76', '15', '1', '74',
                                                                 '15', '1', '73', '15', '1', 11, 498, 14, 1, 0),

  /* SEGUNDA MÃO AZUL */
  /*EV, EA, LAço, TabuaMVelha, LCobre, TabuaMVerde, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '60', '15', '1', '76', '15', '1', '74',
                                                                 '15', '1', '73', '15', '1', 11, 506, 14, 1, 0),

  /* DUAS MÃOS AZUL */
  /*EV, EA, LAço, TabuaMVelha, LChumbo, TabuaMVerde, Vidro, Mercurio*/
  (0, '155', '15', '1', '157', '15', '1', '54', '15', '1', '82', '15', '1', '71', '15', '1', '76', '15', '1', '74',
                                                                 '15', '1', '73', '15', '1', 11, 510, 14, 1, 0);


INSERT INTO tb_combinacoes_artesao (aleatorio, `1`, `1_t`, `1_q`, `2`, `2_t`, `2_q`, `3`, `3_t`, `3_q`, `4`, `4_t`, `4_q`, `5`, `5_t`, `5_q`, `6`, `6_t`, `6_q`, `7`, `7_t`, `7_q`, `8`, `8_t`, `8_q`, lvl, cod, tipo, quant, visivel)
VALUES

  /*** EQUIPAMENTOS ***/

  /* CABEÇA VERDE */
  /*EB, EV, Osso quebrado, Presa amarela, Couro azul, Escama branca*/
  (0, '153', '15', '1', '155', '15', '1', '15', '15', '1', '21', '15', '1', '27', '15', '1', '33', '15', '1', '0', '0',
   '0', '0', '0', '0', 11, 513, 14, 1, 0),

  /* TORSO VERDE */
  /*EB, EV, Osso envelhecido, Presa, Couro preto, Escama amarela*/
  (0, '153', '15', '1', '155', '15', '1', '16', '15', '1', '20', '15', '1', '26', '15', '1', '31', '15', '1', '0', '0', '0', '0', '0', '0', 11, 517, 14, 1, 0),

  /* CALÇA VERDE */
  /*EB, EV, Osso seco, Presa vermelha, Couro branco, Escama preta*/
  (0, '153', '15', '1', '155', '15', '1', '17', '15', '1', '22', '15', '1', '25', '15', '1', '34', '15', '1', '0', '0', '0', '0', '0', '0', 11, 521, 14, 1, 0),

  /* BOTA VERDE */
  /*EB, EV, Osso, Presa verde, Couro vermelho, Escama verde*/
  (0, '153', '15', '1', '155', '15', '1', '18', '15', '1', '24', '15', '1', '28', '15', '1', '32', '15', '1', '0', '0', '0', '0', '0', '0', 11, 525, 14, 1, 0),

  /* LUVA VERDE */
  /*EB, EV, Osso de ótima qualidade, Presa azul, Couro marrom, Escama azul*/
  (0, '153', '15', '1', '155', '15', '1', '19', '15', '1', '23', '15', '1', '29', '15', '1', '30', '15', '1', '0', '0', '0', '0', '0', '0', 11, 529, 14, 1, 0),

  /* CAPA VERDE */
  /*EB, EV, Osso, Presa, Couro branco, Escama azul*/
  (0, '153', '15', '1', '155', '15', '1', '18', '15', '1', '20', '15', '1', '25', '15', '1', '30', '15', '1', '0', '0', '0', '0', '0', '0', 11, 533, 14, 1, 0),


  /* CABEÇA AZUL */
  /*EV, EA, Osso quebrado, Presa amarela, Couro azul, Escama branca, Tecido de algodão branco*/
  (0, '153', '15', '1', '155', '15', '1', '15', '15', '1', '21', '15', '1', '27', '15', '1', '33', '15', '1', '36', '15', '1', '0', '0', '0', 11, 514, 14, 1, 0),

  /* TORSO AZUL */
  /*EV, EA, Osso envelhecido, Presa, Couro preto, Escama amarela, Tecido de algodão preto*/
  (0, '153', '15', '1', '155', '15', '1', '16', '15', '1', '20', '15', '1', '26', '15', '1', '31', '15', '1', '38', '15', '1', '0', '0', '0', 11, 518, 14, 1, 0),

  /* CALÇA AZUL */
  /*EV, EA, Osso seco, Presa vermelha, Couro branco, Escama preta, Tecido de algodão azul claro*/
  (0, '153', '15', '1', '155', '15', '1', '17', '15', '1', '22', '15', '1', '25', '15', '1', '34', '15', '1', '40', '15', '1', '0', '0', '0', 11, 522, 14, 1, 0),

  /* BOTA AZUL */
  /*EV, EA, Osso, Presa verde, Couro vermelho, Escama verde, Tecido de algodão verde*/
  (0, '153', '15', '1', '155', '15', '1', '18', '15', '1', '24', '15', '1', '28', '15', '1', '32', '15', '1', '42', '15', '1', '0', '0', '0', 11, 526, 14, 1, 0),

  /* LUVA AZUL */
  /*EV, EA, Osso de ótima qualidade, Presa azul, Couro marrom, Escama azul, Tecido de algodão amarelo*/
  (0, '153', '15', '1', '155', '15', '1', '19', '15', '1', '23', '15', '1', '29', '15', '1', '30', '15', '1', '44', '15', '1', '0', '0', '0', 11, 530, 14, 1, 0),

  /* CAPA AZUL */
  /*EV, EA, Osso, Presa, Couro branco, Escama azul, Tecido de algodão vermelho*/
  (0, '153', '15', '1', '155', '15', '1', '18', '15', '1', '20', '15', '1', '25', '15', '1', '30', '15', '1', '46',
                                                                 '15', '1', '0', '0', '0', 11, 534, 14, 1, 0);


INSERT INTO tb_migrations (cod_migration) VALUE (84);