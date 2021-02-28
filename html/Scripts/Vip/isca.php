<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}
if (!isset($_GET["tipo"])) {
    mysql_close();
    echo("#tipo não informado");
    exit();
}
if ($_GET["tipo"] != 16 AND $_GET["tipo"] != 17) {
    mysql_close();
    echo("#Você informou algo inválido");
    exit();
}
$tipo = $_GET["tipo"];

if (1 == 1) {
    $protector->exit_error("Temporariamente indisponível.");
}

if ($userDetails->navio["ultimo_disparo_sofrido"] > (atual_segundo() - 30)) {
    $protector->exit_error("Você foi atingido por um canhão a menos de 30 segundos e precisa esperar para usar uma isca.");
}

$query = "SELECT * FROM tb_usuario_itens WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Você não possui esse item");
    exit();
}
$quant = mysql_fetch_array($result);
if ($quant["quant"] <= 1) $query = "DELETE FROM tb_usuario_itens WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
else {
    $nquant = $quant["quant"] - 1;
    $query = "UPDATE tb_usuario_itens SET quant='$nquant' WHERE tipo_item='$tipo' AND id='" . $usuario["id"] . "'";
}
mysql_query($query) or die("nao foi possivel remover a isca");

if ($tipo == 16) $porc = 30;
else if ($tipo == 17) $porc = 100;
else $porc = 0;

if (rand(1, 100) <= $porc) {
    atacar_rdm(rand(1, 4));
    echo "%combate";
} else {
    echo "Nada aconteceu.";
}