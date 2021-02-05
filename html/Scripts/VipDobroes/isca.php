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

if ($tipo == 16) $porc = 25;
else if ($tipo == 17) $porc = 90;
else $porc = 0;

$query = "UPDATE tb_usuarios SET isca='$porc' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("nao foi possivel usar a isca");

mysql_close();
echo("Você acaba de ativar uma isca!<br>Assim que você navegar suas chances de encontrar uma criatura Marítmica 
	serão aumentadas em $porc%<br>Esse recurso é válido somente por uma mudança de quadro.");
?>