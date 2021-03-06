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
if (!isset($_GET["item"]) OR !isset($_GET["quant"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$item = mysql_real_escape_string($_GET["item"]);
$quant = mysql_real_escape_string($_GET["quant"]);
if (!preg_match("/^[\d]/", $item) OR !preg_match("/^[\d]/", $quant) OR $quant == 0) {
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

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='15'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    if ($cont != 0) {
        $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = mysql_query($query);
        $sql = mysql_fetch_array($result);
        $mod = $sql["mod"];

        $preco = $item_info["preco"] * $mod;
        $preco *= $quant;
        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = mysql_query($query);
            $cont = mysql_num_rows($result);
            if ($cont < $usuario["capacidade_iventario"]) {
                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                $query = "SELECT * FROM tb_usuario_itens 
					WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
                $result = mysql_query($query);
                if (mysql_num_rows($result) != 0) {
                    $item_quant = mysql_fetch_array($result);

                    $quant = $item_quant["quant"] + $quant;

                    $query = "UPDATE tb_usuario_itens SET quant='$quant' 
						WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
                    mysql_query($query) or die("Nao foi possivel comprar o item");
                } else {
                    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)  
						VALUES ('" . $usuario["id"] . "', '$item', '15', '$quant')";
                    mysql_query($query) or die("Nao foi possivel cadastrar o item");
                }
                mysql_close();
                echo("-Item comprado!");
            } else {
                mysql_close();
                echo("#Seu inventário está lotado");
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