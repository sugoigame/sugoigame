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
$quant_compra = $protector->get_number_or_exit("quant");
if (! preg_match("/^[\d]/", $item) or ! preg_match("/^[\d]/", $quant_compra) or $quant_compra == 0) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
}

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='1'";
    $result = $connection->run($query);
    $cont = $result->count();

    if ($cont != 0) {
        $item_info = MapLoader::find("comidas", ["cod_comida" => $item]);

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = $connection->run($query);
        $sql = $result->fetch_array();
        $mod = $sql["mod"];

        $recupera = $item_info["hp_recuperado"] + $item_info["mp_recuperado"];
        $preco = $recupera * 60;
        $preco = $preco * $mod;
        $preco *= $quant_compra;

        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = $connection->run($query);
            $cont = $result->count();
            if ($cont < $usuario["capacidade_iventario"]) {
                if (! $userDetails->add_item($item, TIPO_ITEM_COMIDA, $quant_compra)) {
                    $protector->exit_error("Seu inventário está lotado");
                }

                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                $connection->run($query) or die("Nao foi possivel descontar o dinheiro");


                echo ("@Comida comprada.");
            } else {

                echo ("#Você atingiu o limite do inventário.");
            }
        } else {

            echo ("#Você não tem dinheiro suficiente para comprar este item.");
        }
    } else {

        echo ("#Essa ilha não vende esse item.");
    }
} else {

    echo ("@Você precisa estar logado para executar essa ação.");
}
?>

