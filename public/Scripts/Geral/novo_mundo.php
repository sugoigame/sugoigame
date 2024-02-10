<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if ((($usuario["ilha"] == 42 and $usuario["faccao"] == 1)
    or ($usuario["ilha"] == 43 and $usuario["faccao"] == 0))
    and $personagem[0]["lvl"] >= 45
) {
    $query = "UPDATE tb_usuarios SET x='20', y='166', mar_visivel = 0
		WHERE id='" . $usuario["id"] . "'";
    $connection->run($query) or die("Nao foi possivel finalizar transporte");
}


echo ("Bem Vindo ao Novo Mundo!");
?>

