<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	echo ("#Você precisa estar logado.");
	exit();
}

if (! isset($_GET["pers"]) or ! isset($_GET["ini"]) or ! isset($_GET["fa"])) {

	echo "Você informou algo inválido";
	exit();
}

if (! preg_match("/^[\d]+$/", $_GET["pers"]) or ! preg_match("/^[\d]+$/", $_GET["ini"]) or ! preg_match("/^[\d]+$/", $_GET["fa"])) {

	echo "Você informou algum caracter inválido";
	exit();
}

$pers = $_GET["pers"];
$ini = $_GET["ini"];
$fa = $_GET["fa"];

$query = "DELETE FROM tb_inimigos WHERE id='" . $usuario["id"] . "' AND personagem='$pers' AND inimigo='$ini' AND fa='$fa' LIMIT 1";
$connection->run($query) or die("Não foi possível remover esse registro da lista negra");


echo "Registro removido";
