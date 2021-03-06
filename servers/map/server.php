<?php
$_SERVER["HTTP_HOST"] = isset($argv[1]) ? $argv[1] : "sugoigame.com.br";

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/../../public/Includes/database/mywrap.php';
require __DIR__ . '/../../public/Classes/requires.php';
require __DIR__ . "/../../public/Funcoes/requires.php";
require __DIR__ . "/../../public/Constantes/requires.php"; 

require __DIR__ . '/src/WsServer.php';
require __DIR__ . '/src/EventBroker.php';
require __DIR__ . '/src/MapServerUserDetails.php';
require __DIR__ . '/src/Navigation.php';

// mysqli
$connection = new mywrap_con();

$connection->run("SET NAMES 'utf8'");
$connection->run("SET character_set_connection=utf8");
$connection->run("SET character_set_client=utf8");
$connection->run("SET character_set_results=utf8");
$connection->run("SET CHARACTER SET utf8");
$connection->run("SET sql_mode='NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");

$navigation = new Navigation($connection);

// Run the server application through the WebSocket protocol on port 9000
$app = new Ratchet\App('localhost', 9000, "0.0.0.0");
$app->route('/mar', new WsServer($connection, $navigation), ['*']);
$app->run();
