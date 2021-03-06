<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";
if (!$conect) {
    mysql_close();
    echo("#Você precisa estar logado!");
    exit();
}

$protector->need_dobroes(PRECO_DOBRAO_RENOMEAR_TRIPULACAO);

if (!isset($_GET["nome"]) OR empty($_GET["nome"]) OR $_GET["nome"] == "null") {
    mysql_close();
    echo("#Um nome deve ser informado");
    exit();
}
$nome = mysql_real_escape_string($_GET["nome"]);
if (!preg_match("/^[\w]/", $nome)) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}
$newnome = $nome;

if (strlen($newnome) < 5) {
    mysql_close();
    echo("#O nome deve ter no mínimo 5 caracteres.");
    exit();
}

$query = "SELECT * FROM tb_usuarios WHERE tripulacao='$newnome'";
$result = mysql_query($query);
if (mysql_num_rows($result) != 0) {
    mysql_close();
    echo("Esse nome já está cadastrado");
    exit();
}

$connection->run("UPDATE tb_usuarios SET tripulacao = ? WHERE id = ?",
    "si", array($newnome, $userDetails->tripulacao["id"]));

$userDetails->reduz_dobrao(PRECO_DOBRAO_RENOMEAR_TRIPULACAO, "resetar_nome_tripulacao");

echo("|Nome trocado!");
mysql_close();
