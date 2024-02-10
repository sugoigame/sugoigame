<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	echo ("#Você precisa estar logado!");
	exit;
}
$query = "SELECT * FROM tb_marcenaria_reparos WHERE id=" . $usuario["id"] . "";
$result = $connection->run($query);
if ((($usuario["navio_hp"] < $usuario["navio_hp_max"] and $innavio and $usuario["navio_hp"] > 0) or ($usuario["navio_hp"] == 0))
	and $usuario["navio_reparo"] == 0 and $result->count() == 0) {

	$berries = $usuario["navio_hp_max"] - $usuario["navio_hp"];
	$rest = $berries % 10;
	$berries -= $rest;
	$berries /= 10;
	if ($rest > 0)
		$berries += 1;
	$berries *= 1000;
	$berries = $usuario["berries"] - $berries;
	if ($berries < 0) {

		echo ("#Voce nao tem dinheiro o suficiente");
		exit;
	}
	$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
	$connection->run($query) or die("Nao foi possivel pagar os reparos");

	$tempo = $usuario["navio_hp_max"] - $usuario["navio_hp"];
	$quant = $tempo;
	$rest = $tempo % 10;
	$tempo -= $rest;
	$tempo /= 10;
	if ($rest > 0)
		$tempo += 1;
	$tempo *= 60;
	$mod = 1;
	$tempo *= $mod;
	$tempo += atual_segundo();
	$query = "INSERT INTO tb_marcenaria_reparos (id, tempo, tipo, quant) 
		VALUES ('" . $usuario["id"] . "', '$tempo', '2', '$quant')";
	$connection->run($query) or die("Nao foi possivel iniciar os reparos");

	$query = "DELETE FROM tb_rotas WHERE id='" . $usuario["id"] . "'";
	$connection->run($query) or die("Nao foi possivel iniciar os reparos");


	echo ("@Concerto iniciado.");
} else {

	echo ("#voce nao cumpre os requisitos necessários para concertar o navio.");
}
?>

