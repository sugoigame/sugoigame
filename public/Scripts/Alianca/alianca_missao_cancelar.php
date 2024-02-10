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

$query = "DELETE FROM tb_alianca_missoes WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$connection->run($query) or die("Nao foi possivel assinar");


echo ("Missão abortada");
?>

