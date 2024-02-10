<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if (! $inally) {

    echo "#Você não faz parte de uma Aliança";
    exit();
}
$item = $protector->get_number_or_exit("item");
$tipo = $protector->get_number_or_exit("tipo");

$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$cargo = $result->fetch_array();

$query = "SELECT * FROM tb_alianca_shop WHERE cod='$item' AND tipo='$tipo' AND (faccao='" . $usuario["faccao"] . "' OR faccao='3')";
$result = $connection->run($query);
$cont = $result->count();

if ($cont != 0) {

    $item_info = $result->fetch_array();
    if ($usuario["alianca"]["lvl"] < $item_info["lvl"] and $cargo["cooperacao"] < $item_info["preco"]) {

        echo ("#Você não cumpre os requisitos para comprar este item.");
        exit();
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
    $result = $connection->run($query);
    $cont = $result->count();
    if ($cont < $usuario["capacidade_iventario"]) {
        $ivent = [];
        for ($i = 0; $sql = $result->fetch_array(); $i++) {
            $ivent[$i] = $sql;
        }
        $jatem = FALSE;
        for ($x = 0; $x < sizeof($ivent); $x++) {
            if ($ivent[$x]["cod_item"] == $item and $ivent[$x]["tipo_item"] == $tipo) {
                $jatem = TRUE;
                $quant = $ivent[$x]["quant"] + 1;
            }
        }
        $coop = $cargo["cooperacao"] - $item_info["preco"];
        $query = "UPDATE tb_alianca_membros SET cooperacao='$coop' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

        if ($jatem and ($tipo == 1 or $tipo == 7)) {
            $query = "UPDATE tb_usuario_itens SET 
				quant='$quant' WHERE cod_item='$item' AND tipo_item='$tipo' AND id='" . $usuario["id"] . "' LIMIT 1";
            $connection->run($query) or die("Nao foi possivel adicionad o item");
        } else {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
				VALUES ('" . $usuario["id"] . "', '$item', '$tipo')";
            $connection->run($query) or die("Nao foi possivel cadastrar o item");
        }

        echo ("Item comprado");
    } else {

        echo ("#O limite de itens no Iventário é de apenas 10.");
    }
} else {

    echo ("#você nao pode comprar este item.");
}
?>

