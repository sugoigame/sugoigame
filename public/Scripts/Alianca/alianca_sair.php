<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	header("location:../../?msg=Você precisa estar logado.");
	exit();
}
if (! $inally) {

	header("location:../../?msg=Você não faz parte de uma aliança.");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if ($permicao["autoridade"] == 0) {

	header("location:../../?msg=Você não pode sair da sua aliança.");
	exit();
}
$query = "SELECT * FROM tb_alianca_guerra WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() != 0) {

	header("location:../../?msg=Você está em guerra!");
	exit();
}

$query = "DELETE FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");

$query = "DELETE FROM tb_alianca_guerra_ajuda WHERE id='" . $usuario["id"] . "' AND cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel expulsar");


header("location:../../?ses=aliancaCriar");
?>

