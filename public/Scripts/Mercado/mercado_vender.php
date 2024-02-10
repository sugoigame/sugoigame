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
if (! isset($_GET["tipo"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$item = $protector->get_number_or_exit("item");
$tipo = $protector->get_number_or_exit("tipo");
if (! preg_match("/^[\d]+$/", $item)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $tipo)) {

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
    $mod = 0.5;

    if ($tipo == 0) {
        $query = "SELECT * FROM tb_item_acessorio WHERE cod_acessorio='$item'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $preco = preco_venda_acessorio($item_info);
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= preco_compra_acessorio($item_info)) {
                $preco = preco_compra_acessorio($item_info) - 1;
            }
        }
    } else if ($tipo == 3) {
        $query = "SELECT * FROM tb_item_navio_casco WHERE cod_casco='$item'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
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
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
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
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $preco = $item_info["preco"];
        $preco = $preco * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= $item_info["preco"]) {
                $preco = $item_info["preco"] - 1;
            }
        }
    } else if ($tipo == 8 or $tipo == 9 or $tipo == 10) {
        $preco = 6500000 * $mod;
        if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
            $preco += $aumento * $preco;
            if ($preco >= 6500000) {
                $preco = 6500000 - 1;
            }
        }
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo'";
    $result = $connection->run($query);
    $cont = $result->count();
    if ($cont != 0) {
        $berries = $usuario["berries"] + $preco;
        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel receber o dinheiro");

        $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='$tipo' LIMIT 1";
        $connection->run($query) or die("Nao foi possivel remover o item");


        echo ("-Item vendido");
    } else {

        echo ("#Você não tem esse item em seu iventário.");
    }
} else {

    echo ("#Você precisa estar logado para executar essa ação.");
}
?>

