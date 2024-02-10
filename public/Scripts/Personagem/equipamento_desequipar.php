<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Voce precisa estar logado.");
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

$perso = $protector->get_number_or_exit("pers");
$slot = $protector->get_number_or_exit("slot");
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

$query = "SELECT * FROM tb_usuario_itens WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() >= $usuario["capacidade_iventario"]) {

    echo ("#A capacidade do inventário foi excedida");
    exit();
}

$query = "SELECT * FROM tb_personagem_equipamentos WHERE cod='$perso'";
$result = $connection->run($query);
$equipados = $result->fetch_array();

$query = "SELECT * FROM tb_item_equipamentos WHERE cod_equipamento='" . $equipados[$slot] . "'";
$result = $connection->run($query);
$equipamento = $result->fetch_array();

if ($equipamento["slot"] == 10) {
    $query = "UPDATE tb_personagem_equipamentos SET `7`=NULL, `8`=NULL WHERE cod='" . $personagem["cod"] . "'";
    $connection->run($query) or die("não foi possivel remover o item 1");
} else {
    $query = "UPDATE tb_personagem_equipamentos SET `$slot`=NULL WHERE cod='" . $personagem["cod"] . "'";
    $connection->run($query) or die("não foi possivel remover o item 2");
}

$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item)
	VALUES ('" . $usuario["id"] . "', '" . $equipados[$slot] . "', '14')";
$connection->run($query) or die("não foi possivel remover o item 3");


echo ":equipamentos&outro=" . $personagem["cod"];
?>

