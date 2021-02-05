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
if ($inmissao) {
    mysql_close();
    echo("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (!$inilha) {
    mysql_close();
    echo("#Você precisa estar em uma ilha.");
    exit();
}

if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$cod = mysql_real_escape_string($_GET["cod"]);
if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "'AND cod='$cod'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = mysql_fetch_array($result);
if ($personagem["profissao_xp"] < $personagem["profissao_xp_max"]) {
    mysql_close();
    echo("#Personagem não evoluiu a profissão ainda.");
    exit();
}

$query = "SELECT * FROM tb_ilha_profissoes WHERE ilha='" . $userDetails->ilha["ilha"] . "' AND profissao='" . $personagem["profissao"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Essa ilha não ensina essa profissão.");
    exit();
}

$profissao = mysql_fetch_array($result);
if ($profissao["profissao_lvl_max"] <= $personagem["profissao_lvl"]) {
    mysql_close();
    echo("#Essa ilha não ensina essa profissão2.");
    exit();
}
$preco = $personagem["profissao_lvl"] * 2000;
$berries = $usuario["berries"] - $preco;
if ($berries < 0) {
    mysql_close();
    echo("#Você não possui dinheiro suficiente.");
    exit();
}

$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
mysql_query($query) or die("nao foi possivel pagar o treinamento");
$newxp = $personagem["profissao_xp_max"] + 250;
$newlvl = $personagem["profissao_lvl"] + 1;
$query = "UPDATE tb_personagens SET profissao_xp='0', profissao_xp_max='$newxp', profissao_lvl='$newlvl' WHERE cod='$cod'";
mysql_query($query) or die("nao foi possivel iniciar o treinamento");

mysql_close();
echo("@Profissão evoluída!");
exit();
?>