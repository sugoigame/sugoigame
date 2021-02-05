<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$contaOk) {
    mysql_close();
    header("location:../../?msg=Vocce precisa estar logado.");
    exit();
}

if (!isset($_POST["faccao"])
    OR !isset($_POST["capitao"])
    OR !isset($_POST["icon_capitao"])
    OR !isset($_POST["oceano"])
    OR !isset($_POST["apelido"])
) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Formulário incompleto.");
    exit();
}

$faccao = mysql_real_escape_string(strip_tags($_POST["faccao"]));
$capitao = trim(mysql_real_escape_string($_POST["capitao"]));
$icon = mysql_real_escape_string(strip_tags($_POST["icon_capitao"]));
$oceano = mysql_real_escape_string(strip_tags($_POST["oceano"]));
$apelido = trim(mysql_real_escape_string($_POST["apelido"]));

if (!preg_match("/^[\d]+$/", $faccao)) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido4.");
    exit();
}
if (!preg_match("/^[\w ]+$/", $capitao)) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido5.");
    exit();
}
if (!preg_match("/^[\d]+$/", $icon)) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido6.");
    exit();
}
if (!preg_match("/^[\d]+$/", $oceano)) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido7.");
    exit();
}
if (!preg_match("/^[\w ]+$/", $apelido)) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido8.");
    exit();
}
$capitao = mysql_real_escape_string(stripslashes($capitao));

$erro = false;
if ($faccao != 0 AND $faccao != 1) {
    $erro = true;
}
if (strlen($capitao) < 3) {
    $erro = true;
}
if (strlen($apelido) < 3) {
    $erro = true;
}
if (strlen($icon) == 0) {
    $erro = true;
}
if (strlen($oceano) == 0) {
    $erro = true;
}

if ($erro) {
    mysql_close();
    header("location:../../?ses=seltrip&msg=Algum valor informado nao atende os requisitos de tamanho.");
    exit();
} else {
    $query = "SELECT nome FROM tb_personagens WHERE nome='$capitao'";
    $result = mysql_query($query);
    $cont = mysql_num_rows($result);

    if ($cont != 0) {
        mysql_close();
        header("location:../../?ses=seltrip&msg=O nome de capitão informado já está cadastrado.");
        exit();
    } else {
        $query = "SELECT * FROM tb_usuarios WHERE conta_id = '" . $conta["conta_id"] . "'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) >= 3) {
            mysql_close();
            header("location:../../?ses=seltrip&msg=O limite de tripulações por conta é de 3.");
            exit();
        }

        $id_encrip = md5(time());
        $query = "INSERT INTO tb_usuarios (conta_id, faccao, tripulacao) 
            VALUES ('" . $conta["conta_id"] . "', '$faccao', '$apelido')";
        mysql_query($query) or die("Nao foi possivel cadastrar a tripulacao");

        $id = mysql_insert_id();

        $i = 0;
        while ($i == 0) {
            if ($oceano == "1") {
                $x = 428;
                $y = 31;
                $i++;
            } else if ($oceano == "2") {
                $x = 70;
                $y = 51;
                $i++;
            } else if ($oceano == "3") {
                $x = 424;
                $y = 341;
                $i++;
            } else if ($oceano == "4") {
                $x = 35;
                $y = 337;
                $i++;
            } else {
                $oceano = mt_rand(1, 4);
            }
        }

        $query = "UPDATE tb_usuarios SET x='$x', y= '$y',
            res_x='$x', res_y='$y'
            WHERE id='$id'";
        mysql_query($query) or die ("nao foi possivel cadastrar coordenadas do usuario");

        $query = "INSERT INTO tb_personagens (id, img, nome)
            VALUES ('$id', '$icon', '$capitao')";
        mysql_query($query) or die ("nao foi possivel criar personagem");

        $codcapitao = mysql_insert_id();

        $query = "UPDATE tb_usuarios SET cod_personagem='$codcapitao' WHERE id='$id'";
        mysql_query($query) or die ("nao foi possivel cadastrar capitao");

        $query = "INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon)
            VALUES ('$codcapitao', '1', '1', 'Soco', 'Tenta acerta um soco no oponente.', '1')";
        mysql_query($query) or die ("nao foi possivel criar skil1");

        $query = "INSERT INTO tb_vip (id)
            VALUES ('$id')";
        mysql_query($query) or die ("nao foi possivel criar tb_vip");

        mysql_close();
        header("location:../../?ses=seltrip");
        exit();
    }
}

?>