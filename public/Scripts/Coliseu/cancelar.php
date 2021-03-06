<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if ($userDetails->fila_coliseu && $userDetails->fila_coliseu["busca_competitivo"]) {
    $connection->run("UPDATE tb_coliseu_fila SET busca_coliseu = 0, busca_casual = 0 WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
} else {
    $connection->run("DELETE FROM tb_coliseu_fila WHERE id = ?",
        "i", array($userDetails->tripulacao["id"]));
}
echo("%coliseu");
