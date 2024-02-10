<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    header("location:../../?msg=Você precisa estar logado para executar essa ação.");
    exit;
}
if ($inmissao) {

    header("location../../?msg=Você está ocupado em uma missão neste meomento.");
    exit;
}
if (! isset($_GET["pers"]) or
    ! isset($_GET["item"]) or
    ! isset($_POST["quant"])
) {

    header("location../../?msg=Você informou algum caracter inválido.");
    exit();
}

if (! preg_match("/^[\d]+$/", $_GET["pers"]) or
    ! preg_match("/^[\d]+$/", $_GET["item"]) or
    ! preg_match("/^[\d]+$/", $_POST["quant"])
) {

    header("location../../?msg=Você informou algum caracter inválido.");
    exit();
}

$pers = $protector->get_number_or_exit("pers");
$item = $protector->get_number_or_exit("item");
$quant_faz = $protector->post_number_or_exit("quant");

if ($quant_faz < 0) {

    header("location../../?msg=Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() >= $usuario["capacidade_iventario"]) {

    header("location:../../?msg=Seu iventário está lotado.");
    exit;
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$pers'";
$result = $connection->run($query);
$personagem = $result->fetch_array();

if ($personagem["profissao"] != 7) {

    header("location../../?msg=Este personagem não é um cozinheiro.");
    exit;
}

$query = "SELECT * FROM tb_item_comida WHERE cod_comida='$item'";
$result = $connection->run($query);
$item = $result->fetch_array();
$item["preco"] = ($item["hp_recuperado"] + $item["mp_recuperado"]) * 50;
$item["preco"] *= (((1 - $personagem["profissao_lvl"] * 0.05)) * $quant_faz);
if ($usuario["berries"] < $item["preco"]) {

    header("location../../?msg=Você não tem dinheiro para fazer essa quantidade de comida.");
    exit;
}

if ($personagem["profissao_lvl"] < $item["requisito_lvl"] and $item["requisito_lvl"] != 0) {

    header("location../../?msg=Você não cumpre os requisitos para fazer este item.");
    exit;
}
$berries = $usuario["berries"] - $item["preco"];
$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel pagar o item");

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_comida"] . "' AND tipo_item='1'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont != 0) {
    $quant = $result->fetch_array();
    $quant = $quant["quant"] + $quant_faz;
    $query = "UPDATE tb_usuario_itens SET quant='$quant', novo = 1 WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_comida"] . "' AND tipo_item='1' LIMIT 1";
    $connection->run($query) or die("Nao foi posssivel adicionar o item");
} else {
    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '" . $item["cod_comida"] . "', '1', '$quant_faz')";
    $connection->run($query) or die("Nao foi possivel criar o item");
}
if ($personagem["profissao_xp"] < $personagem["profissao_xp_max"] and $personagem["profissao_lvl"] == $item["requisito_lvl"]) {
    $xp = $personagem["profissao_xp"] + $quant_faz;
    if ($xp > $personagem["profissao_xp_max"])
        $xp = $personagem["profissao_xp_max"];
    $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem["cod"] . "'";
    $connection->run($query) or die("Nao foi possivel evoluir profisssao");
}

header("location:../../?ses=profissoes");
?>

