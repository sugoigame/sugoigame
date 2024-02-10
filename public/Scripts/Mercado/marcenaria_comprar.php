<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {
    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}

$item = $protector->get_number_or_exit('item');
$tipo = $protector->get_number_or_exit('tipo');

if (! $inilha) {
    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='$tipo'";
    $result = $connection->run($query);
    $cont = $result->count();

    if ($tipo == 13)
        $cont++;

    if ($cont != 0) {
        if ($tipo == 11) {
            $query = "SELECT * FROM tb_navio WHERE cod_navio='" . $item . "'";
            $result = $connection->run($query);
            $item_info = $result->fetch_array();
        } else if ($tipo == 3) {
            $query = "SELECT * FROM tb_item_navio_casco WHERE cod_casco='" . $item . "'";
            $result = $connection->run($query);
            $item_info = $result->fetch_array();
        } else if ($tipo == 4) {
            $query = "SELECT * FROM tb_item_navio_leme WHERE cod_leme='" . $item . "'";
            $result = $connection->run($query);
            $item_info = $result->fetch_array();
        } else if ($tipo == 5) {
            $query = "SELECT * FROM tb_item_navio_velas WHERE cod_velas='" . $item . "'";
            $result = $connection->run($query);
            $item_info = $result->fetch_array();
        } else if ($tipo == 12) {
            $query = "SELECT * FROM tb_item_navio_canhao WHERE cod_canhao='" . $item . "'";
            $result = $connection->run($query);
            $item_info = $result->fetch_array();
        } else if ($tipo == 13) {
            $item_info["preco"] = 10000;
        } else {

            echo ("#Item inválido.");
            exit();
        }
        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = $connection->run($query);
        $sql = $result->fetch_array();
        $mod = $sql["mod"];

        $preco = $item_info["preco"] * $mod;

        if ($tipo == 13) {
            if (! isset($_GET["quant"])) {

                echo ("#Você informou algum caracter inválido.");
                exit();
            }
            $quant_compra = $protector->get_number_or_exit("quant");
            if (! preg_match("/^[\d]+$/", $quant_compra) or $quant_compra == 0) {

                echo ("#Você informou algum caracter inválido.");
                exit();
            }

            $preco *= $quant_compra;
        }

        if ($usuario["berries"] >= $preco) {
            $query = "UPDATE tb_usuarios SET kai='0' WHERE id='" . $usuario["id"] . "'";
            $connection->run($query) or die("Nao foi possivel descontar o dinheiro");
            if ($tipo == 11) {
                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

                $query = "SELECT * FROM tb_usuario_navio WHERE id='" . $usuario["id"] . "'";
                $result = $connection->run($query);
                $cont = $result->count();
                if ($cont == 0) {
                    $query = "INSERT INTO tb_usuario_navio (id, cod_navio, cod_casco, cod_leme, cod_velas, hp, hp_max, lvl)  
    					VALUES ('" . $usuario["id"] . "', '$item', '0', '0', '0', '100', '100', '1')";
                    $connection->run($query) or die("Nao foi possivel comprar o navio");


                    echo ("@Item Comprado");
                } else {
                    $connection->run("UPDATE tb_usuario_navio SET cod_navio = ? WHERE id = ?", 'ii', [
                        $item,
                        $usuario['id']
                    ]);
                    /*$query = "UPDATE tb_usuario_navio SET cod_navio='$item', cod_casco='0', cod_leme='0', cod_velas='0',
                        hp='100', hp_max='100', lvl='1', xp='0', xp_max='250' WHERE id='" . $usuario["id"] . "'";
                    $connection->run($query) or die("Nao foi possivel substituir o navio");

                    */
                    echo ("@Item Comprado");
                }
            } else if ($tipo == 13) {

                $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND tipo_item='13'";
                $result = $connection->run($query);
                if ($result->count() == 0) {
                    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
                    $result = $connection->run($query);
                    $cont = $result->count();
                    if ($cont < $usuario["capacidade_iventario"]) {
                        $berries = $usuario["berries"] - $preco;
                        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                        $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

                        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)  
	                        VALUES ('" . $usuario["id"] . "', '$item', '$tipo', '$quant_compra')";
                        $connection->run($query) or die("Nao foi possivel cadastrar o item");


                        echo ("@Item comprado!");
                    } else {

                        echo ("#O limite de itens no Iventário foi excedido.");
                    }
                } else {
                    $quant = $result->fetch_array();
                    $quant = $quant["quant"] + $quant_compra;

                    $berries = $usuario["berries"] - $preco;
                    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                    $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

                    $query = "UPDATE tb_usuario_itens SET quant='$quant', novo = 1 WHERE id='" . $usuario["id"] . "' AND tipo_item='13'";
                    $connection->run($query) or die("Nao foi possivel cadastrar o item");
                    echo ("@Item comprado!");
                }
            } else {
                $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
                $result = $connection->run($query);
                $cont = $result->count();
                if ($cont < $usuario["capacidade_iventario"]) {
                    $berries = $usuario["berries"] - $preco;
                    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                    $connection->run($query) or die("Nao foi possivel descontar o dinheiro");

                    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
                        VALUES ('" . $usuario["id"] . "', '$item', '$tipo')";
                    $connection->run($query) or die("Nao foi possivel cadastrar o item");


                    echo ("-Item comprado!");
                } else {

                    echo ("#O limite de itens no Iventário foi excedido.");
                }

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

