<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$status = $connection->run("SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ?",
    "i", array($userDetails->tripulacao["id"]));

if (!$status->count()) {
    $protector->exit_error("Você não foi convocado para o torneio");
}

$status = $status->fetch_array();

$connection->run("UPDATE tb_torneio_inscricao SET confirmacao = ?  WHERE tripulacao_id = ?",
    "ii", array($status["confirmacao"] ? 0 : 1, $userDetails->tripulacao["id"]));

echo "%torneio";