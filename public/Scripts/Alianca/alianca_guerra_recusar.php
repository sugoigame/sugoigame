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

if (substr($usuario["alianca"][$permicao["autoridade"]], 4, 1) == 0) {

	echo ("#Você não tem permissão para desafiar jogadores.");
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

$query = "DELETE FROM tb_alianca_guerra_pedidos 
	WHERE cod_alianca='$forma' AND convidado='" . $usuario["alianca"]["cod_alianca"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel cancelar o convite");


echo ("Desafio recusado");
?>

