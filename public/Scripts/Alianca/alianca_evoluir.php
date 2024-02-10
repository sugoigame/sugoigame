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

if ($permicao["autoridade"] >= 3) {

	echo ("#Você não tem permissão para isso.");
	exit();
}
if ($usuario["alianca"]["xp"] < $usuario["alianca"]["xp_max"]) {

	echo ("#Sua aliança não tem experiencia suficiente");
	exit();
}
if ($usuario["alianca"]["lvl"] >= 10) {

	echo ("#Sua aliança já alcançou o nível máximo");
	exit();
}
$lvl = $usuario["alianca"]["lvl"] + 1;
$nxp = $usuario["alianca"]["xp"] - $usuario["alianca"]["xp_max"];
$xp_max = $usuario["alianca"]["xp_max"] + 500;

$query = "UPDATE tb_alianca SET lvl='$lvl', xp='$nxp', xp_max='$xp_max' 
	WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$connection->run($query) or die("nao foi possivel cancelar o convite");


echo ("Novo nível alcançado!");
?>

