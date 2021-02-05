<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_gold(PRECO_GOLD_LUNETA);

$tempo_base = $userDetails->vip["luneta"] ? $userDetails->vip["luneta_duracao"] : atual_segundo();
$tempo = $tempo_base + 2592000;
$query = "UPDATE tb_vip SET luneta='1', luneta_duracao='$tempo' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel cadastrar o item");

$userDetails->reduz_gold(PRECO_GOLD_LUNETA, "luneta");

echo("-Parabens!<br>Você acabou de comprar uma luneta!");