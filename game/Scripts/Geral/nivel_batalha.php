<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

if ($userDetails->tripulacao["battle_points"] < PONTOS_POR_NIVEL_BATALHA) {
    $protector->exit_error("Você não conseguiu experiência de batalha suficiente.");
}

if (!$userDetails->can_add_item(3)) {
    $protector->exit_error("Você não tem espaço no inventário para receber sua recompensa.");
}

$connection->run("UPDATE tb_usuarios SET battle_lvl = battle_lvl + 1, battle_points = battle_points - ? WHERE id = ?",
    "ii", array(PONTOS_POR_NIVEL_BATALHA, $userDetails->tripulacao["id"]));

$userDetails->add_item(187, TIPO_ITEM_REAGENT, 1);

// equipamento preto
if (($userDetails->tripulacao["battle_lvl"] + 1) % 30 == 0) {
    $userDetails->add_item(162, TIPO_ITEM_REAGENT, 1);
}
// equipamento azul
if (($userDetails->tripulacao["battle_lvl"] + 1) % 15 == 0) {
    $userDetails->add_item(183, TIPO_ITEM_REAGENT, 1);
}
// equipamento verde
if (($userDetails->tripulacao["battle_lvl"] + 1) % 10 == 0) {
    $userDetails->add_item(188, TIPO_ITEM_REAGENT, 1);
}

$response->send_share_msg("Você alcançou o " . ($userDetails->tripulacao["battle_lvl"] + 1) . "º Nível de Batalha e recebeu um prêmio!");