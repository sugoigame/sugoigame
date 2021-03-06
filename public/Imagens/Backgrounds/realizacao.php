<?php
include "../../Funcoes/bandeira.php";
require_once "../../Includes/database/mywrap.php";

// mysqli
$connection = new mywrap_con();

$connection->run("SET NAMES 'utf8'");
$connection->run("SET character_set_connection=utf8");
$connection->run("SET character_set_client=utf8");
$connection->run("SET character_set_results=utf8");
$connection->run("SET CHARACTER SET utf8");


$id = isset($_GET["id"]) ? $_GET["id"] : 1;
$cod = isset($_GET["cod"]) ? $_GET["cod"] : 1;
$message = isset($_GET["m"]) ? base64_decode($_GET["m"]) : "";

$tripulacao = $connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", array($id));

if (!$tripulacao->count()) {
    echo("Erro ao processar a requisicao");
    exit();
}

$tripulacao = $tripulacao->fetch_array();

$pers = $connection->run("SELECT * FROM tb_personagens WHERE cod = ?", "i", array($cod));

if (!$pers->count()) {
    echo("Erro ao processar a requisicao");
    exit();
}

$pers = $pers->fetch_array();

$background = imagecreatefromjpeg("realizacao3.jpg");

$avatar = imagecreatefromjpeg("../Personagens/Big/" . sprintf("%04d", $pers["img"]) . "(" . $pers["skin_c"] . ").jpg");
imagecopyresampled($background, $avatar, 60, 136, 0, 0, 200, 300, imagesx($avatar), imagesy($avatar));

$flag = generate_flag($tripulacao["faccao"], $tripulacao["bandeira"], "../Bandeiras/");
imagecopyresampled($background, $flag["bandeira"], 113, 62, 0, 0, 95, 65, imagesx($flag["bandeira"]), imagesy($flag["bandeira"]));

$textcolor = imagecolorallocate($background, 255, 250, 10);
$lines = explode("\n", wordwrap($message, 21));
foreach ($lines as $index => $line) {
    $dimensions = imagettfbbox(36, 0, "./Roboto-Regular.ttf", $line);
    $x = 580 - ($dimensions[4] - $dimensions[6]) / 2;
    imagettftext($background, 36, 0, $x, 250 + $index * 60, $textcolor, "./Roboto-Regular.ttf", $line);
}

header('Content-type: image/jpeg');
imagejpeg($background);
imagedestroy($avatar);
destroy_flag($flag);
imagedestroy($background);