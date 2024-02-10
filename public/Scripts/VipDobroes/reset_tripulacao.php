<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}

$protector->need_dobroes(PRECO_DOBRAO_RENOMEAR_TRIPULACAO);

if (! isset($_GET["nome"]) or empty($_GET["nome"]) or $_GET["nome"] == "null") {

    echo ("#Um nome deve ser informado");
    exit();
}
$nome = $protector->get_alphanumeric_or_exit("nome");
if (! preg_match("/^[\w]/", $nome)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$newnome = $nome;

if (strlen($newnome) < 5) {

    echo ("#O nome deve ter no mínimo 5 caracteres.");
    exit();
}

$query = "SELECT * FROM tb_usuarios WHERE tripulacao='$newnome'";
$result = $connection->run($query);
if ($result->count() != 0) {

    echo ("Esse nome já está cadastrado");
    exit();
}

$connection->run("UPDATE tb_usuarios SET tripulacao = ? WHERE id = ?",
    "si", array($newnome, $userDetails->tripulacao["id"]));

$userDetails->reduz_dobrao(PRECO_DOBRAO_RENOMEAR_TRIPULACAO, "resetar_nome_tripulacao");

echo ("|Nome trocado!");

