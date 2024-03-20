<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if (! isset ($_GET["cod"])) {

    echo ("#Informações insuficientes");
    exit();
}

$cod = $_GET["cod"];

if (! preg_match("/^[\d]+$/", $cod)) {

    echo ("#Informações inválidas");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Personagem não encontrado");
    exit();
}

$query = "UPDATE tb_personagens SET tatic_d='0' WHERE cod='$cod'";
$connection->run($query) or die ("Nao foi possivel atualizar a posição");

echo ":";

