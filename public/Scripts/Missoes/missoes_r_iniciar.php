<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if ($incombate) {

    echo ("#Voce está em combate");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha!");
    exit();
}
if ($inrecrute) {

    echo ("#Você está ocupado recrutando neste momento.");
    exit();
}
if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
$opcao = $protector->post_number_or_exit("duracao");

switch ($opcao) {
    case 1:
        $tempo = atual_segundo() + 1800;
        break;
    case 2:
        $tempo = atual_segundo() + 3600;
        break;
    case 3:
        $tempo = atual_segundo() + 5400;
        break;
    case 4:
        $tempo = atual_segundo() + 7200;
        break;
    case 5:
        $tempo = atual_segundo() + 10800;
        break;
    case 6:
        $tempo = atual_segundo() + 14400;
        break;
    case 7:
        $tempo = atual_segundo() + 28800;
        break;
    default:
        echo ("#Opção inválida.");
        exit();
        break;
}

$query = "INSERT INTO tb_missoes_r (id, x, y, fim, modif) 
	VALUES ('" . $usuario["id"] . "', '" . $usuario["x"] . "', '" . $usuario["y"] . "', '$tempo', '$opcao')";
$connection->run($query) or die("Nao foi possivel iniciar missao");

$query = "INSERT INTO tb_missoes_r_dia (id, x, y) 
	VALUES ('" . $usuario["id"] . "', '" . $usuario["x"] . "', '" . $usuario["y"] . "')";
$connection->run($query) or die("Nao foi possivel iniciar missao");


echo ("-Missão iniciada");
exit();
?>

