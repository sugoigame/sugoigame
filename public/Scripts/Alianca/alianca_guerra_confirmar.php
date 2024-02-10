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
if ($usuario["alianca"]["lvl"] < 5) {

	echo ("#É necessário ter nível 5 para entrar em guerra!");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 0, 1) == 0) {

	echo ("#Você não tem permissão para desafiar jogadores.");
	exit();
}
if (! isset($_GET["cod"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$forma = $protector->get_number_or_exit("cod");

if (! preg_match("/^[\d]+$/", $forma)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$query = "SELECT * FROM tb_alianca_guerra_pedidos WHERE cod_alianca='$forma' AND convidado='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("#Você não foi desafiado por essa aliança.");
	exit();
}
$tipo = $result->fetch_array();

$fim = atual_segundo() + ($tipo["tipo"] / 5) * 86400;

$query = "INSERT INTO tb_alianca_guerra (cod_alianca, cod_inimigo, vitoria,fim)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '$forma', " . $tipo["tipo"] . ", '$fim')";
$connection->run($query) or die("Nao foi possivel assinar");

$query = "INSERT INTO tb_alianca_guerra (cod_alianca, cod_inimigo, vitoria, fim)
	VALUES ('$forma', '" . $usuario["alianca"]["cod_alianca"] . "', " . $tipo["tipo"] . ", '$fim')";
$connection->run($query) or die("Nao foi possivel assinar");

$query = "DELETE FROM tb_alianca_guerra_pedidos WHERE cod_alianca='$forma' AND convidado='" . $usuario["alianca"]["cod_alianca"] . "' 
	LIMIT 1";
$connection->run($query) or die("nao foi possivel cancelar o convite");


echo ("Desafio aceito!");
?>

