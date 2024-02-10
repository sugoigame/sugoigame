<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if (! $inally) {

    echo ("#Você não faz parte de uma alianca");
    exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 7, 1) == 0) {

    echo ("#Você não tem permissão para iniciar missões.");
    exit();
}

$query = "SELECT * FROM tb_alianca_missoes WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Você nao esta em missao.");
    exit();
}

$missao = $result->fetch_array();

if ($missao["quant"] < $missao["fim"]) {

    echo ("#Você nao concluiu a missao.");
    exit();
}

$query = "DELETE FROM tb_alianca_missoes WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$connection->run($query) or die("Nao foi possivel assinar");

switch ($missao["fim"]) {
    case 50:
        $xp = $usuario["alianca"]["xp"] + 100;
        $score = $usuario["alianca"]["score"] + 10;
        break;
    case 100:
        $xp = $usuario["alianca"]["xp"] + 220;
        $score = $usuario["alianca"]["score"] + 22;
        break;
    case 200:
    case 1000000:
        $xp = $usuario["alianca"]["xp"] + 450;
        $score = $usuario["alianca"]["score"] + 45;
        break;
    default:
        $xp = $usuario["alianca"]["xp"] + 100;
        $score = $usuario["alianca"]["score"] + 10;
        break;
}


$query = "UPDATE tb_alianca SET xp='$xp', score='$score'
	WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$connection->run($query) or die("Nao foi possivel assinar");

if ($missao["boss_id"]) {
    $connection->run("UPDATE tb_alianca SET banco = banco + ? WHERE cod_alianca = ?",
        "ii", array(30000000, $userDetails->ally["cod_alianca"]));
}


echo ("Missão concluida!");
