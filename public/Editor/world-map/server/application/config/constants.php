<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Facções
|--------------------------------------------------------------------------
|
| Facções
|
*/
define('FACCAO_MARINHEIRO', 0);
define('FACCAO_PIRATA', 1);

/*
|--------------------------------------------------------------------------
| Cache
|--------------------------------------------------------------------------
|
| IDS de da tabela Cache
|
*/
define('CACHE_ID_HOME', 'HOME');

/*
|--------------------------------------------------------------------------
| Game States
|--------------------------------------------------------------------------
|
| Constantes que servirão para aplicar query maks nos estados do jogador
|
*/
define('STATE_CONECTADO_ON',    1<<0);
define('STATE_CONECTADO_OFF',   1<<1);
define('STATE_TRIPULACAO_ON',   1<<2);
define('STATE_TRIPULACAO_OFF',  1<<3);
define('STATE_NAV_ON',          1<<4);
define('STATE_NAV_OFF',         1<<5);
define('STATE_ILHA_ON',         1<<6);
define('STATE_ILHA_OFF',        1<<7);
define('STATE_NAVIO_ON',        1<<8);
define('STATE_NAVIO_OFF',       1<<9);
define('STATE_VIVO_ON',         1<<10);
define('STATE_VIVO_OFF',        1<<11);
define('STATE_COMBATE_ON',      1<<12);
define('STATE_COMBATE_OFF',     1<<13);
define('STATE_MISSAO_ON',       1<<14);
define('STATE_MISSAO_OFF',      1<<15);
define('STATE_ALLY_ON',         1<<16);
define('STATE_ALLY_OFF',        1<<17);

/*
|--------------------------------------------------------------------------
| Game Rates
|--------------------------------------------------------------------------
|
| Rates do jogo
|
*/
define('RATE_EXP', 1);
define('RATE_BERRIES', 1);
define('RATE_EXP_PROF', 1);
define('RATE_NAVIGATION', 1);

/*
|--------------------------------------------------------------------------
| Game Paramethers
|--------------------------------------------------------------------------
|
| Valores fixos de referencia
|
*/
define('EXP_POR_SELO', 130000);

/*
|--------------------------------------------------------------------------
| Profissões
|--------------------------------------------------------------------------
|
| Códigos de profissoes
|
*/
define('PROF_CARTOGRAFO',   1);
define('PROF_NAVEGADOR',    2);
define('PROF_MEDICO',       3);
define('PROF_CARPINTEIRO',  4);
define('PROF_ARQUEOLOGO',   5);
define('PROF_MERGULHADOR',  6);
define('PROF_COZINHEIRO',   7);
define('PROF_MUSICO',       8);
define('PROF_COMBATENTE',   9);
define('PROF_ENGENHEIRO',   10);
define('PROF_FERREIRO',     11);
define('PROF_ARTESAO',      12);
define('PROF_CIENTISTA',    13);

/*
|--------------------------------------------------------------------------
| Tipos de itens
|--------------------------------------------------------------------------
|
| Códigos de Tipos de itens
|
*/
define('ITEM_ACESSORIO',    0);
define('ITEM_COMIDA',       1);
define('ITEM_MAPA',         2);
define('ITEM_CASCO',        3);
define('ITEM_LEME',         4);
define('ITEM_VELAS',        5);
define('ITEM_POSE',         6);
define('ITEM_REMEDIO',      7);
define('ITEM_LOGIA',        8);
define('ITEM_PARAMECIA',    9);
define('ITEM_ZOAN',         10);
define('ITEM_CANHAO',       12);
define('ITEM_BALA',         13);
define('ITEM_EQUIPAMENTO',  14);
define('ITEM_REAGENTE',     15);
define('ITEM_ISCA',         16);
define('ITEM_ISCA_DOURADA', 17);

/*
|--------------------------------------------------------------------------
| Novos tipos de itens
|--------------------------------------------------------------------------
|
| Códigos de Tipos de itens da estrutura nova
|
*/
define('ITN_PARTE_NAVIO',   1);
define('ITN_AKUMA',         2);
define('ITN_MATERIAL',      3);
define('ITN_MISSAO',        4);
define('ITN_COMIDA',        5);
define('ITN_INGREDIENTE',   6);

/*
|--------------------------------------------------------------------------
| Categorias itens
|--------------------------------------------------------------------------
|
| Códigos de categorias de itens
|
*/
define('ITN_CAT_CINZA',     0);
define('ITN_CAT_BRANCO',    1);
define('ITN_CAT_VERDE',     2);
define('ITN_CAT_AZUL',      3);
define('ITN_CAT_VERMELHO',  4);
define('ITN_CAT_DOURADO',   5);

/*
|--------------------------------------------------------------------------
| Tipos de reparo de navio
|--------------------------------------------------------------------------
|
| Tipos de reparo de navio
|
*/
define('REPARO_NAVIO_ENGENHEIRO', 1);
define('REPARO_NAVIO_ESTALEIRO', 2);

/*
|--------------------------------------------------------------------------
| IDs de NPCs estaticos
|--------------------------------------------------------------------------
|
| IDs dos NPCs responsáveis pelos principais edifícios das ilhas
|
*/
define('NPC_ESTALEIRO', 'estaleiro');
define('NPC_RESTAURANTE', 'restaurante');
define('NPC_MERCADO', 'mercado');

/*
|--------------------------------------------------------------------------
| Tipos de receitass
|--------------------------------------------------------------------------
|
| Tipos de receitas
|
*/
define('RECEITA_UNICA', 0);
define('RECEITA_MULTIPLA', 1);

/*
|--------------------------------------------------------------------------
| Parametros de ranking
|--------------------------------------------------------------------------
|
| Parametros de ranking
|
*/
define('RNK_POSICOES_PREMIADAS', 35);

/*
|--------------------------------------------------------------------------
| Tipos de eventos
|--------------------------------------------------------------------------
|
| Parametros de ranking
|
*/
define('EVENT_GENERICO', "GENERICO");
define('EVENT_FIGHT', "FIGHT");
define('EVENT_TAREFA', "TAREFA");
