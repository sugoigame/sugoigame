<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!isset($_POST["nome"])) {
    mysql_close();
    echo("#Você informou algum caracter inválido.");
    exit();
}

$nome = mysql_real_escape_string(strip_tags($_POST["nome"]));


if (strlen($nome) == 0) {
    mysql_close();
    echo("#Acesso inválido.");
    exit();
}
if (!isset($_POST["senha_nova"]) OR empty($_POST["senha_nova"])) {
    $query = "UPDATE tb_conta SET nome='$nome' WHERE conta_id='" . $usuario["conta_id"] . "'";
    mysql_query($query) or die("Não foi possível atualizar seu nome");

    mysql_close();
    echo("Informações alteradas!");
} else {
    $senha = $_POST["senha_antiga"];
    $nsenha = $_POST["senha_nova"];
    if ($conta["senha"] != "" && !password_verify($senha, $conta["senha"])) {
        mysql_close();
        echo("#Senha inválida.");
        exit();
    }
    $nsenha = password_hash($nsenha, PASSWORD_BCRYPT);
    $query = "UPDATE tb_conta SET nome='$nome', senha='$nsenha' WHERE conta_id='" . $usuario["conta_id"] . "'";
    mysql_query($query) or die("Não foi possível atualizar seu nome");

    mysql_close();
    echo("Informações alteradas!");
}
?>