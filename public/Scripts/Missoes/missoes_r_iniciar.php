<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    mysql_close();
    echo("#Voce está em combate");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($inrecrute) {
    mysql_close();
    echo("#Você está ocupado recrutando neste momento.");
    exit();
}
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!isset($_POST["duracao"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$opcao = mysql_real_escape_string($_POST["duracao"]);

if (!preg_match("/^[\d]+$/", $opcao)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
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
        echo("#Opção inválida.");
        exit();
        break;
}

$query = "INSERT INTO tb_missoes_r (id, x, y, fim, modif) 
	VALUES ('" . $usuario["id"] . "', '" . $usuario["x"] . "', '" . $usuario["y"] . "', '$tempo', '$opcao')";
mysql_query($query) or die("Nao foi possivel iniciar missao");

$query = "INSERT INTO tb_missoes_r_dia (id, x, y) 
	VALUES ('" . $usuario["id"] . "', '" . $usuario["x"] . "', '" . $usuario["y"] . "')";
mysql_query($query) or die("Nao foi possivel iniciar missao");

mysql_close();
echo("-Missão iniciada");
exit();
?>