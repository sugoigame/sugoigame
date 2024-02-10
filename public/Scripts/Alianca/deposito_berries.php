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
if (! $inilha) {

	echo ("#Você precisa estar em uma ilha");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 10, 1) == 0) {

	echo ("#Você não tem permissão para isso.");
	exit();
}

if (! isset($_GET["quant"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$quant = $protector->get_number_or_exit("quant");

if (! preg_match("/^[\d]+$/", $quant)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if ($quant < 0) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if ($personagem[0]["lvl"] < 15) {

	echo ("#É necessário ter o capitão no nível 15 para utilizar o banco.");
	exit();
}

if ($usuario["berries"] < $quant) {

	echo ("#Você não tem todo esse dinheiro.");
	exit();
}

$quant_a = $usuario["alianca"]["banco"] + $quant;

$quant_u = $usuario["berries"] - $quant;

$query = "INSERT INTO tb_alianca_banco_log (cod_alianca, usuario, item, tipo)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '" . $personagem[0]["nome"] . "', '$quant Berries', '1')";
$connection->run($query);

$query = "UPDATE tb_usuarios SET berries='$quant_u' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("não foi possivel depositar o dinheiro");

$query = "UPDATE tb_alianca SET banco='$quant_a' WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$connection->run($query) or die("não foi possivel depositar o dinheiro");


echo ("@Dinheiro depositado");

?>

