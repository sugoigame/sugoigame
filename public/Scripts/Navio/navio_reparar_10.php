<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#?msg=Você precisa estar logado!");
    exit;
}
$carpok = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4) {
        if ($carpok) {
            if ($carpinteiro["profissao_lvl"] < $personagem[$x]["profissao_lvl"])
                $carpinteiro = $personagem[$x];
        } else
            $carpinteiro = $personagem[$x];
        $carpok = TRUE;
    }
}
if ($usuario["navio_hp"] < $usuario["navio_hp_max"] and $carpok and $usuario["navio_reparo"] == 0 and $innavio) {
    $berries = $usuario["berries"] - 1000;
    if ($berries < 0) {

        echo ("#Voce nao tem dinheiro o suficiente");
        exit;
    }
    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
    $connection->run($query) or die("Nao foi possivel pagar os reparos");

    $mod = (1 - $carpinteiro["profissao_lvl"] * 0.05);
    $tempo = atual_segundo() + (60 * $mod);
    $query = "UPDATE tb_usuario_navio SET reparo='$tempo', reparo_tipo='1' WHERE id='" . $usuario["id"] . "'";
    $connection->run($query) or die("Nao foi possivel iniciar os reparos");


    echo ("-Reparos iniciados");
} else {

    echo ("#voce nao cumpre os requisitos necessários para concertar o navio.");
}
?>

