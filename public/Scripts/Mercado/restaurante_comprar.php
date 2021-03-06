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
$quant_compra = mysql_real_escape_string($_GET["quant"]);
if (!preg_match("/^[\d]/", $item) OR !preg_match("/^[\d]/", $quant_compra) OR $quant_compra == 0) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
}

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='1'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    if ($cont != 0) {
        $query = "SELECT * FROM tb_item_comida WHERE cod_comida='$item'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = mysql_query($query);
        $sql = mysql_fetch_array($result);
        $mod = $sql["mod"];

        $recupera = $item_info["hp_recuperado"] + $item_info["mp_recuperado"];
        $preco = $recupera * 60;
        $preco = $preco * $mod;
        $preco *= $quant_compra;

        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = mysql_query($query);
            $cont = mysql_num_rows($result);
            if ($cont < $usuario["capacidade_iventario"]) {
                if (!$userDetails->add_item($item, TIPO_ITEM_COMIDA, $quant_compra)) {
                    $protector->exit_error("Seu inventário está lotado");
                }

                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                mysql_close();
                echo("@Comida comprada.");
            } else {
                mysql_close();
                echo("#O limite de itens no Iventário é de apenas 10.");
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
    echo("@Você precisa estar logado para executar essa ação.");
}
?>