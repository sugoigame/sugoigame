<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
include "../../Includes/verifica_missao.php";

if (! $conect) {

    echo ("#Você precisa estar logado!");
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
if (! isset($_GET["cod"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$cod = $protector->get_number_or_exit("cod");

if (! ereg("([0-9])", $cod)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Personagem não encotrado.");
    exit();
}

$personagem = $result->fetch_array();

$query = "DELETE FROM tb_personagens WHERE id='" . $usuario["id"] . "' AND cod='$cod' LIMIT 1";
$connection->run($query) or die("Nao foi possivel demitir o persoangem1");

$query = "DELETE FROM tb_personagens_skil WHERE cod='$cod'";
$connection->run($query) or die("Nao foi possivel demitir o persoangem");


echo ("Tripulante expulso!");
exit();
?>

