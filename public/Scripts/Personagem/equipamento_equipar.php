<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Voce precisa estar logado.");
    exit();
}

if (! isset($_GET["item"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! isset($_GET["pers"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! isset($_GET["slot"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$item = $protector->get_number_or_exit("item");
$perso = $protector->get_number_or_exit("pers");
$slot = $protector->get_number_or_exit("slot");
if (! preg_match("/^[\d]+$/", $item)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $perso)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $slot)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$perso'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Personagem não encontrado.");
    exit();
}
$personagem = $result->fetch_array();

$query = "SELECT * FROM tb_usuario_itens WHERE cod_item='$item' AND tipo_item='14'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Você não possui esse item");
    exit();
}

$query = "SELECT * FROM tb_item_equipamentos WHERE cod_equipamento='$item'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Você não possui esse item");
    exit();
}
$equipamento = $result->fetch_array();

$query = "SELECT * FROM tb_personagem_equipamentos WHERE cod='$perso'";
$result = $connection->run($query);
$equipados = $result->fetch_array();

if ($personagem["lvl"] < $equipamento["lvl"]) {

    echo ("#Esse personagem não tem o nível adequado para usar esse equipamento");
    exit();
}
if ($equipamento["requisito"] != 0) {
    if ($equipamento["requisito"] != $personagem["classe"]) {

        echo ("#Esse personagem não tem a classe adequada para usar esse equipamento");
        exit();
    }
}

if ($equipamento["slot"] < 9) {
    if ($equipados[$equipamento["slot"]] != 0) {
        $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
        $result = $connection->run($query);
        if ($result->count() >= ($usuario["capacidade_iventario"])) {

            echo ("#A capacidade do invent�rio foi excedida");
            exit();
        }
    }
    if ($equipados[$equipamento["slot"]] != 0) {
        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
			VALUES ('" . $usuario["id"] . "', '" . $equipados[$equipamento["slot"]] . "', '14')";
        $connection->run($query) or die("nao foi possivel retirar o item equipado");
    }
    $query = "UPDATE tb_personagem_equipamentos SET `" . $equipamento["slot"] . "`='$item' WHERE cod='$perso'";
    $connection->run($query) or die("nao foi possivel equipar o item1");

} else {
    if ($equipamento["slot"] == 9) {
        if ($equipados[$slot] != 0) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = $connection->run($query);
            if ($result->count() >= ($usuario["capacidade_iventario"])) {

                echo ("#A capacidade do invent�rio foi excedida");
                exit();
            }
        }
        if ($equipados[$slot] != 0) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[$slot] . "', '14')";
            $connection->run($query) or die("nao foi possivel retirar o item equipado");
        }
        $query = "UPDATE tb_personagem_equipamentos SET `$slot`='$item' WHERE cod='$perso'";
        $connection->run($query) or die("nao foi possivel equipar o item2");
    } else if ($equipamento["slot"] == 10) {
        if ($equipados[7] != 0 && $equipados[8] != 0) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = $connection->run($query);
            if ($result->count() >= ($usuario["capacidade_iventario"] - 1)) {

                echo ("#A capacidade do invent�rio foi excedida");
                exit();
            }
        }
        if ($equipados[7] != 0) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[7] . "', '14')";
            $connection->run($query) or die("nao foi possivel retirar o item equipado");
        }
        if ($equipados[8] != 0 and $equipados[8] != $equipados[7]) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[8] . "', '14')";
            $connection->run($query) or die("nao foi possivel retirar o item equipado");
        }
        $query = "UPDATE tb_personagem_equipamentos SET `7`='$item',`8`='$item' WHERE cod='$perso'";
        $connection->run($query) or die("nao foi possivel equipar o item3");
    }
}
if ($slot == 7 and $equipamento["slot"] != 10) {
    if ($equipados[7] == $equipados[8]) {
        $query = "UPDATE tb_personagem_equipamentos SET `8`= NULL WHERE cod='$perso'";
        $connection->run($query) or die("nao foi possivel equipar o item4");
    }
}
if ($slot == 8 and $equipamento["slot"] != 10) {
    if ($equipados[7] == $equipados[8]) {
        $query = "UPDATE tb_personagem_equipamentos SET `7`= NULL WHERE cod='$perso'";
        $connection->run($query) or die("nao foi possivel equipar o item5");
    }
}

$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='14'";
$connection->run($query) or die("nao foi possivel equipar o item");


echo ":equipamentos&outro=" . $personagem["cod"];
?>

