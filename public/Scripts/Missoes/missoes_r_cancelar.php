<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_missao.php";
include "../../Includes/verifica_combate.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}
if ($incombate) {

    echo ("#Você está em combate");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha!");
}
if ($inrecrute) {

    echo ("#Você está ocupado recrutando neste momento.");
    exit();
}

$query = "DELETE FROM tb_missoes_r 
	WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("Nao foi possivel cancelar missao");


echo ("-Missão abortada");
exit();
?>

