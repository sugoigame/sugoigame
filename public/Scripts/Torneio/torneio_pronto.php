<?php
require "../../Includes/conectdb.php";

$protector->must_be_out_of_ilha();
$protector->must_has_pvp_on();

$torneio = get_current_torneio_poneglyph();

$chave = $connection->run(
    "SELECT * FROM tb_torneio_chave
    WHERE torneio_id = ?
    AND (tripulacao_1_id = ? OR tripulacao_2_id = ?)
    AND (em_andamento IS NULL OR em_andamento = 0)
    AND (finalizada IS NULL OR finalizada = 0)",
    "iii", [$torneio["id"], $userDetails->tripulacao["id"], $userDetails->tripulacao["id"]]
);

if (! $chave->count()) {
    $protector->exit_error("Batalha não encontrada");
}
$chave = $chave->fetch_array();

$posicao = $chave["tripulacao_1_id"] == $userDetails->tripulacao["id"] ? "1" : "2";

$outra_posicao = $posicao == "1" ? "2" : "1";

if ($chave["tripulacao_" . $posicao . "_pronto"]) {
    $protector->exit_error("Já está pronto!");
}

if ($chave["tripulacao_" . $outra_posicao . "_pronto"]) {
    // inicia batalha
    inicia_combate($chave["tripulacao_" . $outra_posicao . "_id"], TIPO_TORNEIO, $chave);
}

$connection->run(
    "UPDATE tb_torneio_chave SET tripulacao_" . $posicao . "_pronto = 1 WHERE id = ?",
    "i", [$chave["id"]]
);

if ($chave["tripulacao_" . $outra_posicao . "_pronto"]) {
    echo ("%combate");
} else {
    echo ":";
}
