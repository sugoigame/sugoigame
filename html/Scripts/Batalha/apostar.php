<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$combate_id = $protector->get_number_or_exit("cbt");
$aposta = $protector->get_number_or_exit("aposta");

$result = $connection->run("SELECT * FROM tb_combate WHERE combate = ?", "i", array($combate_id));

if (!$result->count()) {
    $protector->exit_error("Batalha não encontrada");
}

$combate = $result->fetch_array();

if ($combate["fim_apostas"]) {
    $protector->exit_error("O período de apostas para essa batalha já acabou");
}

if ($aposta != $combate["id_1"] && $aposta != $combate["id_2"]) {
    $protector->exit_error("Aposta Inválida");
}

$protector->need_berries($combate["preco_apostas"]);

$result = $connection->run("SELECT * FROM tb_combate_apostas WHERE combate_id = ? AND tripulacao_id = ?",
    "ii", array($combate_id, $userDetails->tripulacao["id"]));

if ($result->count()) {
    $protector->exit_error("Você já apostou nessa batalha");
}

$connection->run("INSERT INTO tb_combate_apostas (tripulacao_id, combate_id, aposta) VALUE (?, ?, ?)",
    "iii", array($userDetails->tripulacao["id"], $combate_id, $aposta));

$connection->run("UPDATE tb_combate SET premio_apostas = premio_apostas + ? WHERE combate = ?",
    "ii", array($combate["preco_apostas"], $combate_id));

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($combate["preco_apostas"], $userDetails->tripulacao["id"]));

echo "Sua aposta foi registrada";