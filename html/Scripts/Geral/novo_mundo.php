<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit();
}
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if ((($usuario["ilha"] == 42 AND $usuario["faccao"] == 1)
        OR ($usuario["ilha"] == 43 AND $usuario["faccao"] == 0))
    AND $personagem[0]["lvl"] >= 45
) {
    $query = "UPDATE tb_usuarios SET x='20', y='166', mar_visivel = 0
		WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("Nao foi possivel finalizar transporte");
}

mysql_close();
echo("Bem Vindo ao Novo Mundo!");
?>