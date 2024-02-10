<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";


$nome = $protector->post_alphanumeric_or_exit("nome");


if (strlen($nome) == 0) {

    echo ("#Acesso inválido.");
    exit();
}
if (! isset($_POST["senha_nova"]) or empty($_POST["senha_nova"])) {
    $query = "UPDATE tb_conta SET nome='$nome' WHERE conta_id='" . $usuario["conta_id"] . "'";
    $connection->run($query) or die("Não foi possível atualizar seu nome");


    echo ("Informações alteradas!");
} else {
    $senha = $_POST["senha_antiga"];
    $nsenha = $_POST["senha_nova"];
    if ($conta["senha"] != "" && ! password_verify($senha, $conta["senha"])) {

        echo ("#Senha inválida.");
        exit();
    }
    $nsenha = password_hash($nsenha, PASSWORD_BCRYPT);
    $query = "UPDATE tb_conta SET nome='$nome', senha='$nsenha' WHERE conta_id='" . $usuario["conta_id"] . "'";
    $connection->run($query) or die("Não foi possível atualizar seu nome");


    echo ("Informações alteradas!");
}
?>

