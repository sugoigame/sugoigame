<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = mysql_real_escape_string($_GET["cod"]);
if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}

$query = "UPDATE tb_personagens SET respawn='0', respawn_tipo='0' WHERE cod='$cod'";
mysql_query($query) or die("nao foi possivel iniciar a recuperacao");

mysql_close();
echo "Descanso cancelado";
