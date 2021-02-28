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
    echo("#Você está em combate!");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha!");
    exit();
}
if ($inrecrute) {
    mysql_close();
    echo("#Você está ocupado recrutando neste momento.");
    exit();
}

$query = "SELECT * FROM tb_missoes_r 
	WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 1) {
    $missao_r = mysql_fetch_array($result);
} else {
    mysql_close();
    echo("#Você não iniciou uma missão!");
    exit();
}
if ($missao_r["fim"] > atual_segundo()) {
    mysql_close();
    echo("#Você não concluiu a missão");
    exit();
}

switch ($missao_r["modif"]) {
    case 1:
        $rec = 30000;
        $xp = 450;
        break;
    case 2:
        $rec = 60000;
        $xp = 900;
        break;
    case 3:
        $rec = 90000;
        $xp = 1350;
        break;
    case 4:
        $rec = 120000;
        $xp = 1800;
        break;
    case 5:
        $rec = 180000;
        $xp = 2700;
        break;
    case 6:
        $rec = 240000;
        $xp = 3600;
        break;
    case 7:
        $rec = 480000;
        $xp = 7200;
        break;
}
$berries = $usuario["berries"] + $rec;

$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel receber a recompensa");

$userDetails->xp_for_all($xp);

$query = "DELETE FROM tb_missoes_r
	WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("Nao foi possivel concluir a missao");

mysql_close();
echo("-Missão concluida!");
exit();
?>