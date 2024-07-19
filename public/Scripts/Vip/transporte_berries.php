<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {


    echo ("#Você precisa estar logado.");
    exit();
}
$protector->must_be_out_of_any_kind_of_combat();
if (! isset($_GET["destino"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$destino = $protector->get_number_or_exit("destino");
if (! preg_match("/^[\d]+$/", $destino)) {
    echo ("#Você informou algum caracter inválido.");
    exit();
}
if ($destino != 1 and $destino != 8 and $destino != 15 and $destino != 22 and $destino != 29 and $destino != 44) {

    echo ("#Você não pode acessar essa ilha com Berries");
    exit();
}
$mapa = \Regras\Ilhas::get_ilha($destino);

if ($mapa["mar"] != $usuario["mar"]) {

    echo ("#O sistema de transporte não pode te levar para essa ilha.");
    exit();
}

if ($usuario["berries"] < 1000000) {

    echo ("#Você não possui berries suficiente.");
    exit();
}

$berries = $usuario["berries"] - 1000000;
$query = "UPDATE tb_usuarios SET berries='$berries', x='" . $mapa["x"] . "', y='" . $mapa["y"] . "', mar_visivel=0
	WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("#Nao foi possivel iniciar navegação");

echo ("%oceano");
?>

