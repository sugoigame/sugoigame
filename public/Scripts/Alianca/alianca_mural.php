<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	echo ("#Você precisa estar logado.");
	exit();
}
if (! $inally) {

	echo ("#Você não faz parte de uma aliança");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 3, 1) == 0) {

	echo ("#Você não tem permissão para alterar cargos.");
	exit();
}

$texto = $protector->post_alphanumeric_or_exit("mural");

$query = "UPDATE tb_alianca SET mural='$texto' WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");


echo ("Mural alterado");
?>

