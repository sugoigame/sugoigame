<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$incursao = explode(",", get_value_variavel_global(VARIAVEL_VENCEDORES_INCURSAO)["valor_varchar"]);

if (!in_array($userDetails->tripulacao["id"], $incursao)) {
    $protector->exit_error("Você não tem direito a essa recompensa");
}

$recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_incursao WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count();

if ($recompensa) {
    $protector->exit_error("Você já recebeu sua recompensa");
}

if ($incursao[0] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(1, $userDetails->tripulacao["id"]));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 1);
} else if ($incursao[1] == $userDetails->tripulacao["id"] || $adf[1] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(1, $userDetails->tripulacao["id"]));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 1);
} else if ($incursao[2] == $userDetails->tripulacao["id"] || $adf[2] == $userDetails->tripulacao["id"]) {
    $connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ?, credito_skin_navio = credito_skin_navio + 1 WHERE id = ?",
        "ii", array(1, $userDetails->tripulacao["id"]));

    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 1);
}


$connection->run("INSERT INTO tb_recompensa_recebida_incursao (tripulacao_id) VALUE (?)",
    "i", array($userDetails->tripulacao["id"]));

$response->send_share_msg("Você recebeu uma incrível recompensa!");