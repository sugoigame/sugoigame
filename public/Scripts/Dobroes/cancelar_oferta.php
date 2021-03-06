<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$oferta_id = $protector->get_number_or_exit("id");

$result = $connection->run("SELECT * FROM tb_dobroes_oferta WHERE id = ? AND tripulacao_id = ?",
    "ii", array($oferta_id, $userDetails->tripulacao["id"]));

if (!$result->count()) {
    $protector->exit_error("Oferta não encontrada ou já finalizada");
}

$oferta = $result->fetch_array();

$connection->run("UPDATE tb_conta SET dobroes_criados = dobroes_criados + ? WHERE conta_id = ?",
    "ii", array($oferta["quant"], $userDetails->conta["conta_id"]));

$connection->run("DELETE FROM tb_dobroes_oferta WHERE id = ?", "i", $oferta_id);

echo "Sua venda foi cancelada.";