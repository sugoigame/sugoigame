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
    echo("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if (!isset($_GET["prof"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$prof = mysql_real_escape_string($_GET["prof"]);
$cod = mysql_real_escape_string($_GET["cod"]);

if (!preg_match("/^[\d]+$/", $cod)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]+$/", $prof)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
$cont = mysql_num_rows($result);
if ($cont == 0) {
    mysql_close();
    echo("#Personagem não encontrado.");
    exit();
}
$personagem = mysql_fetch_array($result);

$query = "SELECT * FROM tb_ilha_profissoes WHERE ilha='" . $usuario["ilha"] . "'";
$result = mysql_query($query);
$possivel = FALSE;
for ($i = 0; $sql = mysql_fetch_array($result); $i++) {
    $ilha_profs[$i] = $sql;
    if ($ilha_profs[$i]["profissao"] == $prof) {
        $possivel = TRUE;
    }
}
if ($usuario["berries"] < 1000) {
    mysql_close();
    echo("#Dinheiro insuficiente.");
    exit();
}

if ($personagem["profissao_lvl"] == 0 AND $personagem["profissao"] == 0 AND $possivel) {
    $berries = $usuario["berries"] - 1000;

    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
    mysql_query($query) or die("nao foi possivel pagar o treinamento");

    $query = "UPDATE tb_personagens SET profissao='$prof', profissao_lvl='1', profissao_xp_max='250' WHERE cod='$cod'";
    mysql_query($query) or die("nao foi possivel iniciar o treinamento");

    mysql_close();
    $response->send_conquista_pers($personagem, $personagem["nome"] . " se tornou um " . nome_prof($prof) . "!");
} else {
    mysql_close();
    echo("#Este personagem não pode aprender essa profissão.");
}
