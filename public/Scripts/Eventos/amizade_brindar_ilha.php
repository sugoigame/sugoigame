<?php
require "../../Includes/conectdb.php";

$protector->exit_error("Evento indisponivel");
$protector->need_tripulacao();
$protector->must_be_in_ilha();

$result = $connection->run("SELECT * FROM tb_evento_amizade_brindes WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

if (!$result->count()) {
    if (!$userDetails->can_add_item()) {
        $protector->exit_error("Seu inventário está lotado. Libere um espaço para receber a recompensa.");
    }
    $connection->run("INSERT INTO tb_evento_amizade_brindes (tripulacao_id, ilha) VALUE (?, ?)",
        "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

    $userDetails->add_item(134, TIPO_ITEM_REAGENT, 1);
}


echo "Kanpai!";

