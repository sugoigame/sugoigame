<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

$pers = $protector->get_tripulante_or_exit("cod");
$atr = $protector->get_number_or_exit("alc");

if ($atr == 0) {
    $atr = null;
} else {
    $titulo = \Utils\Data::find("titulos", ["cod_titulo" => $atr]);

    if (! $titulo) {
        $protector->exit_error("Alcunha inválida");
    }

    if (isset($titulo["compartilhavel"]) && $titulo["compartilhavel"]) {
        $result = $connection->run(
            "SELECT pertit.titulo FROM tb_personagem_titulo pertit
            INNER JOIN tb_personagens per ON pertit.cod = per.cod
            WHERE per.id = ? AND pertit.titulo = ?",
            "ii", [$userDetails->tripulacao["id"], $atr]
        );

        if (! $result->count()) {
            $protector->exit_error("Alcunha indisponível");
        }
    } else {
        $result = $connection->run("SELECT * FROM tb_personagem_titulo WHERE cod=? AND titulo=?",
            "ii", [$pers["cod"], $atr]);

        if (! $result->count()) {
            $protector->exit_error("Alcunha indisponível");
        }
    }
    $atr = "$atr";
}

$connection->run("UPDATE tb_personagens SET titulo = ? WHERE cod = ?",
    "si", [$atr, $pers["cod"]]);


