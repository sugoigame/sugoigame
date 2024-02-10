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
if (! isset($_GET["pers"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! isset($_GET["pts"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$item = $protector->get_number_or_exit("item");
$perso = $protector->get_number_or_exit("pers");
$pts = $protector->get_number_or_exit("pts");
if (! preg_match("/^[\d]+$/", $item)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! preg_match("/^[\d]+$/", $perso)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! preg_match("/^[\d]+$/", $pts)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$query = "SELECT * FROM tb_item_equipamentos WHERE item='$item' LIMIT 1";
$result = $connection->run($query);
$equipamento = $result->fetch_array();

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$perso'";
$result = $connection->run($query);
if ($result->count() == 0) {

	echo ("#Personagem não encontrado.");
	exit();
}
$personagem = $result->fetch_array();

$query = "SELECT * FROM tb_personagem_equip_treino WHERE cod='$perso' AND item='$item'";
$result = $connection->run($query);
if ($result->count() == 0) {
	$treino["xp"] = 0;
	$insert = TRUE;
} else {
	$treino = $result->fetch_array();
	$insert = FALSE;
}
$treino_max = $equipamento["treino_max"] * $personagem["lvl"];

$treino_xp = $treino["xp"] + $pts;
if ($treino_xp > $treino_max)
	$treino_xp = $treino_max;

if ($treino_xp == $treino_max)
	$pts = $treino_max - $treino["xp"];

if ($usuario["disposicao"] < $pts) {

	echo ("#Você não tem disposição para treinar.");
	exit();
}

$disp = $usuario["disposicao"] - $pts;
$query = "UPDATE tb_usuarios SET disposicao='$disp' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel treinar");

if ($insert) {
	$query = "INSERT INTO tb_personagem_equip_treino (cod, item, xp) VALUES ('$perso', '$item', '$treino_xp')";
} else {
	$query = "UPDATE tb_personagem_equip_treino SET xp='$treino_xp' WHERE cod='$perso' AND item='$item'";
}
$connection->run($query) or die("Nao foi possivel treinar");


echo ":equipamentos&outro=" . $personagem["cod"];
?>

