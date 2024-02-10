<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    echo ("#Você precisa estar logado.");
    exit();
}
if ($inmissao) {

    echo ("#Você está ocupado em uma missão neste meomento.");
    exit();
}
if (! $inilha) {

    echo ("#Você precisa estar em uma ilha para comprar itens.");
    exit();
}
if (! isset($_GET["prof"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! isset($_GET["cod"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$prof = $protector->get_number_or_exit("prof");
$cod = $protector->get_number_or_exit("cod");

if (! preg_match("/^[\d]+$/", $cod)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]+$/", $prof)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE cod='$cod' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont == 0) {

    echo ("#Personagem não encontrado.");
    exit();
}
$personagem = $result->fetch_array();

$query = "SELECT * FROM tb_ilha_profissoes WHERE ilha='" . $usuario["ilha"] . "'";
$result = $connection->run($query);
$possivel = FALSE;
for ($i = 0; $sql = $result->fetch_array(); $i++) {
    $ilha_profs[$i] = $sql;
    if ($ilha_profs[$i]["profissao"] == $prof) {
        $possivel = TRUE;
    }
}
if ($usuario["berries"] < 1000) {

    echo ("#Dinheiro insuficiente.");
    exit();
}

if ($personagem["profissao_lvl"] == 0 and $personagem["profissao"] == 0 and $possivel) {
    $berries = $usuario["berries"] - 1000;

    $query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
    $connection->run($query) or die("nao foi possivel pagar o treinamento");

    $query = "UPDATE tb_personagens SET profissao='$prof', profissao_lvl='1', profissao_xp_max='250' WHERE cod='$cod'";
    $connection->run($query) or die("nao foi possivel iniciar o treinamento");


    $response->send_conquista_pers($personagem, $personagem["nome"] . " se tornou um " . nome_prof($prof) . "!");
} else {

    echo ("#Este personagem não pode aprender essa profissão.");
}
