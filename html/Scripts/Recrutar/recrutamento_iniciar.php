<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    echo("#Você está em combate!");
    exit();
}
if (!$inrecrute AND $inmissao) {
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($usuario["recrutando"] != 0) {
    echo("#Você ja iniciou uma procura!");
    exit();
}
if ($usuario["recrutando"] != 0) {
    echo("#Você ja iniciou uma procura!");
    exit();
}

$result = $connection->run("SELECT * FROM tb_navio WHERE cod_navio = ?", "i", [
    $usuario["navio"]
]);
$navio = $result->fetch_array();

if (sizeof($personagem) >= $navio["limite"]) {
    echo("#Você ja possui o limite de tripulantes!");
    exit();
}
$time = (int)((sizeof($personagem) * 60) + atual_segundo());

$connection->run("UPDATE tb_usuarios SET recrutando = ? WHERE id = ?", 'ii', [
    $time,
    $usuario["id"]
]);

echo("Recrutamento iniciado.");