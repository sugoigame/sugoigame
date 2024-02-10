<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

	header("location:../../?msg=Você precisa estar logado.");
	exit();
}

$forma = $protector->get_number_or_exit("cod");

if (! preg_match("/^[\d]+$/", $forma)) {

	header("location:../../?msg=Você informou algum caracter inválido.");
	exit();
}

$query = "DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='" . $usuario["id"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel cancelar o convite");


header("location:../../?ses=aliancaCriar");
?>

