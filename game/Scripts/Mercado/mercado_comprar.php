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
$item = mysql_real_escape_string($_GET["item"]);
if (!preg_match("/^[\d]/", $item)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

$protector->need_navio();

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='0'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    if ($cont != 0) {
        $query = "SELECT * FROM tb_item_acessorio WHERE cod_acessorio='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = mysql_query($query);
        $sql = mysql_fetch_array($result);
        $mod = $sql["mod"];

        $preco = preco_compra_acessorio($item_info);

        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = mysql_query($query);
            $cont = mysql_num_rows($result);
            if ($cont < $usuario["capacidade_iventario"]) {
                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
					VALUES ('" . $usuario["id"] . "', '$item', '0')";
                mysql_query($query) or die("Nao foi possivel cadastrar o item");

                mysql_close();
                echo("-Item comprado!");
            } else {
                mysql_close();
                echo("#O limite de itens no Iventário é de apenas " . $usuario["capacidade_iventario"]);
            }
        } else {
            mysql_close();
            echo("#Você não tem dinheiro suficiente para comprar este item.");
        }
    } else {
        mysql_close();
        echo("#Essa ilha não vende esse item.");
    }
} else {
    mysql_close();
    echo("#Você precisa estar logado para executar essa ação.");
}
?>