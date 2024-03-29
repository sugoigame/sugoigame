<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if (! $inally) {

    echo ("#Você não faz parte de uma aliança");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha");
    exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 9, 1) == 0) {

    echo ("#Você não tem permissão para isso.");
    exit();
}

if (! isset($_GET["item"]) or ! isset($_GET["tipo"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$cod = $protector->get_number_or_exit("item");
$tipo = $protector->get_number_or_exit("tipo");
$quantidade = $protector->get_number_or_exit("quant");

if (! preg_match("/^[\d]+$/", $cod) or ! preg_match("/^[\d]+$/", $tipo)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $quantidade)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

if ($personagem[0]["lvl"] < 15) {

    echo ("#É necessário ter o capitão no nível 15 para utilizar o banco.");
    exit();
}

if ($usuario["berries"] < 10000) {

    echo ("#Você não tem dinheiro para pagar a taxa.");
    exit();
}

$query = "SELECT * FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'
	AND cod_item='$cod' AND tipo_item='$tipo'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Você não possui esse item.");
    exit();
}
$quant = $result->fetch_array();

if ($quantidade > 0 and $quantidade <= $quant["quant"])
    $quant["quant"] = $quantidade;

if ($tipo == 1 or $tipo == 7 or $tipo == 13 or $tipo == 15) {
    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo'";
    $result = $connection->run($query);
    if ($result->count() == 0) {
        $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
        $result = $connection->run($query);
        if ($result->count() >= $usuario["capacidade_iventario"]) {

            echo ("#Seu inventário está lotado.");
            exit();
        }
        $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
			VALUES ('" . $usuario["id"] . "', '$cod', '$tipo', '" . $quant["quant"] . "')";
        $connection->run($query) or die("Não foi possivel remover o item");
    } else {
        $quant_i = $result->fetch_array();
        $quant_i = $quant_i["quant"] + $quant["quant"];
        $query = "UPDATE tb_usuario_itens SET quant='$quant_i' WHERE id='" . $usuario["id"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
        $connection->run($query) or die("Não foi possivel remover o item");
    }
} else {
    $query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
    $result = $connection->run($query);
    if ($result->count() >= $usuario["capacidade_iventario"]) {

        echo ("#Seu inventário está lotado.");
        exit();
    }
    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '$cod', '$tipo', '" . $quant["quant"] . "')";
    $connection->run($query) or die("Não foi possivel remover o item");
}

switch ($tipo) {
    case 0:
        $query = "SELECT * FROM tb_item_acessorio WHERE cod_acessorio='$cod'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $nome_item = $item_info["nome"];
        break;
    case 1:
        $item_info = MapLoader::find("comidas", ["cod_comida" => $cod]);
        $nome_item = $item_info["nome"];
        break;
    case 4:
        $query = "SELECT * FROM tb_item_navio_leme WHERE cod_leme='$cod'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $nome_item = $item_info["nome"];
        break;
    case 5:
        $query = "SELECT * FROM tb_item_navio_velas WHERE cod_velas='$cod'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $nome_item = $item_info["nome"];
        break;
    case 7:
        $item_info = MapLoader::find("remedios", ["cod_remedio" => $cod]);
        $nome_item = $item_info["nome"];
        break;
    case 8:
        $nome_item = "Akuma no Mi";
        break;
    case 9:
        $nome_item = "Akuma no Mi";
        break;
    case 10:
        $nome_item = "Akuma no Mi";
        break;
    case 12:
        $query = "SELECT * FROM tb_item_navio_canhao WHERE cod_canhao='$cod'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $nome_item = $item_info["nome"];
        break;
    case 13:
        $nome_item = "Bala de canhão";
        break;
    case 15:
        $query = "SELECT * FROM tb_item_reagents WHERE cod_reagent='$cod'";
        $result = $connection->run($query);
        $item_info = $result->fetch_array();
        $nome_item = $item_info["nome"];
        break;
}
$query = "SELECT * FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' AND cod_item='$cod' AND tipo_item='$tipo'";
$result = $connection->run($query);
$total = $result->fetch_array();
if ($total["quant"] == $quant["quant"])
    $query = "DELETE FROM tb_alianca_banco WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
else {
    $nquant = $total["quant"] - $quant["quant"];
    $query = "UPDATE tb_alianca_banco SET quant='$nquant'
		WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "' AND cod_item='$cod' AND tipo_item='$tipo' LIMIT 1";
}
$connection->run($query) or die("Não foi possivel remover o item");

$query = "INSERT INTO tb_alianca_banco_log (cod_alianca, usuario, item, tipo)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '" . $personagem[0]["nome"] . "', '$nome_item', '2')";
$connection->run($query);


$berries = $usuario["berries"] - 10000;
$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Não foi possivel remover o item");


echo ("@Item retirado");

?>

