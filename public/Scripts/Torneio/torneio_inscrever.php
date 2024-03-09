<?php
require "../../Includes/conectdb.php";

$protector->must_be_out_of_ilha();
$protector->must_has_pvp_on();

$torneio = get_current_torneio_poneglyph_completo();

if ($torneio["status"] !== TORNEIO_STATUS_AGUARDANDO) {
    $protector->exit_error("O torneio está indisponível.");
}

$coord = json_decode($torneio["coordenadas"], true)[$userDetails->ilha["mar"]];

if ($coord["x"] != $userDetails->tripulacao["x"] || $coord["y"] != $userDetails->tripulacao["y"]) {
    $protector->exit_error("Você não está na coordenada correta.");
}

$inscritos = get_inscritos_torneio_poneglyph($torneio);

if (count($inscritos) >= TORNEIO_LIMITE_PARTICIPANTES) {
    $protector->exit_error("O torneio já excedeu o limite de participantes.");
}

$connection->run(
    "INSERT INTO tb_torneio_inscricao (torneio_id, tripulacao_id) VALUES (?,?)",
    "ii", [$torneio["id"], $userDetails->tripulacao["id"]]
);

if (count($inscritos) === TORNEIO_LIMITE_PARTICIPANTES - 1) {
    inicia_torneio($torneio);
}

$connection->run(
    "UPDATE tb_usuarios SET mar_visivel = 0 WHERE id = ?",
    "i", [$userDetails->tripulacao["id"]]
);

echo ":";
