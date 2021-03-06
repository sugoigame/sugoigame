<?php
require "../../Includes/conectdb.php";

if ($userDetails->tripulacao) {

    $desafio_amigavel = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?",
        "i", array($userDetails->tripulacao["id"]));

    $retorno = array(
        "msgBoxClear" => has_mensagem(),
        "inCombate" => $userDetails->in_combate,
        "amigavel" => $desafio_amigavel->count() ? $desafio_amigavel->fetch_array()["desafiante_nome"] : null,
        "coliseu" => $userDetails->fila_coliseu && ($userDetails->fila_coliseu["desafio"] || attack_coliseu()),
        "torneio" => attack_torneio()
    );
    echo json_encode($retorno, JSON_NUMERIC_CHECK);
} else
    echo json_encode([], JSON_NUMERIC_CHECK);