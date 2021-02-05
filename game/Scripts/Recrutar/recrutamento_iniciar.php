<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    mysql_close();
    echo("#Você está em combate!");
    exit();
}
if (!$inrecrute AND $inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($usuario["recrutando"] != 0) {
    mysql_close();
    echo("#Você ja iniciou uma procura!");
    exit();
}
if ($usuario["recrutando"] != 0) {
    mysql_close();
    echo("#Você ja iniciou uma procura!");
    exit();
}
$query = "SELECT * FROM tb_navio WHERE cod_navio='" . $usuario["navio"] . "'";
$result = mysql_query($query);
$navio = mysql_fetch_array($result);

if (sizeof($personagem) >= $navio["limite"]) {
    mysql_close();
    echo("#Você ja possui o limite de tripulantes!");
    exit();
}
$time = 60;
$time += atual_segundo();
$time = (int)$time;
$query = "UPDATE tb_usuarios SET recrutando='$time' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Erro ao iniciar recrutamento");

mysql_close();
echo("Recrutamento iniciado.");