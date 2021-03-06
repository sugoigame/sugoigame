<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$full_haki = $connection->run("SELECT count(*) AS total FROM tb_personagens WHERE id = ? AND haki_lvl_ultima_era = ?",
    "ii", array($userDetails->tripulacao["id"], 25))->fetch_array()["total"];

if (!$full_haki) {
    $protector->exit_error("Você não tem recompensa para receber");
}

$recompensa = $connection->run("SELECT * FROM tb_recompensa_recebida_haki WHERE tripulacao_id = ?", "i", array($userDetails->tripulacao["id"]))->count();

if ($recompensa) {
    $protector->exit_error("Você já recebeu sua recompensa");
}

if (!$userDetails->can_add_item(4)) {
    $protector->exit_error("Você não tem espaço disponível suficiente no inventário");
}

$userDetails->add_item(120, TIPO_ITEM_REAGENT, $full_haki);
$userDetails->add_item(122, TIPO_ITEM_REAGENT, $full_haki);
$connection->run("UPDATE tb_usuarios SET credito_skin = credito_skin + ? WHERE id = ?",
    "ii", array($full_haki, $userDetails->tripulacao["id"]));

if ($full_haki >= 15) {
    if ($userDetails->tripulacao["campanha_impel_down"] !== 0) {
        $userDetails->add_item(166, TIPO_ITEM_REAGENT, 1);
    }
    $userDetails->add_item(201, TIPO_ITEM_REAGENT, 1);
}

$connection->run("INSERT INTO tb_recompensa_recebida_haki (tripulacao_id) VALUE (?)",
    "i", array($userDetails->tripulacao["id"]));

echo "Você recebeu uma incrível recompensa!";