<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit();
}
if (!$inally) {
    mysql_close();
    echo("#Você não faz parte de uma aliança");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha");
    exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$permicao = mysql_fetch_array($result);

if (substr($usuario["alianca"][$permicao["autoridade"]], 8, 1) == 0) {
    mysql_close();
    echo("#Você não tem permissão para isso.");
    exit();
}

if (!isset($_GET["item"]) OR !isset($_GET["tipo"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = mysql_real_escape_string($_GET["item"]);
$tipo = mysql_real_escape_string($_GET["tipo"]);
$quantidade = mysql_real_escape_string($_GET["quant"]);

if (!preg_match("/^[\d]+$/", $cod) OR !preg_match("/^[\d]+$/", $tipo)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $quantidade)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

if ($personagem[0]["lvl"] < 15) {
    mysql_close();
    echo("#É necessário ter o capitão no nível 15 para utilizar o banco.");
    exit();
}

if ($usuario["berries"] < 10000) {
    mysql_close();
    echo("#Você não tem dinheiro para pagar a taxa.");
    exit();
}

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Você não possui esse item.");
    exit();
}
$quant = mysql_fetch_array($result);

if ($quantidade > 0 AND $quantidade <= $quant["quant"])
    $quant["quant"] = $quantidade;


if ($tipo == 2
    OR $tipo == 16
    OR $tipo == 17
    OR $tipo == 14
    OR $tipo == 3
    OR $tipo == 8
    OR $tipo == 9
    OR $tipo == 10
) {
    mysql_close();
    echo("#Esse item não pode ser depositado.");
    exit();
}

if ($tipo == 1 OR $tipo == 7 OR $tipo == 13 OR $tipo == 15) {
    $query = "SELECT * FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' AND cod_item='$cod' AND tipo_item='$tipo'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) == 0) {
        $query = "SELECT * FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) >= 100) {
            mysql_close();
            echo("#O banco está lotado.");
            exit();
        }
        $query = "INSERT INTO tb_alianca_banco (cod_alianca, cod_item, tipo_item, quant)
			VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '$cod', '$tipo', '" . $quant["quant"] . "')";
        mysql_query($query) or die("Não foi possivel remover o item");
    } else {
        $quant_i = mysql_fetch_array($result);
        $quant_i = $quant_i["quant"] + $quant["quant"];
        $query = "UPDATE tb_alianca_banco SET quant='$quant_i' 
			WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
        mysql_query($query) or die("Não foi possivel remover o item");
    }
} else {
    $query = "SELECT * FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
    $result = mysql_query($query);
    if (mysql_num_rows($result) >= 100) {
        mysql_close();
        echo("#O banco está lotado.");
        exit();
    }
    $query = "INSERT INTO tb_alianca_banco (cod_alianca, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '$cod', '$tipo', '" . $quant["quant"] . "')";
    mysql_query($query) or die("Não foi possivel remover o item");
}
switch ($tipo) {
    case 0:
        $query = "SELECT * FROM tb_item_acessorio WHERE cod_acessorio='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 1:
        $query = "SELECT * FROM tb_item_comida WHERE cod_comida='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 4:
        $query = "SELECT * FROM tb_item_navio_leme WHERE cod_leme='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 5:
        $query = "SELECT * FROM tb_item_navio_velas WHERE cod_velas='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 7:
        $query = "SELECT * FROM tb_item_remedio WHERE cod_remedio='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 12:
        $query = "SELECT * FROM tb_item_navio_canhao WHERE cod_canhao='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    case 13:
        $nome_item = "Bala de canhão";
        break;
    case 15:
        $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$cod'";
        $result = mysql_query($query);
        $item_info = mysql_fetch_array($result);
        $nome_item = $item_info["nome"];
        break;
    default:
        mysql_close();
        echo("#Esse item não pode ser depositado.");
        exit();
        break;
}
$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo'";
$result = mysql_query($query);
$total = mysql_fetch_array($result);
if ($total["quant"] == $quant["quant"])
    $query = "DELETE FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
else {
    $nquant = $total["quant"] - $quant["quant"];
    $query = "UPDATE tb_usuario_itens SET quant='$nquant' WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
}
mysql_query($query) or die("Não foi possivel remover o item");

$query = "INSERT INTO tb_alianca_banco_log (cod_alianca, usuario, item, tipo)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '" . $personagem[0]["nome"] . "', '$nome_item', '1')";
mysql_query($query);

$berries = $usuario["berries"] - 10000;
$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Não foi possivel remover o item");

mysql_close();
echo("@Item depositado");

?>