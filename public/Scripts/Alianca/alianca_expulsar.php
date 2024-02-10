<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	echo ("#Você precisa estar logado.");
	exit();
}
if (! $inally) {

	echo ("#Voce nao faz parte de uma aliança");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 1, 1) == 0) {

	echo ("#Você não tem permissão para expulsar jogadores.");
	exit();
}

if (! isset($_GET["cod"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$forma = $protector->get_number_or_exit("cod");

if (! ereg("[0-9]", $forma)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

if ($forma == $usuario["id"]) {

	echo ("#Você não pode se explusar da aliança.");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE id='$forma'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("#Você não pode expulsar jogadores que nao estão na aliança.");
	exit();
}

$allydele = $result->fetch_array();
if ($allydele["cod_alianca"] != $usuario["alianca"]["cod_alianca"]) {

	echo ("#Você não pode expulsar jogadores que nao estão na aliança.");
	exit();
}
$query = "SELECT * FROM tb_alianca_guerra WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() != 0) {

	echo ("#Você está em guerra!");
	exit();
}

$query = "DELETE FROM tb_alianca_membros WHERE id='$forma' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");

$query = "DELETE FROM tb_alianca_guerra_ajuda WHERE id='$forma' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");


echo ("Membro expulso");
?>

