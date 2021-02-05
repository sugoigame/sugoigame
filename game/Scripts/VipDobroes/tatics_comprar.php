<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_dobroes(PRECO_DOBRAO_TATICAS);

$tempo_base = $userDetails->vip["tatic"] ? $userDetails->vip["tatic_duracao"] : atual_segundo();
$tempo = $tempo_base + 2592000;
$query = "UPDATE tb_vip SET tatic='1', tatic_duracao='$tempo' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel cadastrar o item");

$userDetails->reduz_dobrao(PRECO_DOBRAO_TATICAS, "taticas");

echo("-Parabens!<br>Você acabou de ativar suas táticas de combate!");