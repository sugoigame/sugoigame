<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$tipo = $protector->get_alphanumeric_or_exit("tipo");
$item = $protector->get_alphanumeric_or_exit("item");

function get_lvl_query() {
    global $userDetails;

    if ($userDetails->lvl_mais_forte < 25) {
        return "lvl < 25";
    } else if ($userDetails->lvl_mais_forte < 35) {
        return "lvl >= 25 AND lvl < 35";
    } else if ($userDetails->lvl_mais_forte < 45) {
        return "lvl >= 35 AND lvl < 45";
    } else {
        return "lvl >= 45";
    }
}

$result = $connection->run("SELECT cp FROM tb_coliseu_cp WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

$CP = $result->count() ? $result->fetch_array()["cp"] : 0;

$result = $connection->run("SELECT cp FROM tb_coliseu_ranking WHERE " . get_lvl_query() . " ORDER BY cp DESC LIMIT 1");

$cpBase = $result->count() ? $result->fetch_array()["cp"] : 1;
if ($cpBase < 100) {
    $cpBase = 100;
}

function precoCP($porcent) {
    global $cpBase;
    $total = (int)(($porcent / 100) * $cpBase);
    return ($total > 1) ? $total : 1;
}

if ($tipo == "gold") {
    $preco = precoCP(15);
    if ($CP < $preco) {
        $protector->exit_error("Voce não possui CP suficiente.");
    }

    $connection->run("UPDATE tb_conta SET gold = gold + 1 WHERE conta_id = ?",
        "i", array($userDetails->conta["conta_id"]));

    $connection->run("UPDATE tb_coliseu_cp SET cp = cp - ? WHERE id = ?",
        "ii", array($preco, $userDetails->tripulacao["id"]));

    echo "Você recebeu 1 Moeda de Ouro";
} else {
    $recompensas = DataLoader::load("loja_coliseu");

    if (!isset($recompensas[$item])) {
        $protector->exit_error("Recompensa invalida");
    }
    $recompensa = $recompensas[$item];

    $preco = precoCP($recompensa["preco"]);
    if ($CP < $preco) {
        $protector->exit_error("Voce não possui CP suficiente.");
    }

    switch ($recompensa["tipo"]) {
        case "berries":
            $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
                "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
            $msg = "Você recebeu " . mascara_berries($recompensa["quant"]) . " Berries";
            break;
        case "akuma":
            if (!$userDetails->add_item(rand(100, 110), rand(8, 10), 1, true)) {
                $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
            }
            $msg = "Você recebeu uma Akuma no Mi";
            break;
        case "reagent":
            if (!$userDetails->can_add_item()) {
                $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
            }
            $userDetails->add_item($recompensa["cod_item"], TIPO_ITEM_REAGENT, $recompensa["quant"]);

            $msg = "Você recebeu sua recompensa!";
            break;
        case "alcunha":
            $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
                "ii", array($userDetails->capitao["cod"], $recompensa["cod_titulo"]));
            $msg = "Você recebeu uma nova Alcunha";
            break;
        case "skin":
            $existe = $connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ? AND skin = ?",
                "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
            if ($existe->count()) {
                $protector->exit_error("Você já possui essa aparência nessa tripulação");
            }
            $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
                "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
            $msg = "Você recebeu uma nova recompensa";
            break;
    }

    $connection->run("UPDATE tb_coliseu_cp SET cp = cp - ? WHERE id = ?",
        "ii", array($preco, $userDetails->tripulacao["id"]));

    echo "Item Recebido!";
}
