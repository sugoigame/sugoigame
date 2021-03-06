<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}

$item = $protector->get_number_or_exit('item');
$tipo = $protector->get_number_or_exit('tipo');

if (!$inilha) {
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='$tipo'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    if ($tipo == 13) $cont++;

    if ($cont != 0) {
        if ($tipo == 11) {
            $query = "SELECT * FROM tb_navio WHERE cod_navio='" . $item . "'";
            $result = mysql_query($query);
            $item_info = mysql_fetch_array($result);
        } else if ($tipo == 3) {
            $query = "SELECT * FROM tb_item_navio_casco WHERE cod_casco='" . $item . "'";
            $result = mysql_query($query);
            $item_info = mysql_fetch_array($result);
        } else if ($tipo == 4) {
            $query = "SELECT * FROM tb_item_navio_leme WHERE cod_leme='" . $item . "'";
            $result = mysql_query($query);
            $item_info = mysql_fetch_array($result);
        } else if ($tipo == 5) {
            $query = "SELECT * FROM tb_item_navio_velas WHERE cod_velas='" . $item . "'";
            $result = mysql_query($query);
            $item_info = mysql_fetch_array($result);
        } else if ($tipo == 12) {
            $query = "SELECT * FROM tb_item_navio_canhao WHERE cod_canhao='" . $item . "'";
            $result = mysql_query($query);
            $item_info = mysql_fetch_array($result);
        } else if ($tipo == 13) {
            $item_info["preco"] = 10000;
        } else {
            mysql_close();
            echo("#Item inválido.");
            exit();
        }
        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = mysql_query($query);
        $sql = mysql_fetch_array($result);
        $mod = $sql["mod"];

        $preco = $item_info["preco"] * $mod;

        if ($tipo == 13) {
            if (!isset($_GET["quant"])) {
                mysql_close();
                echo("#Você informou algum caracter inválido.");
                exit();
            }
            $quant_compra = mysql_real_escape_string($_GET["quant"]);
            if (!preg_match("/^[\d]+$/", $quant_compra) OR $quant_compra == 0) {
                mysql_close();
                echo("#Você informou algum caracter inválido.");
                exit();
            }

            $preco *= $quant_compra;
        }

        if ($usuario["berries"] >= $preco) {
            $query = "UPDATE tb_usuarios SET kai='0' WHERE id='" . $usuario["id"] . "'";
            mysql_query($query) or die("Nao foi possivel descontar o dinheiro");
            if ($tipo == 11) {
                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                $query = "SELECT * FROM tb_usuario_navio WHERE id='" . $usuario["id"] . "'";
                $result = mysql_query($query);
                $cont = mysql_num_rows($result);
                if ($cont == 0) {
                    $query = "INSERT INTO tb_usuario_navio (id, cod_navio, cod_casco, cod_leme, cod_velas, hp, hp_max, lvl)  
    					VALUES ('" . $usuario["id"] . "', '$item', '0', '0', '0', '100', '100', '1')";
                    mysql_query($query) or die("Nao foi possivel comprar o navio");

                    mysql_close();
                    echo("@Item Comprado");
                } else {
                    $connection->run("UPDATE tb_usuario_navio SET cod_navio = ? WHERE id = ?", 'ii', [
                        $item,
                        $usuario['id']
                    ]);
                    /*$query = "UPDATE tb_usuario_navio SET cod_navio='$item', cod_casco='0', cod_leme='0', cod_velas='0',
    					hp='100', hp_max='100', lvl='1', xp='0', xp_max='250' WHERE id='" . $usuario["id"] . "'";
                    mysql_query($query) or die("Nao foi possivel substituir o navio");

                    mysql_close();*/
                    echo("@Item Comprado");
                }
            } else if ($tipo == 13) {

                $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND tipo_item='13'";
                $result = mysql_query($query);
                if (mysql_num_rows($result) == 0) {
                    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
                    $result = mysql_query($query);
                    $cont = mysql_num_rows($result);
                    if ($cont < $usuario["capacidade_iventario"]) {
                        $berries = $usuario["berries"] - $preco;
                        $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                        mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)  
	                        VALUES ('" . $usuario["id"] . "', '$item', '$tipo', '$quant_compra')";
                        mysql_query($query) or die("Nao foi possivel cadastrar o item");

                        mysql_close();
                        echo("@Item comprado!");
                    } else {
                        mysql_close();
                        echo("#O limite de itens no Iventário foi excedido.");
                    }
                } else {
                    $quant = mysql_fetch_array($result);
                    $quant = $quant["quant"] + $quant_compra;

                    $berries = $usuario["berries"] - $preco;
                    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                    mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                    $query = "UPDATE tb_usuario_itens SET quant='$quant', novo = 1 WHERE id='" . $usuario["id"] . "' AND tipo_item='13'";
                    mysql_query($query) or die("Nao foi possivel cadastrar o item");
                    echo("@Item comprado!");
                }
            } else {
                $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
                $result = mysql_query($query);
                $cont = mysql_num_rows($result);
                if ($cont < $usuario["capacidade_iventario"]) {
                    $berries = $usuario["berries"] - $preco;
                    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                    mysql_query($query) or die("Nao foi possivel descontar o dinheiro");

                    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
                        VALUES ('" . $usuario["id"] . "', '$item', '$tipo')";
                    mysql_query($query) or die("Nao foi possivel cadastrar o item");

                    mysql_close();
                    echo("-Item comprado!");
                } else {
                    mysql_close();
                    echo("#O limite de itens no Iventário foi excedido.");
                }

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