<?php
require "../../Includes/conectdb.php";

$participante = $connection->run(
    "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
    "i", array($userDetails->tripulacao["id"])
);

if (!$participante->count()) {
    $protector->exit_error("Você não está participando do torneio");
}

$participante = $participante->fetch_array();

if ($participante["na_fila"]) {
    $connection->run("UPDATE tb_torneio_inscricao 
        SET tempo_na_fila = (unix_timestamp() - unix_timestamp(fila_entrada)) + IFNULL(tempo_na_fila, 0), na_fila = 0, fila_entrada = NULL 
        WHERE tripulacao_id = ?",
        "i", $userDetails->tripulacao["id"]);

    echo "-Você saiu da fila";
} else {
    $connection->run("UPDATE tb_torneio_inscricao SET na_fila = 1, fila_entrada = current_timestamp WHERE tripulacao_id = ?",
        "i", $userDetails->tripulacao["id"]);

    echo "-Você entrou na fila";
}