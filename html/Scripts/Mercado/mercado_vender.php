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
if (!isset($_GET["tipo"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$item = mysql_real_escape_string($_GET["item"]);
$tipo = mysql_real_escape_string($_GET["tipo"]);
if (!preg_match("/^[\d]+$/", $item)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $tipo)) {
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
    $mod = 0.5;

    if ($tipo == 0) {
        $query = "SELECT * FROM tb_item_acessorio WHERE cod_acessorio='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $preco = preco_venda_acessorio($item_info);
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= preco_compra_acessorio($item_info)) {
                $preco = preco_compra_acessorio($item_info) - 1;
            }
        }
    } else if ($tipo == 3) {
        $query = "SELECT * FROM tb_item_navio_casco WHERE cod_casco='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $preco = $item_info["preco"];
        $preco = $preco * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= $item_info["preco"]) {
                $preco = $item_info["preco"] - 1;
            }
        }
    } else if ($tipo == 4) {
        $query = "SELECT * FROM tb_item_navio_leme WHERE cod_leme='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $preco = $item_info["preco"];
        $preco = $preco * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= $item_info["preco"]) {
                $preco = $item_info["preco"] - 1;
            }
        }
    } else if ($tipo == 5) {
        $query = "SELECT * FROM tb_item_navio_velas WHERE cod_velas='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $preco = $item_info["preco"];
        $preco = $preco * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= $item_info["preco"]) {
                $preco = $item_info["preco"] - 1;
            }
        }
    } else if ($tipo == 8 OR $tipo == 9 OR $tipo == 10) {
        $preco = 6500000 * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= 6500000) {
                $preco = 6500000 - 1;
            }
        }
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);
    if ($cont != 0) {
        $berries = $usuario["berries"] + $preco;
        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        mysql_query($query) or die("Nao foi possivel receber o dinheiro");

        $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
        mysql_query($query) or die("Nao foi possivel remover o item");

        mysql_close();
        echo("-Item vendido");
    } else {
        mysql_close();
        echo("#Você não tem esse item em seu iventário.");
    }
} else {
    mysql_close();
    echo("#Você precisa estar logado para executar essa ação.");
}
?>