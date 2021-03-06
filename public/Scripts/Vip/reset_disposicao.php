<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_gold(PRECO_GOLD_RESET_DISPOSICAO);

$query = "UPDATE tb_usuarios SET disposicao='10000' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel trocar de faccao");

$userDetails->reduz_gold(PRECO_GOLD_RESET_DISPOSICAO, "resetar_disposicao");

echo("-Disposição restaurada");
mysql_close();
