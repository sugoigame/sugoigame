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

if (substr($usuario["alianca"][$permicao["autoridade"]], 6, 1) == 0) {

	echo ("#Você não tem permissão para iniciar missões.");
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
switch ($quant) {
	case 1:
		$quant = 50;
		break;
	case 2:
		$quant = 100;
		if ($usuario["alianca"]["lvl"] < 3) {

			echo ("#Sua aliança não cumpre os requisitos necessários para iniciar a missão.");
			exit();
		}
		break;
	case 3:
		$quant = 200;
		if ($usuario["alianca"]["lvl"] < 5) {

			echo ("#Sua aliança não cumpre os requisitos necessários para iniciar a missão.");
			exit();
		}
		break;
	default:
		$quant = 50;
		break;
}

$query = "SELECT * FROM tb_alianca_missoes WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() != 0) {

	echo ("#Você ja esta em missao.");
	exit();
}

$query = "INSERT INTO tb_alianca_missoes (cod_alianca, fim)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '$quant')";
$connection->run($query) or die("Nao foi possivel assinar");


echo ("Missão iniciada!");
?>

