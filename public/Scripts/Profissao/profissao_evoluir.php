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

    echo ("#Você precisa estar em uma ilha.");
    exit();
}

if (! isset($_GET["cod"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$cod = $protector->get_number_or_exit("cod");
if (! preg_match("/^[\d]+$/", $cod)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "'AND cod='$cod'";
$result = $connection->run($query);
$cont = $result->count();
if ($cont == 0) {

    echo ("#Personagem não encontrado.");
    exit();
}
$personagem = $result->fetch_array();
if ($personagem["profissao_xp"] < $personagem["profissao_xp_max"]) {

    echo ("#Personagem não evoluiu a profissão ainda.");
    exit();
}

$query = "SELECT * FROM tb_ilha_profissoes WHERE ilha='" . $userDetails->ilha["ilha"] . "' AND profissao='" . $personagem["profissao"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Essa ilha não ensina essa profissão.");
    exit();
}

$profissao = $result->fetch_array();
if ($profissao["profissao_lvl_max"] <= $personagem["profissao_lvl"]) {

    echo ("#Essa ilha não ensina essa profissão2.");
    exit();
}
$preco = $personagem["profissao_lvl"] * 2000;
$berries = $usuario["berries"] - $preco;
if ($berries < 0) {

    echo ("#Você não possui dinheiro suficiente.");
    exit();
}

$query = "UPDATE tb_usuarios SET berries='$berries' WHERE id='" . $usuario["id"] . "'";
$connection->run($query) or die("nao foi possivel pagar o treinamento");
$newxp = $personagem["profissao_xp_max"] + 250;
$newlvl = $personagem["profissao_lvl"] + 1;
$query = "UPDATE tb_personagens SET profissao_xp='0', profissao_xp_max='$newxp', profissao_lvl='$newlvl' WHERE cod='$cod'";
$connection->run($query) or die("nao foi possivel iniciar o treinamento");


echo ("@Profissão evoluída!");
exit();
?>

