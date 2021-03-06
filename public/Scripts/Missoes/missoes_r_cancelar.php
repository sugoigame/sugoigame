<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if ($incombate) {
    mysql_close();
    echo("#Você está em combate");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha!");
}
if ($inrecrute) {
    mysql_close();
    echo("#Você está ocupado recrutando neste momento.");
    exit();
}

$query = "DELETE FROM tb_missoes_r 
	WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel cancelar missao");

mysql_close();
echo("-Missão abortada");
exit();
?>