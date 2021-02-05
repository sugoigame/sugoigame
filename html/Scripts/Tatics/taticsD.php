<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if (!isset($_GET["cod"]) OR !isset($_GET["pos"])) {
    mysql_close();
    echo("#Informações insuficientes");
    exit();
}

$cod = $_GET["cod"];
$pos = explode("_", $_GET["pos"]);

if (!preg_match("/^[\d]+$/", $cod) OR !preg_match("/^[\d]+$/", $pos[0]) OR !preg_match("/^[\d]+$/", $pos[1])) {
    mysql_close();
    echo("#Informações inválidas");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Personagem não encontrado");
    exit();
}

if ($pos[0] < 0 OR $pos[0] > 4 OR $pos[1] > 19 OR $pos[1] < 0) {
    mysql_close();
    echo("#Informações inválidas");
    exit();
}
$coord = $pos[0] . ";" . $pos[1];

$query = "SELECT * FROM tb_personagens WHERE tatic_d='$coord' AND ativo=1 AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) != 0) {
    mysql_close();
    echo("#Este quadrado já está ocupado");
    exit();
}

$query = "UPDATE tb_personagens SET tatic_d='$coord' WHERE cod='$cod'";
mysql_query($query) or die("Nao foi possivel atualizar a posição");

echo "Posição fixa definida!";
mysql_close();
