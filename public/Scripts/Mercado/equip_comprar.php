<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (! isset($_GET["item"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$item = $protector->get_number_or_exit("item");
if (! preg_match("/^[\d]/", $item)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
$protector->need_navio();

if ($conect) {

    $query = "SELECT * FROM tb_ilha_itens WHERE ilha='" . $usuario["ilha"] . "' AND cod_item='$item' AND tipo_item='14'";
    $result = $connection->run($query);
    $cont = $result->count();

    if ($cont != 0) {
        $query = "SELECT * FROM tb_equipamentos WHERE item='$item'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();

        $query = "SELECT * FROM tb_ilha_mod WHERE ilha='" . $usuario["ilha"] . "'";
        $result = $connection->run($query);
        $sql = $result->fetch_array();
        $mod = $sql["mod"];

        $preco = preco_compra_equipamento($item_info);

        if ($usuario["berries"] >= $preco) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = $connection->run($query);
            $cont = $result->count();
            if ($cont < $usuario["capacidade_iventario"]) {
                $query = "INSERT INTO tb_item_equipamentos 
					(item, img, cat_dano, b_1, b_2, categoria, nome, descricao, lvl, treino_max, slot, requisito)  
					VALUES 
					('" . $item_info["item"] . "', '" . $item_info["img"] . "', '" . $item_info["cat_dano"] . "', '" . $item_info["b_1"] . "', 
					'" . $item_info["b_2"] . "', '" . $item_info["categoria"] . "', '" . $item_info["nome"] . "', '" . $item_info["descricao"] . "', 
					'" . $item_info["lvl"] . "', '" . $item_info["treino_max"] . "', '" . $item_info["slot"] . "', '" . $item_info["requisito"] . "')";
                $connection->run($query) or die("Nao foi possivel criar o equipamento");

                $cod_item = mysql_insert_id();

                $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)  
					VALUES ('" . $usuario["id"] . "', '$cod_item', '14')";
                $connection->run($query) or die("Nao foi possivel cadastrar o item");

                $berries = $usuario["berries"] - $preco;
                $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
                $connection->run($query) or die("Nao foi possivel descontar o dinheiro");


                echo ("-Item comprado!");
            } else {

                echo ("#O limite de itens no Iventário é de apenas " . $usuario["capacidade_iventario"]);
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

