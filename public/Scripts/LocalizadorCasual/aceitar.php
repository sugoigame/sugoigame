<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (!$userDetails->fila_coliseu) {
    $protector->exit_error("Você não está na fila");
}

if (!$userDetails->fila_coliseu["desafio"]) {
    $protector->exit_error("Você não foi desafiado");
}

if ($userDetails->fila_coliseu["desafio_aceito"]) {
    $protector->exit_error("Você já aceitou o desafio. Aguarde seu adversário");
}

$adversario_fila = $connection->run("SELECT * FROM tb_coliseu_fila WHERE desafio = ?",
    "i", array($userDetails->tripulacao["id"]));

if (!$adversario_fila->count()) {
    $protector->exit_error("Seu adversário não recebeu o desafio");
}
$adversario_fila = $adversario_fila->fetch_array();

$connection->run("UPDATE tb_coliseu_fila SET desafio_aceito = 1 WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

if (!$adversario_fila["desafio_aceito"]) {
    echo "Desafio aceito! Aguarde seu adversário...";
} else {
    header("location:../../Scripts/Mapa/mapa_atacar.php?id=" . $userDetails->fila_coliseu["desafio"] . "&tipo=" . $userDetails->fila_coliseu["desafio_tipo"]);
}