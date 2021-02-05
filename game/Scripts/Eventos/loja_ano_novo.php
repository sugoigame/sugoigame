<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$recompensa_id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("recompensas_loja_ano_novo");

if (!isset($recompensas[$recompensa_id])) {
    $protector->exit_error("Recompensa inválida");
}

$recompensa = $recompensas[$recompensa_id];

$derrotados = $userDetails->get_item(205, TIPO_ITEM_REAGENT);
$derrotados = $derrotados ? $derrotados["quant"] : 0;

if ($derrotados < $recompensa["preco"]) {
    $protector->exit_error("Você não possui doces suficientes");
}

if (isset($recompensa["tipo_item"])) {
    $quant = isset($recompensa["quant"]) ? $recompensa["quant"] : 1;
    if ($recompensa["tipo_item"] == TIPO_ITEM_REAGENT || $recompensa["tipo_item"] == TIPO_ITEM_COMIDA) {
        if (!$userDetails->add_item($recompensa["cod_item"], $recompensa["tipo_item"], $quant)) {
            $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
        }
    } elseif ($recompensa["tipo_item"] == TIPO_ITEM_EQUIPAMENTO) {
        if (!$userDetails->can_add_item()) {
            $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
        }

        $userDetails->add_equipamento_by_cod($recompensa["cod_item"]);
    }
}

if (isset($recompensa["haki"])) {
    $userDetails->haki_for_all($recompensa["haki"]);
}

if (isset($recompensa["xp"])) {
    $userDetails->xp_for_all($recompensa["xp"]);
}

if (isset($recompensa["skin"])) {
    $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
        "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
}

$userDetails->reduz_item(205, TIPO_ITEM_REAGENT, $recompensa["preco"]);

$response->send_share_msg("Você recebeu sua recompensa!");