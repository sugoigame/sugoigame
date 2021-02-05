<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!isset($_GET["item"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["tudo"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$item = mysql_real_escape_string($_GET["item"]);
$tudo = mysql_real_escape_string($_GET["tudo"]);
if (!preg_match("/^[\d]+$/", $item)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $tudo)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($conect) {
    $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
    $result = mysql_query($query);
    $sql = mysql_fetch_array($result);
    $mod = $sql["mod_venda"];

    $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$item'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        mysql_close();
        echo("#Item não encontrado.");
        exit();
    }
    $item_info = mysql_fetch_array($result);

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        mysql_close();
        echo("#Item não encontrado.");
        exit();
    }
    $item_quant = mysql_fetch_array($result);

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
            mysql_query($query) or die("Nao foi possivel vender o item");
        } else {
            $query = "UPDATE tb_usuario_itens SET quant='$quant'
				WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
            mysql_query($query) or die("Nao foi possivel vender o item");

        }

        $berries = $usuario["berries"] + $preco;

        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        mysql_query($query) or die("Nao foi possivel vender o item");
    } else {
        $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
        mysql_query($query) or die("Nao foi possivel vender o item");

        $preco *= $item_quant["quant"];

        $berries = $usuario["berries"] + $preco;

        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        mysql_query($query) or die("Nao foi possivel vender o item");
    }
    echo "-item vendido";
} else {
    mysql_close();
    echo("#Você precisa estar logado para executar essa ação.");
}
?>