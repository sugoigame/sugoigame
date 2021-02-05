<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit;
}
if (!$innavio) {
    mysql_close();
    echo("#Você precisa de um navio");
    exit;
}
if ($navio["cod_casco"] != 2) {
    mysql_close();
    echo("#Você não possui um casco apropriado.");
    exit;
}
$op = mysql_real_escape_string($_GET["op"]);

if (!preg_match("/^[\d]+$/", $op)) {
    mysql_close();
    echo("#Opçao invalida.");
    exit;
}
if ($op != 1 AND $op != 0) {
    mysql_close();
    echo("#Opçao invalida2.");
    exit;
}

$query = "UPDATE tb_usuarios SET kai='$op' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel alterar Kairouseki");

if ($op == 1) {
    echo "Kairouseki ativado!";
} else if ($op == 0) {
    echo "Kairouseki desativado!";
}
mysql_close();
?>