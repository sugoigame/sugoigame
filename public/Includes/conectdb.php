<?php
require_once "database/mywrap.php";

// mysqli
$connection = new mywrap_con();
$connection->run("SET NAMES 'utf8'");
$connection->run("SET character_set_connection=utf8");
$connection->run("SET character_set_client=utf8");
$connection->run("SET character_set_results=utf8");
$connection->run("SET CHARACTER SET utf8");
$connection->run("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");

$result = $connection->run("SELECT * FROM tb_ban WHERE ip = ?", "s", $_SERVER['REMOTE_ADDR']);
if ($result->count()) {
	echo "Você não tem permissão para acessar esse site!";
	exit;
}

// old mysql
mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

mysql_query("SET NAMES 'utf8'");
mysql_query('SET character_set_connection=utf8');
mysql_query('SET character_set_client=utf8');
mysql_query('SET character_set_results=utf8');
mysql_query("SET CHARACTER SET utf8");
mysql_query("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");

define('ALL_FORMAT',				"//");
define('STR_FORMAT',				"/^[\\w]+$/");
define('INT_FORMAT',				"/^[\\d]+$/");
define('EMAIL_FORMAT',				"/^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,3})$/");
define('EMAIL_FORMAT_2',			"/^[A-Za-z0-9_\\-\\.]+@[A-Za-z0-9_\\-\\.]{2,}\\.[A-Za-z0-9]{2,}(\\.[A-Za-z0-9])?/");
define('DATA_FORMAT',				"/^\\d{4}-\\d{1,2}-\\d{1,2}$/");
define('COORD_FORMAT',				"/^\\d{1,3}_\\d{1,3}$/");

define('IS_BETA', 					FALSE);

define('BONUS_DOBROES_ATIVACAO', 	600);

define('PONTOS_POR_NIVEL',			4);		// Pontos para distribuir em atributos

define('FACCAO_MARINHA',			0);
define('FACCAO_PIRATA',				1);

define('COD_CASCO_KAIROUSEKI',		2);

define('SKILLS_ICONS_MAX',			512);

define('PERSONAGENS_MAX',			369);

define('MAX_POINTS_MANTRA',			20);
define('MAX_POINTS_ARMAMENTO',		20);
define('MAX_POINTS_HDR',			12);
define('HAKI_LVL_MAX',				25);

define('PONTOS_POR_NIVEL_BATALHA',	500);

$COD_HAOSHOKU_LVL = [
	1   => 3,
	2   => 4,
	3   => 5,
	4   => 6,
	5   => 7,
	6   => 8,
	7   => 9,
	8   => 10,
	9   => 11,
	10  => 12,
	11  => 13,
	12  => 14
];

define('ILHA_COLISEU',				42);
define('ILHA_COLISEU_2',			44);

require_once(dirname(__FILE__) . "/../Funcoes/requires.php");
require_once(dirname(__FILE__) . "/../Classes/requires.php");

$userDetails	= new UserDetails($connection);
$response		= new Response();
$protector		= new Protector($userDetails, $response);