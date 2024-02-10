<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (! $conect) {

    echo ("#Você precisa estar logado!");
    exit();
}

$protector->need_gold(PRECO_GOLD_RESET_NOME_PERSONAGEM);

if (! isset($_GET["nome"]) or empty($_GET["nome"]) or $_GET["nome"] == "null") {

    echo ("#Um nome deve ser informado");
    exit();
}
if (! isset($_GET["cod"])) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$nome = $protector->get_alphanumeric_or_exit("nome");
$personagem = $protector->get_number_or_exit("cod");
if (! preg_match("/^[\w]/", $nome)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\d]/", $personagem)) {

    echo ("#Você informou algum caracter inválido.");
    exit();
}
$newnome = "";
for ($x = 0; $x < strlen($nome); $x++) {
    if (substr($nome, $x, 1) != " ")
        $newnome .= substr($nome, $x, 1);
}
if (strlen($newnome) < 4) {

    echo ("#O nome deve ter no mínimo 4 caracteres.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE nome='$newnome'";
$result = $connection->run($query);
if ($result->count() != 0) {

    echo ("#Esse nome já está cadastrado");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$personagem' AND id='" . $usuario["id"] . "'";
$result = $connection->run($query);
if ($result->count() == 0) {

    echo ("#Persoangem nao encontrado");
    exit();
}
$pers = $result->fetch_array();


$query = "UPDATE tb_personagens 
	SET nome='$newnome'
	WHERE cod='$personagem'";
$connection->run($query) or die("Nao foi possivel resetar os atributos.");

$userDetails->reduz_gold(PRECO_GOLD_RESET_NOME_PERSONAGEM, "resetar_nome_tripulante");

echo ("-Nome trocado!");

