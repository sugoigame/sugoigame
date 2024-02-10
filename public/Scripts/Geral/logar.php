<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

$login = $_POST["login"];
$senha = $_POST["senha"];


if (! isset($login) || ! isset($senha)) {
    header("location:../../login.php?erro=1");
    exit();
}
if (! preg_match(EMAIL_FORMAT, $login)) {
    header("location:../../login.php?erro=1");
    exit();
}

if (! empty($login)) {
    $result = $connection->run("SELECT conta_id, senha, ativacao, tripulacao_id, beta FROM tb_conta WHERE email = ? LIMIT 1", "s", $login);

    // Se nao encontrar o login e senha
    if ($result->count() == 0) {
        header("location:../../login.php?erro=1");
        exit();
    } else {
        $conta = $result->fetch_array();

        if (! password_verify($senha, $conta["senha"])) {
            header("location:../../login.php?erro=1");
            exit();
        } else if (IS_BETA && $conta['beta'] != 1) {
            header("location:../../login.php?erro=2");
            exit();
        } else {
            $userDetails->set_authentication($conta["conta_id"]);

            // Redireciona
            if ($conta["tripulacao_id"])
                header("location:../../?ses=home");
            else
                header("location:../../?ses=seltrip");
        }
    }
} else {
    header("location:../../login.php?erro=1");
    exit();
}
