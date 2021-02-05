<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado.");
    exit();
}
if (!isset($_GET["afilhado"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$afilhado = mysql_real_escape_string($_GET["afilhado"]);

if (!preg_match("/^[\d]+$/", $afilhado)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_afilhados WHERE id='" . $usuario["id"] . "' AND afilhado='$afilhado'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Você não é padrinho deste personagem.");
    exit();
}

$query = "SELECT * FROM tb_usuarios WHERE id='$afilhado'";
$result = mysql_query($query);
$afilhado_info = mysql_fetch_array($result);

$query = "SELECT * FROM tb_personagens WHERE cod='" . $afilhado_info["cod_personagem"] . "'";
$result = mysql_query($query);
$afilhado_pers = mysql_fetch_array($result);

if ($afilhado_pers["lvl"] < 30) {
    mysql_close();
    echo("#Esse afilhado nao alcançou o nível 30 ainda.");
    exit();
}

$query = "SELECT * FROM tb_afilhados_recrutados WHERE id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    $query = "INSERT INTO tb_afilhados_recrutados (id, quant)
		VALUES ('" . $usuario["id"] . "', '1')";
    mysql_query($query) or die("nao foi possivel registrar o jogador");
} else {
    $afilhado_registrado = mysql_fetch_array($result);
    $query = "UPDATE tb_afilhados_recrutados SET quant='" . ($afilhado_registrado["quant"] + 1) . "'
		WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("nao foi possivel registrar o jogador");
}

$gold = $usuario["gold"] + 25;
$query = "UPDATE tb_conta SET gold='$gold' WHERE conta_id='" . $userDetails->conta["conta_id"] . "'";
mysql_query($query) or die("Nao foi possivel pegar o gold");

$query = "DELETE FROM tb_afilhados WHERE id='" . $usuario["id"] . "' AND afilhado='$afilhado'";
mysql_query($query) or die("Nao foi possivel remover o afilhado");
mysql_close();
echo("Parabéns!<br>Você pegou sua recompensa por ter recrutado esse jogador!");
?>