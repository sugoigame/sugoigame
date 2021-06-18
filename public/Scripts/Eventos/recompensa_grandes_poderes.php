<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$rdp = explode(",", get_value_variavel_global(VARIAVEL_YONKOUS)["valor_varchar"]);
$adf = explode(",", get_value_variavel_global(VARIAVEL_ALMIRANTES)["valor_varchar"]);

if (!in_array($userDetails->tripulacao["id"], $rdp) && !in_array($userDetails->tripulacao["id"], $adf)) {
    $protector->exit_error("Você não tem direito a essa recompensa");
}

$recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_grandes_poderes WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count();

if ($recompensa) {
    $protector->exit_error("Você já recebeu sua recompensa");
}

if ($rdp[0] == $userDetails->tripulacao["id"] || $adf[0] == $userDetails->tripulacao["id"]) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? 93 : 94));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(122, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(209, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[1] == $userDetails->tripulacao["id"] || $adf[1] == $userDetails->tripulacao["id"]) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? 93 : 94));;

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(122, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(209, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[2] == $userDetails->tripulacao["id"] || $adf[2] == $userDetails->tripulacao["id"]) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? 93 : 94));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(209, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[3] == $userDetails->tripulacao["id"] || $adf[3] == $userDetails->tripulacao["id"]) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? 93 : 94));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(209, TIPO_ITEM_REAGENT, 1);
}


$connection->run("INSERT INTO tb_recompensa_recebida_grandes_poderes (tripulacao_id) VALUE (?)",
    "i", array($userDetails->tripulacao["id"]));

$response->send_share_msg("Você se tornou um " . ($userDetails->tripulacao["faccao"] == FACCAO_MARINHA ? "Almirante" : "Yonkou") . "!");