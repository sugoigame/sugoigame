<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Voce precisa estar logado.");
    exit();
}

if (!isset($_GET["item"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["pers"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["slot"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$item = mysql_real_escape_string($_GET["item"]);
$perso = mysql_real_escape_string($_GET["pers"]);
$slot = mysql_real_escape_string($_GET["slot"]);
if (!preg_match("/^[\d]+$/", $item)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $perso)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $slot)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$perso'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = mysql_fetch_array($result);

$query = "SELECT * FROM tb_usuario_itens WHERE cod_item='$item' AND tipo_item='14'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Você não possui esse item");
    exit();
}

$query = "SELECT * FROM tb_item_equipamentos WHERE cod_equipamento='$item'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Você não possui esse item");
    exit();
}
$equipamento = mysql_fetch_array($result);

$query = "SELECT * FROM tb_personagem_equipamentos WHERE cod='$perso'";
$result = mysql_query($query);
$equipados = mysql_fetch_array($result);

if ($personagem["lvl"] < $equipamento["lvl"]) {
    mysql_close();
    echo("#Esse personagem não tem o nível adequado para usar esse equipamento");
    exit();
}
if ($equipamento["requisito"] != 0) {
    if ($equipamento["requisito"] != $personagem["classe"]) {
        mysql_close();
        echo("#Esse personagem não tem a classe adequada para usar esse equipamento");
        exit();
    }
}

if ($equipamento["slot"] < 9) {
    if ($equipados[$equipamento["slot"]] != 0) {
        $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) >= ($usuario["capacidade_iventario"])) {
            mysql_close();
            echo("#A capacidade do invent�rio foi excedida");
            exit();
        }
    }
    if ($equipados[$equipamento["slot"]] != 0) {
        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
			VALUES ('" . $usuario["id"] . "', '" . $equipados[$equipamento["slot"]] . "', '14')";
        mysql_query($query) or die("nao foi possivel retirar o item equipado");
    }
    $query = "UPDATE tb_personagem_equipamentos SET `" . $equipamento["slot"] . "`='$item' WHERE cod='$perso'";
    mysql_query($query) or die("nao foi possivel equipar o item1");

} else {
    if ($equipamento["slot"] == 9) {
        if ($equipados[$slot] != 0) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) >= ($usuario["capacidade_iventario"])) {
                mysql_close();
                echo("#A capacidade do invent�rio foi excedida");
                exit();
            }
        }
        if ($equipados[$slot] != 0) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[$slot] . "', '14')";
            mysql_query($query) or die("nao foi possivel retirar o item equipado");
        }
        $query = "UPDATE tb_personagem_equipamentos SET `$slot`='$item' WHERE cod='$perso'";
        mysql_query($query) or die("nao foi possivel equipar o item2");
    } else if ($equipamento["slot"] == 10) {
        if ($equipados[7] != 0 && $equipados[8] != 0) {
            $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
            $result = mysql_query($query);
            if (mysql_num_rows($result) >= ($usuario["capacidade_iventario"] - 1)) {
                mysql_close();
                echo("#A capacidade do invent�rio foi excedida");
                exit();
            }
        }
        if ($equipados[7] != 0) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[7] . "', '14')";
            mysql_query($query) or die("nao foi possivel retirar o item equipado");
        }
        if ($equipados[8] != 0 AND $equipados[8] != $equipados[7]) {
            $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
				VALUES ('" . $usuario["id"] . "', '" . $equipados[8] . "', '14')";
            mysql_query($query) or die("nao foi possivel retirar o item equipado");
        }
        $query = "UPDATE tb_personagem_equipamentos SET `7`='$item',`8`='$item' WHERE cod='$perso'";
        mysql_query($query) or die("nao foi possivel equipar o item3");
    }
}
if ($slot == 7 AND $equipamento["slot"] != 10) {
    if ($equipados[7] == $equipados[8]) {
        $query = "UPDATE tb_personagem_equipamentos SET `8`= NULL WHERE cod='$perso'";
        mysql_query($query) or die("nao foi possivel equipar o item4");
    }
}
if ($slot == 8 AND $equipamento["slot"] != 10) {
    if ($equipados[7] == $equipados[8]) {
        $query = "UPDATE tb_personagem_equipamentos SET `7`= NULL WHERE cod='$perso'";
        mysql_query($query) or die("nao foi possivel equipar o item5");
    }
}

$query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$item' AND tipo_item='14'";
mysql_query($query) or die("nao foi possivel equipar o item");

mysql_close();
echo ":equipamentos&outro=" . $personagem["cod"];
?>