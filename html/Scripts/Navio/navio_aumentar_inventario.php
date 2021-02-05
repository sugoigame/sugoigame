<?php

require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();

$carpinteiro = $userDetails->lvl_carpinteiro;

if (!$carpinteiro) {
    $protector->exit_error("Você precisa de um carpinteiro");
}

if (($userDetails->navio["capacidade_inventario"] - 55) >= ($userDetails->lvl_carpinteiro * 10)) {
    $protector->exit_error("Não é possível aprimorar seu inventario");
}

$preco = ((($userDetails->navio["capacidade_inventario"] - 55) / 10) + 1) * 100000;

if ($userDetails->tripulacao["berries"] < $preco) {
    $protector->exit_error("Você não possui berries o suficiente");
}

$connection->run("UPDATE tb_usuario_navio SET capacidade_inventario = capacidade_inventario + 10 WHERE id = ?",
    "i", $userDetails->tripulacao["id"]);

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

echo "Seu inventário foi aprimorado!";