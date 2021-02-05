<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Voce precisa estar logado.");
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

$perso = mysql_real_escape_string($_GET["pers"]);
$slot = mysql_real_escape_string($_GET["slot"]);
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

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) >= $usuario["capacidade_iventario"]) {
    mysql_close();
    echo("#A capacidade do inventário foi excedida");
    exit();
}

$query = "SELECT * FROM tb_personagem_equipamentos WHERE cod='$perso'";
$result = mysql_query($query);
$equipados = mysql_fetch_array($result);

$query = "SELECT * FROM tb_item_equipamentos WHERE cod_equipamento='" . $equipados[$slot] . "'";
$result = mysql_query($query);
$equipamento = mysql_fetch_array($result);

if ($equipamento["slot"] == 10) {
    $query = "UPDATE tb_personagem_equipamentos SET `7`=NULL, `8`=NULL WHERE cod='" . $personagem["cod"] . "'";
    mysql_query($query) or die("não foi possivel remover o item 1");
} else {
    $query = "UPDATE tb_personagem_equipamentos SET `$slot`=NULL WHERE cod='" . $personagem["cod"] . "'";
    mysql_query($query) or die("não foi possivel remover o item 2");
}

$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
	VALUES ('" . $usuario["id"] . "', '" . $equipados[$slot] . "', '14')";
mysql_query($query) or die("não foi possivel remover o item 3");

mysql_close();
echo ":equipamentos&outro=" . $personagem["cod"];
?>