<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (!isset($_GET["i"]) OR !isset($_GET["cod"])) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
$id = mysql_real_escape_string($_GET["i"]);
$cod = $_GET["cod"];

if (!preg_match(INT_FORMAT, $id) OR !preg_match(STR_FORMAT, $cod)) {
    mysql_close();
    header("location:../../?msg=Você informou algum caracter inválido.");
    exit();
}
if (!empty($id) AND !empty($cod)) {
    $query = "SELECT ativacao FROM tb_conta WHERE conta_id='$id' LIMIT 1";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    //se nao encontrar o login e senha
    if ($cont == 0) {
        mysql_close();
        header("location:../../?erro=1");
        exit();
    } //se encontrar
    else {
        $id_encrip = mysql_fetch_array($result);

        if ($id_encrip["ativacao"] != $cod) {
            mysql_close();
            header("location:../../?ses=cadastrosucess&erro=1");
            exit();
        }

        $query = "UPDATE tb_conta SET ativacao = NULL, dobroes = dobroes + " . BONUS_DOBROES_ATIVACAO . " WHERE conta_id='$id'";
        mysql_query($query) or die ("nao foi possivel ativar a conta");

        //redireciona
        mysql_close();
        header("location:../../?ses=ativacaosucess");
    }

} else {
    mysql_close();
    header("location:../../?erro=1");
}
?>