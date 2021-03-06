<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    header("location../erro.php?erro=Você precisa estar logado para executar essa ação.");
    exit;
}
if ($inmissao) {
    mysql_close();
    header("location:../../?msg=Você está ocupado em uma missão neste meomento.");
    exit;
}
if (!isset($_GET["pers"])) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["item"])) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (!isset($_POST["quant"])) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}

$pers = mysql_real_escape_string($_GET["pers"]);
$item = mysql_real_escape_string($_GET["item"]);
$quant_faz = mysql_real_escape_string($_POST["quant"]);

if (!preg_match("/^[\d]+$/", $pers)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.1");
    exit();
}
if (!preg_match("/^[\d]+$/", $item)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.2");
    exit();
}
if (!preg_match("/^[\d]+$/", $quant_faz)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.3");
    exit();
}

if ($quant_faz < 0) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.4");
    exit();
}
$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) >= $usuario["capacidade_iventario"]) {
    mysql_close();
    header("location:../../?msg=Seu iventário está lotado.");
    exit;
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$pers'";
$result = mysql_query($query);
$personagem = mysql_fetch_array($result);

if ($personagem["profissao"] != 3) {
    mysql_close();
    header("location:../../?msg=Este personagem não é um médico.");
    exit;
}

$query = "SELECT * FROM tb_item_remedio WHERE cod_remedio='$item'";
$result = mysql_query($query);
$item = mysql_fetch_array($result);
$item["preco"] = ($item["hp_recuperado"] + $item["mp_recuperado"]) * 60;
$item["preco"] *= (((1 - $personagem["profissao_lvl"] * 0.05)) * $quant_faz);

if ($usuario["berries"] < $item["preco"]) {
    mysql_close();
    header("location:../../?msg=Você não tem dinheiro para essa quantidade de itens.");
    exit;
}

if ($personagem["profissao_lvl"] < $item["requisito_lvl"]) {
    mysql_close();
    header("location:../../?msg=Você não cumpre os requisitos para fazer este item.");
    exit;
}
$berries = $usuario["berries"] - $item["preco"];
$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel pagar o item");

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_remedio"] . "' AND tipo_item='7'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont != 0) {
    $quant = mysql_fetch_array($result);
    $quant = $quant["quant"] + $quant_faz;
    $query = "UPDATE tb_usuario_itens SET quant='$quant', novo = 1 WHERE id='" . $usuario["id"] . "' AND cod_item='" . $item["cod_remedio"] . "' AND tipo_item='7' LIMIT 1";
    mysql_query($query) or die("Nao foi posssivel adicionar o item");
} else {
    $query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant)
		VALUES ('" . $usuario["id"] . "', '" . $item["cod_remedio"] . "', '7', '$quant_faz')";
    mysql_query($query) or die("Nao foi possivel criar o item");
}
if ($personagem["profissao_xp"] < $personagem["profissao_xp_max"] AND $personagem["profissao_lvl"] == $item["requisito_lvl"]) {
    $xp = $personagem["profissao_xp"] + $quant_faz;
    if ($xp > $personagem["profissao_xp_max"]) $xp = $personagem["profissao_xp_max"];
    $query = "UPDATE tb_personagens SET profissao_xp='$xp' WHERE id='" . $usuario["id"] . "' AND cod='" . $personagem["cod"] . "'";
    mysql_query($query) or die("Nao foi possivel evoluir profisssao");
}

mysql_close();
header("location:../../?ses=profissoes");
?>