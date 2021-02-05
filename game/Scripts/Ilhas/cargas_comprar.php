<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$recompensa_id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("loja_cargas");

if (!isset($recompensas[$recompensa_id])) {
    $protector->exit_error("Recompensa inválida");
}

$recompensa = $recompensas[$recompensa_id];

$cargas = $userDetails->get_item(CARGA_ROUBADA_ID, TIPO_ITEM_REAGENT);

if (!$cargas || $cargas["quant"] < $recompensa["preco"]) {
    $protector->exit_error("Você não possui cargas roubadas suficientes");
}

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

        $userDetails->add_equipamento_by_cod($recompensa["cod_item"]);
    }
}

if (isset($recompensa["haki"])) {
    $userDetails->haki_for_all($recompensa["haki"]);
}

if (isset($recompensa["xp"])) {
    $userDetails->xp_for_all($recompensa["xp"]);
}
if (isset($recompensa["fa"])) {
    $connection->run("UPDATE tb_personagens SET fama_ameaca = tb_personagens.fama_ameaca + ? WHERE id = ? AND ativo = 1",
        "ii", array($recompensa["fa"], $userDetails->tripulacao["id"]));
}

if (isset($recompensa["dobroes"])) {
    $connection->run("UPDATE tb_conta SET dobroes = dobroes + ? WHERE conta_id = ?",
        "ii", array($recompensa["dobroes"], $userDetails->conta["conta_id"]));
}
if (isset($recompensa["alcunha"])) {
    $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
        "ii", array($userDetails->capitao["cod"], $recompensa["alcunha"]));
}
if (isset($recompensa["skin"])) {
    $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
        "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
}
if (isset($recompensa["effect"])) {
    $userDetails->add_effect($recompensa["effect"]);
}

$userDetails->reduz_item(CARGA_ROUBADA_ID, TIPO_ITEM_REAGENT, $recompensa["preco"]);

echo "Você recebeu sua recompensa!";