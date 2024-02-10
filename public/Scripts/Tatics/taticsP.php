<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if (! isset($_GET["cod"]) or ! isset($_GET["pos"])) {

    echo ("#Informações insuficientes");
    exit();
}

$cod = $_GET["cod"];
$pos = explode("_", $_GET["pos"]);

if (! preg_match("/^[\d]+$/", $cod) or ! preg_match("/^[\d]+$/", $pos[0]) or ! preg_match("/^[\d]+$/", $pos[1])) {

    echo ("#Informações inválidas");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Personagem não encontrado");
    exit();
}

if ($pos[0] < 0 or $pos[0] > 4 or $pos[1] > 19 or $pos[1] < 0) {

    echo ("#Informações inválidas");
    exit();
}
$coord = $pos[0] . ";" . $pos[1];

$query = "SELECT * FROM tb_personagens WHERE tatic_p='$coord' AND ativo=1 AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() != 0) {

    echo ("#Este quadrado já está ocupado");
    exit();
}

$query = "UPDATE tb_personagens SET tatic_p='$coord' WHERE cod='$cod'";
$connection->run($query) or die("Nao foi possivel atualizar a posição");

echo "Posição fixa definida!";

