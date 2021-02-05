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
if (!$inally) {
    mysql_close();
    echo "#Você não faz parte de uma Aliança";
    exit();
}
if (!isset($_GET["item"]) OR !isset($_GET["tipo"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$item = mysql_real_escape_string($_GET["item"]);
$tipo = mysql_real_escape_string($_GET["tipo"]);
if (!preg_match("/^[\d]+$/", $item) OR !preg_match("/^[\d]+$/", $tipo)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$cargo = mysql_fetch_array($result);

$query = "SELECT * FROM tb_alianca_shop WHERE cod='$item' AND tipo='$tipo' AND (faccao='" . $usuario["faccao"] . "' OR faccao='3')";
$result = mysql_query($query);
$cont = mysql_num_rows($result);

if ($cont != 0) {

    $item_info = mysql_fetch_array($result);
    if ($usuario["alianca"]["lvl"] < $item_info["lvl"] AND $cargo["cooperacao"] < $item_info["preco"]) {
        mysql_close();
        echo("#Você não cumpre os requisitos para comprar este item.");
        exit();
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);
    if ($cont < $usuario["capacidade_iventario"]) {
        $ivent = [];
        for ($i = 0; $sql = mysql_fetch_array($result); $i++) {
            $ivent[$i] = $sql;
        }
        $jatem = FALSE;
        for ($x = 0; $x < sizeof($ivent); $x++) {
            if ($ivent[$x]["cod_item"] == $item AND $ivent[$x]["tipo_item"] == $tipo) {
                $jatem = TRUE;
                $quant = $ivent[$x]["quant"] + 1;
            }
        }
        $coop = $cargo["cooperacao"] - $item_info["preco"];
        $query = "UPDATE tb_alianca_membros SET cooperacao='$coop' WHERE id='" . $usuario["id"] . "'";
        mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

        if ($jatem AND ($tipo == 1 OR $tipo == 7)) {
            $query = "UPDATE tb_usuario_itens SET 
				quant='$quant' WHERE cod_item='$item' AND tipo_item='$tipo' AND id='" . $usuario["id"] . "' LIMIT 1";
            mysql_query($query) or die("Nao foi possivel adicionad o item");
        } else {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
				VALUES ('" . $usuario["id"] . "', '$item', '$tipo')";
            mysql_query($query) or die("Nao foi possivel cadastrar o item");
        }
        mysql_close();
        echo("Item comprado");
    } else {
        mysql_close();
        echo("#O limite de itens no Iventário é de apenas 10.");
    }
} else {
    mysql_close();
    echo("#você nao pode comprar este item.");
}
?>