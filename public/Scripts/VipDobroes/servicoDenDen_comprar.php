<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if (! isset($_GET["tipo"]) or ! isset($_GET["cod"]) or ! isset($_GET["quant"])) {

    echo ("#Valores não informados");
    exit();
}
$tipo = $protector->get_number_or_exit("tipo");
$cod = $protector->get_number_or_exit("cod");
$quant = $protector->get_number_or_exit("quant");

if (! preg_match("/^[\d]/", $tipo) or
    ! preg_match("/^[\d]/", $cod) or
    ! preg_match("/^[\d]/", $quant)
) {

    echo ("#Você informou algo inválido");
    exit();
}

if ($tipo == 16 or $tipo == 17) {
    if ($tipo == 16) {
        if ($quant != 10) {

            echo ("#Você não tem dinheiro suficiente");
            exit();
        }
        $beries = 10000;
        if ($usuario["berries"] < $beries) {

            echo ("#Você não tem dinheiro suficiente");
            exit();
        }

        $beries = $usuario["berries"] - $beries;
        $query = "UPDATE tb_usuarios SET berries='$beries' WHERE id='" . $usuario["id"] . "'";
        $connection->run($query) or die("nao foi possivel comprar a isca");
    } else {
        if ($quant == 10) {
            $beries = PRECO_DOBRAO_ISCA_10;
        } else if ($quant == 130) {
            $beries = PRECO_DOBRAO_ISCA_130;
        } else {

            echo ("#Quantidade invalida");
            exit();
        }
        $protector->need_dobroes($beries);

        $userDetails->reduz_dobrao($beries, "isca_dourada");
    }

    $query = "SELECT * FROM tb_usuario_itens WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
    $result = $connection->run($query);

    if ($result->count() == 0)
        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '0', '$tipo', '$quant')";
    else {
        $quanttem = $result->fetch_array();
        $nquant = $quanttem["quant"] + $quant;
        $query = "UPDATE tb_usuario_itens SET quant='$nquant', novo = 1 WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
    }
    $connection->run($query) or die("nao foi possivel comprar a isca");
}


echo ("?Item comprado!");
?>

