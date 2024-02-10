<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit;
}
$carpok = FALSE;
for ($x = 0; $x < sizeof($personagem); $x++) {
    if ($personagem[$x]["profissao"] == 4) {
        $carpok = TRUE;
    }
}
if ($usuario["navio_hp"] < $usuario["navio_hp_max"] and $carpok and $usuario["navio_reparo"] != 0 and $innavio) {
    $tempo = atual_segundo();
    if ($usuario["navio_reparo"] > $tempo) {

        echo ("#Os reparos ainda não forão terminados.");
        exit;
    }
    if ($usuario["navio_reparo_tipo"] == 1) {
        $hp = $usuario["navio_hp"] + 10;
        if ($hp > $usuario["navio_hp_max"])
            $hp = $usuario["navio_hp_max"];

        $query = "UPDATE tb_usuario_navio SET hp='$hp', reparo='0', reparo_tipo='0' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel iniciar os reparos");

        for ($x = 0; $x < sizeof($personagem); $x++) {
            if ($personagem[$x]["profissao"] == 4) {
                if ($personagem[$x]["profissao_xp"] < $personagem[$x]["profissao_xp_max"]) {
                    $xp = $personagem[$x]["profissao_xp"] + 1;
                    $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem[$x]["cod"] . "'";
                    $connection->run($query) or die("Nao foi possivel evoluir o carpinteiro");
                }
            }
        }
    } else if ($usuario["navio_reparo_tipo"] == 2) {
        $hp = $usuario["navio_hp"] + $usuario["navio_reparo_quant"];
        if ($hp > $usuario["navio_hp_max"])
            $hp = $usuario["navio_hp_max"];

        $berries = $usuario["navio_hp_max"] - $usuario["navio_hp"];
        $rest = $berries % 10;
        $berries -= $rest;
        $berries /= 10;
        if ($rest > 0)
            $berries += 1;
        for ($x = 0; $x < sizeof($personagem); $x++) {
            if ($personagem[$x]["profissao"] == 4) {
                if ($personagem[$x]["profissao_xp"] < $personagem[$x]["profissao_xp_max"]) {
                    $xp = $personagem[$x]["profissao_xp"] + $berries;
                    $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem[$x]["cod"] . "'";
                    $connection->run($query) or die("Nao foi possivel evoluir o carpinteiro");
                }
            }
        }

        $query = "UPDATE tb_usuario_navio SET hp='$hp', reparo='0', reparo_tipo='0' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel iniciar os reparos");
    }
    $query = "";

    echo ("-Reparos concluídos");
} else {

    echo ("#voce nao cumpre os requisitos necessários para concertar o navio.");
}
?>

