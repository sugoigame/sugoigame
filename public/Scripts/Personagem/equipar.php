<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	echo ("#Voce precisa estar logado.");
	exit();
}

if (! isset($_GET["item"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! isset($_GET["person"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$item = $protector->get_number_or_exit("item");
$perso = $protector->get_number_or_exit("person");

if (! preg_match("/^[\d]+$/", $item)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! preg_match("/^[\d]+$/", $perso)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$query = "SELECT cod_acessorio FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$perso'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("#Personagem não encontrado.");
	exit();
}
$equipado = $result->fetch_array();
if ($equipado["cod_acessorio"] == "0") {
	$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='0'";
	$result = $connection->run($query);
	$cont = $result->count();
	if ($cont != "0") {
		$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='0' LIMIT 1";
		$connection->run($query) or die("Nao foi possivel remover o item");

		$query = "UPDATE tb_personagens SET cod_acessorio='$item' WHERE cod='$perso'";
		$connection->run($query) or die("Nao foi possivel atualizar o item");


		echo ("Item equipado!");
	} else {

		echo ("#Você não possui este item, por acaso andou roubando por aí?");
	}
} else {
	$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='0'";
	$result = $connection->run($query);
	$cont = $result->count();
	if ($cont != 0) {
		$query = "UPDATE tb_personagens SET cod_acessorio='$item' WHERE cod='$perso'";
		$connection->run($query) or die("Nao foi possivel atualizar o item");

		$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='0' LIMIT 1";
		$connection->run($query) or die("Nao foi possivel remover o item");

		$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES ('" . $usuario["id"] . "', '" . $equipado["cod_acessorio"] . "', '0', '1')";
		$connection->run($query) or die("Nao foi possivel inserir o item");


		echo ("Item equipado!");
	} else {

		echo ("#Você não possui este item, por acaso andou roubando por aí?");
	}
}
?>

