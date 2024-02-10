<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	echo ("#Você precisa estar logado.");
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

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$cont = $result->count();

if ($cont < $usuario["capacidade_iventario"]) {
	$query = "SELECT * FROM tb_personagens WHERE cod='$perso'";
	$result = $connection->run($query);
	$equipado = $result->fetch_array();
	if ($equipado["cod_acessorio"] == $item and $equipado["id"] == $usuario["id"]) {
		$query = "UPDATE tb_personagens SET cod_acessorio='0' WHERE cod='$perso'";
		$connection->run($query) or die("erro ao remover o item");

		$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES ('" . $usuario["id"] . "', '" . $equipado["cod_acessorio"] . "', '0', '1')";
		$connection->run($query) or die("Nao foi possivel inserir o item");


		echo ("Item removido");
	} else {

		echo ("#Seu personagem não tem este item equipado.");
	}
} else {

	echo ("#A capacidade do Iventário é de 55 itens apenas.");
}
?>

