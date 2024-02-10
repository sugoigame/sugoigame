<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (! isset($_GET["item"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! isset($_GET["tudo"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$item = $protector->get_number_or_exit("item");
$tudo = $protector->get_number_or_exit("tudo");
if (! preg_match("/^[\d]+$/", $item)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $tudo)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($conect) {
    $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
    $result = $connection->run($query);
    $sql = $result->fetch_array();
    $mod = $sql["mod_venda"];

    $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$item'";
    $result = $connection->run($query);
    if ($result->count() == 0) {

        echo ("#Item não encontrado.");
        exit();
    }
    $item_info = $result->fetch_array();

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
    $result = $connection->run($query);
    if ($result->count() == 0) {

        echo ("#Item não encontrado.");
        exit();
    }
    $item_quant = $result->fetch_array();

    $preco = $item_info["preco"] * $mod;
    if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
        $preco += $aumento * $preco;

        if ($preco >= $item_info["preco"]) {
            $preco = $item_info["preco"] - 1;
        }
    }

    if ($tudo == 0) {
        $quant = $item_quant["quant"] - 1;
        if ($quant <= 0) {
            $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
            $connection->run($query) or die("Nao foi possivel vender o item");
        } else {
            $query = "UPDATE tb_usuario_itens SET quant='$quant'
				WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
            $connection->run($query) or die("Nao foi possivel vender o item");

        }

        $berries = $usuario["berries"] + $preco;

        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel vender o item");
    } else {
        $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
        $connection->run($query) or die("Nao foi possivel vender o item");

        $preco *= $item_quant["quant"];

        $berries = $usuario["berries"] + $preco;

        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel vender o item");
    }
    echo "-item vendido";
} else {

    echo ("#Você precisa estar logado para executar essa ação.");
}
?>

