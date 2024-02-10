<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (! isset($_GET["item"]) or ! isset($_GET["quant"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$item = $protector->get_number_or_exit("item");
$quant = $protector->get_number_or_exit("quant");
if (! preg_match("/^[\d]/", $item) or ! preg_match("/^[\d]/", $quant) or $quant == 0) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
$protector->need_navio();

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='15'";
    $result = $connection->run($query);
    $cont = $result->count();

    if ($cont != 0) {
        $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$item'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = $connection->run($query);
        $sql = $result->fetch_array();
        $mod = $sql["mod"];

        $preco = $item_info["preco"] * $mod;
        $preco *= $quant;
        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = $connection->run($query);
            $cont = $result->count();
            if ($cont < $usuario["capacidade_iventario"]) {
                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

                $query = "SELECT * FROM tb_usuario_itens 
					WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
                $result = $connection->run($query);
                if ($result->count() != 0) {
                    $item_quant = $result->fetch_array();

                    $quant = $item_quant["quant"] + $quant;

                    $query = "UPDATE tb_usuario_itens SET quant='$quant' 
						WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='15'";
                    $connection->run($query) or die("Nao foi possivel comprar o item");
                } else {
                    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)  
						VALUES ('" . $usuario["id"] . "', '$item', '15', '$quant')";
                    $connection->run($query) or die("Nao foi possivel cadastrar o item");
                }

                echo ("-Item comprado!");
            } else {

                echo ("#Seu inventário está lotado");
            }
        } else {

            echo ("#Você não tem dinheiro suficiente para comprar este item.");
        }
    } else {

        echo ("#Essa ilha não vende esse item.");
    }
} else {

    echo ("#Você precisa estar logado para executar essa ação.");
}
?>

