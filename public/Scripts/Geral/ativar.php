<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (! isset($_GET["email"]) or ! isset($_GET["cod"])) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
$login = $_GET["email"];
$cod = $_GET["cod"];

if (! preg_match(EMAIL_FORMAT, $login) or ! preg_match(STR_FORMAT, $cod)) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (! empty($login) and ! empty($cod)) {
    $query = "SELECT ativacao FROM tb_conta WHERE email=? LIMIT 1";
    $result = $connection->run($query, "s", $login);
    $cont = $result->count();

    //se nao encontrar o login e senha
    if ($cont == 0) {

        header("location:../../?erro=1");
        exit();
    } //se encontrar
    else {
        $id_encrip = $result->fetch_array();

        if ($id_encrip["ativacao"] != $cod) {

            header("location:../../?ses=cadastrosucess&erro=1");
            exit();
        }

        $query = "UPDATE tb_conta SET ativacao = NULL, gold = gold + " . BONUS_GOLD_ATIVACAO . " WHERE email=?";
        $connection->run($query, "s", $login) or die("nao foi possivel ativar a conta");

        //redireciona

        header("location:../../?ses=ativacaosucess");
    }

} else {

    header("location:../../?erro=1");
}
