<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	echo ("#Você precisa estar logado!");
	exit();
}


if (! isset($_GET["item"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! isset($_GET["tipo"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$item = $protector->get_number_or_exit("item");
$tipo = $protector->get_number_or_exit("tipo");

if (! preg_match("/^[\d]+$/", $item)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
if (! preg_match("/^[\d]+$/", $tipo)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont != 0) {
	$item_info = $result->fetch_array();
	if ($item_info["quant"] == 1) {
		if ($tipo == 2) {
			$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
			$connection->run($query) or die("nao foi possivel apagar o item");

			$query = "DELETE FROM tb_item_mapa WHERE id='" . $usuario["id"] . "' AND cod_mapa='$item' LIMIT 1";
			$connection->run($query) or die("nao foi possivel apagar o item");

			$query = "DELETE FROM tb_item_mapa_visivel WHERE cod_mapa='$item'";
			$connection->run($query) or die("nao foi possivel apagar o item");

			$query = "UPDATE tb_usuarios SET desenho='0', desenho_cod='0' WHERE id='" . $usuario["id"] . "'";
			$connection->run($query) or die("NAO FOI possivel cancelar a expedicao");


			echo ("Item descartado.");
		} else if ($tipo == 14) {
			$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
			$connection->run($query) or die("nao foi possivel apagar o item");

			$query = "DELETE FROM tb_item_equipamentos WHERE cod_equipamento='$item' LIMIT 1";
			$connection->run($query) or die("nao foi possivel apagar o item");


			echo ("Item descartado.");
		} else {
			$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
			$connection->run($query) or die("nao foi possivel apagar o item");


			echo ("Item descartado.");
		}
	} else {
		if (isset($_GET["tudo"])) {
			if ($_GET["tudo"] == 1) {
				$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
				$connection->run($query) or die("nao foi possivel apagar o item");


				echo ("Item descartado.");
			} else {
				$quant = $item_info["quant"] - 1;
				$query = "UPDATE tb_usuario_itens SET quant='$quant' WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
				$connection->run($query) or die("nao foi possivel remover o item");


				echo ("Item descartado.");
			}
		} else {
			$quant = $item_info["quant"] - 1;
			$query = "UPDATE tb_usuario_itens SET quant='$quant' WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
			$connection->run($query) or die("nao foi possivel remover o item");


			echo ("Item descartado.");
		}
	}
} else {

	echo ("#Você não tem este item no iventário");
}
?>

