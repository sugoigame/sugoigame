<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	header("location:../../?msg=Você precisa estar logado.");
	exit();
}
if ($inally) {

	header("location:../../?msg=Você já faz parte de uma aliança");
	exit();
}
if (! isset($_GET["cod"])) {

	header("location:../../?msg=Você informou algum caracter inválido.");
	exit();
}
$forma = $protector->get_number_or_exit("cod");

if (! preg_match("/^[\d]+$/", $forma)) {

	header("location:../../?msg=Você informou algum caracter inválido.");
	exit();
}

$query = "SELECT * FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

	header("location:../../?msg=Você não foi convidado para essa aliança.");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='$forma'";
$result = $connection->run($query);
if ($result->count() >= 10) {
	$query = "DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='" . $usuario["id"] . "' LIMIT 1";
	$connection->run($query) or die("nao foi possivel cancelar o convite");


	echo ("#Essa aliança aliança já atingiu o número máximo de membros permitidos.");
	exit();
}

$query = "INSERT INTO tb_alianca_membros (cod_alianca, id)
	VALUES ('$forma', '" . $usuario["id"] . "')";
$connection->run($query) or die("Nao foi possivel assinar");

$query = "DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='" . $usuario["id"] . "' LIMIT 1";
$connection->run($query) or die("nao foi possivel cancelar o convite");


header("location:../../?ses=alianca");
?>

