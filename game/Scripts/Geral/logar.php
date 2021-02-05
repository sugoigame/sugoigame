<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (!isset($_POST["login"]) OR !isset($_POST["senha"])) {
    mysql_close();
    header("location:../../login.php?erro=1");
    exit();
}
$login = mysql_real_escape_string($_POST["login"]);
$senha = $_POST["senha"];

if (!preg_match(EMAIL_FORMAT, $login)) {
    mysql_close();
    header("location:../../login.php?erro=1");
    exit();
}

if (!empty($login)) {
    $query = "SELECT conta_id, senha, ativacao, tripulacao_id FROM tb_conta WHERE email='$login' LIMIT 1";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    //se nao encontrar o login e senha
    if ($cont == 0) {
        mysql_close();
        header("location:../../login.php?erro=1");
        exit();
    } //se encontrar
    else {
        $conta = mysql_fetch_array($result);

        if (!password_verify($senha, $conta["senha"])) {
            mysql_close();
            header("location:../../login.php?erro=1");
            exit();
        }

        $userDetails->set_authentication($conta["conta_id"]);

        //redireciona
        mysql_close();
        if ($conta["tripulacao_id"]) {
            header("location:../../index.php?ses=home");
        } else {
            header("location:../../index.php?ses=seltrip");
        }
    }

} else {
    mysql_close();
    header("location:../../login.php?erro=1");
}