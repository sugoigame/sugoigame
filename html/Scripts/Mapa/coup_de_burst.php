<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if (!$userDetails->rotas) {
    $protector->exit_error("Você não está navegando");
}

if ($userDetails->rotas[0]["momento"] <= atual_segundo()) {
    exit();
}

if ($userDetails->tripulacao["coup_de_burst_usado"]) {
    $protector->exit_error("Você não pode usar o Coup De Burst duas vezes para o mesmo quadro.");
}

if (!$userDetails->vip["coup_de_burst"]) {
    $protector->exit_error("Você não tem coup de bursts para usar");
//    $tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));
//
//    if ($tipo == "gold") {
//        $protector->need_gold(PRECO_GOLD_COUP_DE_BURST_INSTANTANEO);
//    } else {
//        $protector->need_dobroes(PRECO_DOBRAO_COUP_DE_BURST_INSTANTANEO);
//    }
}

$visivel = $connection->run("SELECT count(id) AS total FROM tb_mapa_contem WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];

if (!$visivel) {
    $protector->exit_error("Você não pode usar o Coup De Burst enquanto estiver invisível.");
}

$connection->run("UPDATE tb_usuarios SET coup_de_burst_usado = coup_de_burst_usado + 5 WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

if (!$userDetails->vip["coup_de_burst"]) {
    $tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao"));

    if ($tipo == "gold") {
        $userDetails->reduz_gold(PRECO_GOLD_COUP_DE_BURST_INSTANTANEO, "coup_de_burst_instantaneo");
    } else {
        $userDetails->reduz_dobrao(PRECO_DOBRAO_COUP_DE_BURST_INSTANTANEO, "coup_de_burst_instantaneo");
    }
} else {
    $connection->run("UPDATE tb_vip SET coup_de_burst = coup_de_burst -1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
}

echo "%oceano";