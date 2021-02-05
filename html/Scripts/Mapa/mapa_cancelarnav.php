<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit;
}
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit;
}
if (!$innavio) {
    mysql_close();
    echo("#Você precisa de um navio.");
    exit;
}

$connection->run("UPDATE tb_usuarios SET coup_de_burst_usado = 0 WHERE id = ?", "i", $userDetails->tripulacao["id"]);

$query = "DELETE FROM tb_rotas WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("nao foi possivel cancelar a rota");
mysql_close();
echo("Rota cancelada");