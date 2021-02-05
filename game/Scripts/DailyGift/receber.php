<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if ($userDetails->tripulacao["presente_diario_obtido"]) {
    $protector->exit_error("Você já recebeu seu presente hoje.");
}

$recompensas = DataLoader::load("daily_gift");

$recompensa = $recompensas[$userDetails->tripulacao["presente_diario_count"]];

if (isset($recompensa["akuma"])) {
    if (!$userDetails->add_item(rand(100, 110), rand(8, 10), 1, true)) {
        $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
    }
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

        $equipamento = $connection->run("SELECT * FROM tb_equipamentos WHERE item = ?", "i", array($recompensa["cod_item"]))->fetch_array();

        $userDetails->add_equipamento($equipamento);
    }
}

if (isset($recompensa["haki"])) {
    $userDetails->haki_for_all($recompensa["haki"]);
}

if (isset($recompensa["xp"])) {
    $userDetails->xp_for_all($recompensa["xp"]);
}

if (isset($recompensa["dobroes"])) {
    $connection->run("UPDATE tb_conta SET dobroes = dobroes + ? WHERE conta_id = ?",
        "ii", array($recompensa["dobroes"], $userDetails->conta["conta_id"]));
}

if (isset($recompensa["reputacao"])) {
    $connection->run("UPDATE tb_usuarios SET reputacao = reputacao + ?, reputacao_mensal = reputacao_mensal + ? WHERE id = ?",
        "iii", array($recompensa["reputacao"], $recompensa["reputacao"], $userDetails->tripulacao["id"]));
}

$connection->run("UPDATE tb_usuarios SET berries = berries + ?, presente_diario_obtido = 1, presente_diario_count = presente_diario_count + 1 WHERE id = ?",
    "ii", array($recompensa["berries"], $userDetails->tripulacao["id"]));

echo "Você recebeu o seu presente diário!";