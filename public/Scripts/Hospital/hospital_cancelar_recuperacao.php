<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	echo ("#Você precisa estar logado!");
	exit();
}
if (! $inilha) {

	echo ("#Você precisa estar em uma ilha para comprar itens.");
	exit();
}
if (! isset($_GET["cod"])) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$cod = $protector->get_number_or_exit("cod");
if (! preg_match("/^[\d]+$/", $cod)) {

	echo ("#Você informou algum caracter inválido.");
	exit();
}
$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont == 0) {

	echo ("#Personagem não encontrado.");
	exit();
}

$query = "UPDATE tb_personagens SET respawn='0', respawn_tipo='0' WHERE cod='$cod'";
$connection->run($query) or die("nao foi possivel iniciar a recuperacao");


echo "Tratamento cancelado";
?>

