<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$userDetails->tripulacao) {
    echo "#Você precisa estar logado.";
    exit();
}


if ($userDetails->rotas && $userDetails->rotas[0]["momento"] <= atual_segundo()) {
    include "mapa_finalizanav.php";
}

$retorno = array(
    "navegacao" => $userDetails->rotas ? $userDetails->rotas[0]["momento"] - atual_segundo() : 0,
    "destino" => $userDetails->rotas,
    "mapa" => $userDetails->ilha,
    "ilha" => nome_ilha($userDetails->ilha["ilha"]),
    "mar" => nome_mar($userDetails->ilha["mar"]),
    "coord" => get_human_location($userDetails->ilha["x"], $userDetails->ilha["y"])
);

echo json_encode($retorno, JSON_NUMERIC_CHECK);