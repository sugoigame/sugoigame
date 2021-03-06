<?php
require "../../Includes/conectdb.php";

$protector->exit_error("Evento indisponivel");
$protector->need_tripulacao();

$id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("recompensas_amizade");

if (!isset($recompensas[$id])) {
    $protector->exit_error("Recompensa invalida");
}

$recompensa = $recompensas[$id];

$recompensado = $connection->run("SELECT count(*) AS total FROM tb_evento_amizade_recompensa WHERE tripulacao_id = ? AND recompensa_id = ?",
    "ii", array($userDetails->tripulacao["id"], $id))->fetch_array()["total"];

if ($recompensado) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

$derrotados = $connection->run("SELECT count(id) AS total FROM tb_evento_amizade_brindes WHERE tripulacao_id= ?",
    "i", $userDetails->tripulacao["id"])->fetch_array()["total"];

if ($derrotados < $recompensa["minimo"]) {
    $protector->exit_error("Você ainda não pode receber essa recompensa");
}

switch ($recompensa["tipo"]) {
    case "berries":
        $connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
            "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
        $msg = "Você recebeu " . mascara_berries($recompensa["quant"]) . " Berries";
        break;
    case "dobrao":
        $connection->run("UPDATE tb_conta SET dobroes = dobroes + ? WHERE conta_id = ?",
            "ii", array($recompensa["quant"], $userDetails->conta["conta_id"]));
        $msg = "Você recebeu " . $recompensa["quant"] . " Dobrões de Ouro";
        break;
    case "haki":
        $userDetails->haki_for_all($recompensa["quant"]);
        $msg = "Sua tripulação recebeu " . $recompensa["quant"] . " pontos de Haki";
        break;
    case "akuma":
        if (!$userDetails->add_item(rand(100, 110), rand(8, 10), 1, true)) {
            $protector->exit_error("Seu inventário está lotado. Libere espaço antes de pegar sua recompensa");
        }
        $msg = "Você recebeu uma Akuma no Mi";
        break;
    case "alcunha":
        $connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?, ?)",
            "ii", array($userDetails->capitao["cod"], $recompensa["cod_titulo"]));
        $msg = "Você recebeu uma nova Alcunha";
        break;
    case "skin":
        $connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin) VALUE (?, ?, ?)",
            "iii", array($userDetails->tripulacao["id"], $recompensa["img"], $recompensa["skin"]));
        $msg = "Você recebeu uma nova recompensa";
        break;
    case "effect":
        $userDetails->add_effect($recompensa["effect"]);
        $msg = "Você recebeu a animação de habilidade " . $recompensa["effect"];
        break;
    case "moeda_evento":
        $connection->run("UPDATE tb_usuarios SET moedas_evento = moedas_evento + ? WHERE id = ?",
            "ii", array($recompensa["quant"], $userDetails->tripulacao["id"]));
        $msg = "Você recebeu uma nova recompensa";
        break;
}

$userDetails->xp_for_all(500);

$connection->run("INSERT INTO tb_evento_amizade_recompensa (tripulacao_id, recompensa_id) VALUE (?, ?)",
    "ii", array($userDetails->tripulacao["id"], $id));

echo "-$msg";