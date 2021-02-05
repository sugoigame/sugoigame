<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (!$conect) {
    mysql_close();
    header("location:../../?msg=Você precisa estar logado.");
    exit();
}
if ($inally) {
    mysql_close();
    header("location:../../?msg=Você ja faz parte de uma aliança/frota");
    exit();
}
if (!isset($_POST["pagamento"]) OR !isset($_POST["nome"])) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}

$forma = mysql_real_escape_string($_POST["pagamento"]);
$nome = mysql_real_escape_string($_POST["nome"]);

if (!preg_match("/^[\d]+$/", $forma)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (!preg_match("/^[\w]+$/", $nome)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (strlen($nome) < 5) {
    mysql_close();
    header("location:../../?msg=Esse nome é muito curto.");
    exit();
}
$query = "SELECT * FROM tb_alianca WHERE nome='$nome'";
$result = mysql_query($query);
if (mysql_num_rows($result) != 0) {
    mysql_close();
    header("location:../../?msg=Esse nome não está disponivel.");
    exit();
}

switch ($forma) {
    case 1:
        $preco = $userDetails->tripulacao["berries"] - 10000000;
        if ($preco <= 0) {
            header("location:../../?msg=Você não tem berries o suficiente.");
            exit();
        }
        $connection->run("UPDATE tb_usuarios SET berries='$preco' WHERE id=?", "i", $userDetails->tripulacao["id"]);
        break;
    case 2:
        $preco = $userDetails->conta["gold"] - 8;
        if ($preco <= 0) {
            header("location:../../?msg=Você não tem ouro o suficiente.");
            exit();
        }
        $connection->run("UPDATE tb_conta SET gold='$preco' WHERE conta_id=?", "i", $userDetails->conta["conta_id"]);

        $connection->run("INSERT INTO tb_gold_log (user_id, quant, script) VALUES (?, ? ,?)",
            "iis", array($userDetails->tripulacao["id"], 200, "criar_alianca"));
        break;
    default:
        header("location:../../?msg=Opção errada");
        exit();
        break;
}

$query = "INSERT INTO tb_alianca (nome)
	VALUES ('$nome')";
mysql_query($query) or die("Erro ao efetuar criacao");

$query = "SELECT * FROM tb_alianca WHERE nome='$nome'";
$result = mysql_query($query);
$alianca = mysql_fetch_array($result);

$query = "INSERT INTO tb_alianca_membros (cod_alianca, id, autoridade)
	VALUES ('" . $alianca["cod_alianca"] . "', '" . $usuario["id"] . "', '0')";
mysql_query($query) or die("Erro ao efetuar criacao");

mysql_close();
header("location:../../?ses=alianca");
?>