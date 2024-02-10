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
if ($result->count() != 0 and $usuario["navio_reparo"] == 0) {
	$tempo = atual_segundo();
	$reparo = $result->fetch_array();
	if ($reparo["tempo"] > $tempo) {

		echo ("#Os reparos ainda não forão terminados.");
		exit;
	}
	if ($reparo["tipo"] == 1) {
		$hp = $usuario["navio_hp"] + 10;
		if ($hp > $usuario["navio_hp_max"])
			$hp = $usuario["navio_hp_max"];

		$query = "UPDATE tb_usuario_navio SET hp='$hp', reparo='0', reparo_tipo='0' WHERE id='" . $usuario["id"] . "'";
		$connection->run($query) or die("Nao foi possivel iniciar os reparos");

		$query = "DELETE FROM tb_marcenaria_reparos WHERE id='" . $usuario["id"] . "'";
		$connection->run($query) or die("Nao foi possivel iniciar os reparos");
	} else if ($reparo["tipo"] == 2) {
		$hp = $usuario["navio_hp"] + $reparo["quant"];
		if ($hp > $usuario["navio_hp_max"])
			$hp = $usuario["navio_hp_max"];

		$query = "UPDATE tb_usuario_navio SET hp='$hp', reparo='0', reparo_tipo='0' WHERE id='" . $usuario["id"] . "'";
		$connection->run($query) or die("Nao foi possivel iniciar os reparos");

		$query = "DELETE FROM tb_marcenaria_reparos WHERE id='" . $usuario["id"] . "'";
		$connection->run($query) or die("Nao foi possivel iniciar os reparos");
	}

	echo ("Concerto finalizado!");
} else {

	echo ("#voce nao cumpre os requisitos necessários para concertar o navio.");
}
?>

