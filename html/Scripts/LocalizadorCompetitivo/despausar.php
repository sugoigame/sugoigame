<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (!$userDetails->fila_coliseu) {
    $protector->exit_error("Você não está na fila");
}

$connection->run("UPDATE tb_coliseu_fila SET pausado = 0  WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

echo "%localizadorCompetitivo";