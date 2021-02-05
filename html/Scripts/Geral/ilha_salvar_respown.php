<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}

if ($usuario["x"] == $usuario["res_x"] AND $usuario["y"] == $usuario["res_y"]) {
    mysql_close();
    echo("#Você precisa estar em uma ilha diferente.");
    exit();
}
if ($usuario["ilha"] == 47) {
    mysql_close();
    echo("#Você não pode salvar seu retorno nessa ilha.");
    exit();
}
	
$query = "UPDATE tb_usuarios SET res_x='" . $usuario["x"] . "', res_y='" . $usuario["y"] . "'
	WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel salvar retorno");

mysql_close();
echo("Retorno Salvo!");
?>