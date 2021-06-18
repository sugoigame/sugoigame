<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$rdp = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA)["valor_varchar"]);
$adf = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA)["valor_varchar"]);

if (!in_array($userDetails->tripulacao["id"], $rdp) && !in_array($userDetails->tripulacao["id"], $adf)) {
    $protector->exit_error("Você não tem direito a essa recompensa");
}

$recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_era WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count();

if ($recompensa) {
    $protector->exit_error("Você já recebeu sua recompensa");
}

if ($rdp[0] == $userDetails->tripulacao["id"] || $adf[0] == $userDetails->tripulacao["id"]) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? 1 : 2));

    $connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ?",
        "ii", array(550, $userDetails->conta["conta_id"]));

    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(15, $userDetails->tripulacao["id"]));

    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(161, TIPO_ITEM_REAGENT, 1);
    $userDetails->add_item(208, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[1] == $userDetails->tripulacao["id"] || $adf[1] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ?",
        "ii", array(440, $userDetails->conta["conta_id"]));

    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(15, $userDetails->tripulacao["id"]));

    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[2] == $userDetails->tripulacao["id"] || $adf[2] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(15, $userDetails->tripulacao["id"]));

    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[3] == $userDetails->tripulacao["id"] || $adf[3] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(15, $userDetails->tripulacao["id"]));

    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
} else if ($rdp[4] == $userDetails->tripulacao["id"] || $adf[4] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(15, $userDetails->tripulacao["id"]));

    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 2);
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
}


$connection->run("INSERT INTO tb_recompensa_recebida_era (tripulacao_id) VALUE (?)",
    "i", array($userDetails->tripulacao["id"]));

$response->send_share_msg("Você recebeu uma incrível recompensa!");