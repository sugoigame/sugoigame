<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

    header("location:../../?msg=Você precisa estar logado.");
    exit();
}
if ($inally) {

    header("location:../../?msg=Você ja faz parte de uma aliança/frota");
    exit();
}
if (! isset($_POST["pagamento"]) or ! isset($_POST["nome"])) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}

$forma = $protector->post_number_or_exit("pagamento");
$nome = $protector->post_alphanumeric_or_exit("nome");

if (! preg_match("/^[\d]+$/", $forma)) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (! preg_match("/^[\w]+$/", $nome)) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (strlen($nome) < 5) {

    header("location:../../?msg=Esse nome é muito curto.");
    exit();
}
$query = "SELECT * FROM tb_alianca WHERE nome='$nome'";
$result = $connection->run($query);
if ($result->count() != 0) {

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
$connection->run($query) or die("Erro ao efetuar criacao");

$query = "SELECT * FROM tb_alianca WHERE nome='$nome'";
$result = $connection->run($query);
$alianca = $result->fetch_array();

$query = "INSERT INTO tb_alianca_membros (cod_alianca, id, autoridade)
	VALUES ('" . $alianca["cod_alianca"] . "', '" . $usuario["id"] . "', '0')";
$connection->run($query) or die("Erro ao efetuar criacao");


header("location:../../?ses=alianca");
?>

