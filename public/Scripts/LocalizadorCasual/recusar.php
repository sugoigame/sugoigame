<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if (!$userDetails->fila_coliseu) {
    $protector->exit_error("Você não está na fila");
}

if (!$userDetails->fila_coliseu["desafio"]) {
    $protector->exit_error("Você não foi desafiado");
}

$connection->run("UPDATE tb_coliseu_fila SET desafio = NULL, desafio_momento = NULL, desafio_aceito = 0 WHERE id = ?",
    "i", array($userDetails->fila_coliseu["desafio"]));

$connection->run("DELETE FROM tb_coliseu_fila WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));
echo("%localizadorCasual");
