<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if (!isset($_GET["tipo"]) OR !isset($_GET["cod"]) OR !isset($_GET["quant"])) {
    mysql_close();
    echo("#Valores não informados");
    exit();
}
$tipo = mysql_real_escape_string($_GET["tipo"]);
$cod = mysql_real_escape_string($_GET["cod"]);
$quant = mysql_real_escape_string($_GET["quant"]);

if (!preg_match("/^[\d]/", $tipo) OR
    !preg_match("/^[\d]/", $cod) OR
    !preg_match("/^[\d]/", $quant)
) {
    mysql_close();
    echo("#Você informou algo inválido");
    exit();
}

if ($tipo == 16 OR $tipo == 17) {
    if ($tipo == 16) {
        if ($quant != 10) {
            mysql_close();
            echo("#Você não tem dinheiro suficiente");
            exit();
        }
        $beries = 10000;
        if ($usuario["berries"] < $beries) {
            mysql_close();
            echo("#Você não tem dinheiro suficiente");
            exit();
        }

        $beries = $usuario["berries"] - $beries;
        $query = "UPDATE tb_usuarios SET berries='$beries' WHERE id='" . $usuario["id"] . "'";
        mysql_query($query) or die("nao foi possivel comprar a isca");
    } else {
        if ($quant == 10) {
            $beries = PRECO_DOBRAO_ISCA_10;
        } else if ($quant == 130) {
            $beries = PRECO_DOBRAO_ISCA_130;
        } else {
            mysql_close();
            echo("#Quantidade invalida");
            exit();
        }
        $protector->need_dobroes($beries);

        $userDetails->reduz_dobrao($beries, "isca_dourada");
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
    $result = mysql_query($query);

    if (mysql_num_rows($result) == 0) $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '0', '$tipo', '$quant')";
    else {
        $quanttem = mysql_fetch_array($result);
        $nquant = $quanttem["quant"] + $quant;
        $query = "UPDATE tb_usuario_itens SET quant='$nquant', novo = 1 WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
    }
    mysql_query($query) or die("nao foi possivel comprar a isca");
}

mysql_close();
echo("?Item comprado!");
?>