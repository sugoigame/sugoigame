<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$borda = $protector->get_number_or_exit("borda");

$bordas = DataLoader::load("bordas");

if (!isset($bordas[$borda]) || $bordas[$borda]["preco"] <= 0) {
    $protector->exit_error("Borda inválida");
}

$protector->need_gold($bordas[$borda]["preco"]);

$connection->run("INSERT INTO tb_tripulacao_bordas (tripulacao_id, borda) VALUE (?, ?)",
    "ii", array($userDetails->tripulacao["id"], $borda));

$userDetails->reduz_gold($bordas[$borda]["preco"], "borda_personagem");

echo "-Parabéns! Você comprou uma nova Borda para sua tripulação!";