<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();

$skin = $protector->get_number_or_exit("skin");
$tipo = $protector->get_enum_or_exit("tipo", array("gold", "dobrao", "credito"));

$preco = $tipo == "gold"
    ? $PRECO_GOLD_SKIN_NAVIO[$skin]
    : $PRECO_DOBRAO_SKIN_NAVIO[$skin];

if ($preco <= 0 && $tipo != "credito") {
    $protector->exit_error("Essa aparência não está a venda");
}

if ($tipo == "gold") {
    $protector->need_gold($preco);
} else if ($tipo == "dobrao") {
    $protector->need_dobroes($preco);
} else {
    if (!$userDetails->tripulacao["credito_skin_navio"]) {
        $protector->exit_error("Você não tem direito à aparências gratuitas");
    }
}

$connection->run("INSERT INTO tb_tripulacao_skin_navio (conta_id, tripulacao_id, skin_id) VALUE (?, ?, ?)",
    "iii", array($userDetails->conta["conta_id"], $userDetails->tripulacao["id"], $skin));

if ($tipo == "gold") {
    $userDetails->reduz_gold($preco, "skin_navio");
} else if ($tipo == "dobrao") {
    $userDetails->reduz_dobrao($preco, "skin_navio");
} else {
    $connection->run("UPDATE tb_usuarios SET credito_skin_navio = credito_skin_navio -1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
}

echo "-Parabéns! A aparência foi comprada!";

