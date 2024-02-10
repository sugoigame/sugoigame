<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($usuario["x"] == $usuario["res_x"] and $usuario["y"] == $usuario["res_y"]) {

    echo ("#Você precisa estar em uma ilha diferente.");
    exit();
}
if ($usuario["ilha"] == 47) {

    echo ("#Você não pode salvar seu retorno nessa ilha.");
    exit();
}

$query = "UPDATE tb_usuarios SET res_x='" . $usuario["x"] . "', res_y='" . $usuario["y"] . "'
	WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel salvar retorno");


echo ("Retorno Salvo!");
?>

