<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    mysql_close();

    echo("#Você precisa estar logado.");
    exit();
}
$protector->must_be_out_of_any_kind_of_combat();
if (!isset($_GET["destino"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$destino = mysql_real_escape_string($_GET["destino"]);
if (!preg_match("/^[\d]+$/", $destino)) {
    echo("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_mapa WHERE ilha='$destino'";
$result = mysql_query($query);
$mapa = mysql_fetch_array($result);

if ($mapa["ilha_dono"] != $userDetails->tripulacao["id"]) {
    mysql_close();
    echo("#O sistema de transporte não pode te levar para essa ilha.");
    exit();
}

if ($usuario["berries"] < 3000000) {
    mysql_close();
    echo("#Você não possui berries suficiente.");
    exit();
}

$berries = $usuario["berries"] - 3000000;
$query = "UPDATE tb_usuarios SET berries='$berries', x='" . $mapa["x"] . "', y='" . $mapa["y"] . "', mar_visivel=0
	WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("#Nao foi possivel iniciar navegação");
mysql_close();
echo("%oceano");
