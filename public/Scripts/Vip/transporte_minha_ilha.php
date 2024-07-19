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

$query = "SELECT * FROM tb_mapa WHERE ilha='$destino'";
$result = $connection->run($query);
$mapa = $result->fetch_array();
$ilha = \Regras\Ilhas::get_ilha($destino);

if ($mapa["ilha_dono"] != $userDetails->tripulacao["id"]) {
    echo ("#O sistema de transporte não pode te levar para essa ilha.");
    exit();
}

if ($usuario["berries"] < 3000000) {
    echo ("#Você não possui berries suficiente.");
    exit();
}

$berries = $usuario["berries"] - 3000000;
$query = "UPDATE tb_usuarios SET berries='$berries', x='" . $ilha["x"] . "', y='" . $ilha["y"] . "', mar_visivel=0
	WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("#Nao foi possivel iniciar navegação");

echo ("%oceano");
