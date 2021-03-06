<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_dobroes(PRECO_DOBRAO_RESET_NOME_PERSONAGEM);

if (!isset($_GET["nome"]) OR empty($_GET["nome"]) OR $_GET["nome"] == "null") {
    mysql_close();
    echo("#Um nome deve ser informado");
    exit();
}
if (!isset($_GET["cod"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$nome = mysql_real_escape_string(strip_tags($_GET["nome"]));
$personagem = mysql_real_escape_string($_GET["cod"]);
if (!preg_match("/^[\w]/", $nome)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\d]/", $personagem)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$newnome = "";
for ($x = 0; $x < strlen($nome); $x++) {
    if (substr($nome, $x, 1) != " ")
        $newnome .= substr($nome, $x, 1);
}
if (strlen($newnome) < 5) {
    mysql_close();
    echo("#O nome deve ter no mínimo 5 caracteres.");
    exit();
}

$query = "SELECT * FROM tb_personagens WHERE nome='$newnome'";
$result = mysql_query($query);
if (mysql_num_rows($result) != 0) {
    mysql_close();
    echo("#Esse nome já está cadastrado");
    exit();
}
$query = "SELECT * FROM tb_personagens WHERE cod='$personagem' AND id='" . $usuario["id"] . "'";
$result = mysql_query($query);
if (mysql_num_rows($result) == 0) {
    mysql_close();
    echo("#Persoangem nao encontrado");
    exit();
}
$pers = mysql_fetch_array($result);

$query = "UPDATE tb_personagens 
	SET nome='$newnome'
	WHERE cod='$personagem'";
mysql_query($query) or die("Nao foi possivel resetar os atributos.");

$userDetails->reduz_dobrao(PRECO_DOBRAO_RESET_NOME_PERSONAGEM, "resetar_nome_tripulante");

echo("-Nome trocado!");
mysql_close();
