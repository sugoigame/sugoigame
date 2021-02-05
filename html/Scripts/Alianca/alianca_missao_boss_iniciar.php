<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_an_ally();

if (!can_ini_missao($userDetails->ally, $userDetails->ally["autoridade"])) {
    $protector->exit_error("Você não tem permissão para iniciar uma missão");
}

$boss_id = $protector->get_alphanumeric_or_exit("boss");

$in_missao = $connection->run("SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ?", "i", array($userDetails->ally["cod_alianca"]));

if ($in_missao->count()) {
    $protector->exit_error("Sua aliança já está sob missão");
}

$connection->run("INSERT INTO tb_alianca_missoes (cod_alianca, fim, boss_id) VALUE (?, ?, ?)",
    "iii", array($userDetails->ally["cod_alianca"], 1000000, $boss_id));

echo "Missão iniciada!";