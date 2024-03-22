<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (! isset($_GET["i"]) or ! isset($_GET["cod"])) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
$id = $protector->get_number_or_exit("i");
$cod = $_GET["cod"];

if (! preg_match(INT_FORMAT, $id) or ! preg_match(STR_FORMAT, $cod)) {

    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (! empty($id) and ! empty($cod)) {
    $query = "SELECT ativacao FROM tb_conta WHERE conta_id='$id' LIMIT 1";
    $result = $connection->run($query);
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

        $query = "UPDATE tb_conta SET ativacao = NULL, gold = gold + " . BONUS_GOLD_ATIVACAO . " WHERE conta_id='$id'";
        $connection->run($query) or die("nao foi possivel ativar a conta");

        //redireciona

        header("location:../../?ses=ativacaosucess");
    }

} else {

    header("location:../../?erro=1");
}
?>

