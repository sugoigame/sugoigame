<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if ($userDetails->fila_coliseu && ($userDetails->fila_coliseu["busca_casual"] || $userDetails->fila_coliseu["busca_coliseu"])) {
    $connection->run("UPDATE tb_coliseu_fila SET busca_competitivo = 0 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
} else {
    $connection->run("DELETE FROM tb_coliseu_fila WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
}

echo("%localizadorCompetitivo");
