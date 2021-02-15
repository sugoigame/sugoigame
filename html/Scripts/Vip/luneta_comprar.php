<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_gold(PRECO_GOLD_LUNETA);

$tempoBase  = $userDetails->vip["luneta"] ? $userDetails->vip["luneta_duracao"] : atual_segundo();
$tempo      = $tempoBase + (30 * 86400); // 86400 = 1 dia
$connection->run("UPDATE tb_vip SET luneta = '1', luneta_duracao = ? WHERE id = ?", 'ii', [
    $tempo,
    $usuario['id']
]);

$userDetails->reduz_gold(PRECO_GOLD_LUNETA, "luneta");

echo("-Parabens!<br>Você acabou de comprar uma luneta!");