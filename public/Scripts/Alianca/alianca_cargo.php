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

if (substr($usuario["alianca"][$permicao["autoridade"]], 2, 1) == 0) {

	echo ("#Você não tem permissão para alterar cargos.");
	exit();
}

$forma = $protector->get_number_or_exit("cod");
$cargo = $protector->get_number_or_exit("cargo");

if ($forma == $usuario["id"]) {

	echo ("#Você não pode alterar o próprio cargo.");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='$forma'";
$result = $connection->run($query);
if ($result->count() == 0) {
	echo ("#Você não pode alterar cargo de jogadores que nao estão na aliança.");
	exit();
}

$allydele = $result->fetch_array();
if ($allydele["cod_alianca"] != $usuario["alianca"]["cod_alianca"]) {

	echo ("#Você não pode alterar cargo de jogadores que nao estão na aliança.");
	exit();
}
if ($cargo == 0) {
	if ($permicao["autoridade"] != 0) {

		echo ("#Você não tem permissão para nomear outro lider.");
		exit();
	} else {
		$query = "UPDATE tb_alianca_membros SET autoridade='1' WHERE id='" . $usuario["id"] . "' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
		$connection->run($query) or die("nao foi possivel expulsar");
	}
}

$query = "UPDATE tb_alianca_membros SET autoridade='$cargo' WHERE id='$forma' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");


echo ("Autoridade alterada");
?>

