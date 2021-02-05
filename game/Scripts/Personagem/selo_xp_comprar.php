<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->get_tripulante_or_exit("cod");

$preco = preco_selo_exp($pers);

if ($pers["xp"] < $preco) {
    $protector->exit_error("Você não possui experiência suficiente.");
}

$connection->run("UPDATE tb_personagens SET xp = xp - ?, selos_xp = selos_xp + 1 WHERE cod = ?",
    "ii", array($preco, $pers["cod"]));

$response->send_share_msg($pers["nome"] . " recebeu um Selo de Experiência!");