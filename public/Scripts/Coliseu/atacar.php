<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (!is_coliseu_aberto()) {
    $protector->exit_error("O Coliseu está fechado");
}

$tamanho_time = $connection->run("SELECT count(*) AS total FROM tb_personagens WHERE id = ? AND time_coliseu = 1",
    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];

if ($tamanho_time != TAMANHO_TIME_COLISEU) {
    $protector->exit_error("Seu time não tem o tamanho adequado para participar");
}

if ($userDetails->fila_coliseu) {
    $connection->run("UPDATE tb_coliseu_fila SET busca_coliseu = 1 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
} else {
    $connection->run("INSERT INTO tb_coliseu_fila (id, lvl, busca_coliseu) VALUE (?,?, 1)",
        "ii", array($userDetails->tripulacao["id"], $userDetails->lvl_mais_forte));
}

echo "%coliseu";