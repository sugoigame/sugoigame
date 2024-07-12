<?php
require_once "database/mywrap.php";

// Permite importar classes automaticamente com uso de namespaces
spl_autoload_register(function ($class) {
    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class);

    $file = str_replace("Includes", "", __DIR__) . $class_path . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
date_default_timezone_set('America/Sao_Paulo');

// mysqli
$connection = new mywrap_con();
$connection->run("SET NAMES 'utf8'");
$connection->run("SET character_set_connection=utf8");
$connection->run("SET character_set_client=utf8");
$connection->run("SET character_set_results=utf8");
$connection->run("SET CHARACTER SET utf8");

$result = $connection->run("SELECT * FROM tb_ban WHERE ip = ?", "s", $_SERVER['REMOTE_ADDR']);
if ($result->count()) {
    echo "Você não tem permissão para acessar esse site!";
    exit;
}

define('ALL_FORMAT', "//");
define('STR_FORMAT', "/^[\\w]+$/");
define('INT_FORMAT', "/^[\\d]+$/");
define('EMAIL_FORMAT', "/^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,3})$/");
define('EMAIL_FORMAT_2', "/^[A-Za-z0-9_\\-\\.]+@[A-Za-z0-9_\\-\\.]{2,}\\.[A-Za-z0-9]{2,}(\\.[A-Za-z0-9])?/");
define('DATA_FORMAT', "/^\\d{4}-\\d{1,2}-\\d{1,2}$/");
define('COORD_FORMAT', "/^\\d{1,3}_\\d{1,3}$/");

define('IS_BETA', true);

define('BONUS_GOLD_ATIVACAO', 300);

define('PONTOS_INICIAIS', 69);
define('PONTOS_POR_NIVEL', 4);
define('HP_INICIAL', 5300);
define('HP_POR_NIVEL', 300);
define('HP_POR_VITALIDADE', 50);

define('FACCAO_MARINHA', 0);
define('FACCAO_PIRATA', 1);

define('COD_CASCO_KAIROUSEKI', 2);

define('SKILLS_ICONS_MAX', 515);

define('PERSONAGENS_MAX', 369);

define('MAX_POINTS_MANTRA', 20);
define('MAX_POINTS_ARMAMENTO', 20);
define('MAX_POINTS_HAKI_AVANCADO', 20);
define('MAX_POINTS_HDR', 12);
define('HAKI_LVL_MAX', 50);

define('PONTOS_POR_NIVEL_BATALHA', 500);

$COD_HAOSHOKU_LVL = [
    1 => 3,
    2 => 4,
    3 => 5,
    4 => 6,
    5 => 7,
    6 => 8,
    7 => 9,
    8 => 10,
    9 => 11,
    10 => 12,
    11 => 13,
    12 => 14
];

define('ILHA_COLISEU', 42);
define('ILHA_COLISEU_2', 44);

require_once (dirname(__FILE__) . "/../Funcoes/requires.php");
require_once (dirname(__FILE__) . "/../Classes/requires.php");


$userDetails = new UserDetails($connection);
$response = new Response();
$protector = new Protector($userDetails, $response);

require_once (dirname(__FILE__) . "/../Cron/cron.php");


