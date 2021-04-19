<?php
include "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

if (!$contaOk) {
    header("location:../../?msg=Vocce precisa estar logado.");
    exit();
}

if (!isset($_POST["faccao"])
    OR !isset($_POST["capitao"])
    OR !isset($_POST["icon_capitao"])
    OR !isset($_POST["oceano"])
    OR !isset($_POST["apelido"])
) {
    header("location:../../?ses=seltrip&msg=Formulário incompleto.");
    exit();
}

$faccao     = strip_tags($_POST["faccao"]);
$capitao    = trim($_POST["capitao"]);
$icon       = strip_tags($_POST["icon_capitao"]);
$oceano     = strip_tags($_POST["oceano"]);
$apelido    = trim($_POST["apelido"]);

if (!preg_match("/^[\d]+$/", $faccao)) {
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido4.");
    exit();
}
if (!preg_match("/^[\w ]+$/", $capitao)) {
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido5.");
    exit();
}
if (!preg_match("/^[\d]+$/", $icon)) {
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido6.");
    exit();
}
if (!preg_match("/^[\d]+$/", $oceano)) {
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido7.");
    exit();
}
if (!preg_match("/^[\w ]+$/", $apelido)) {
    header("location:../../?ses=seltrip&msg=Você informou algum caracter inválido8.");
    exit();
}
$capitao = stripslashes($capitao);

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
    header("location:../../?ses=seltrip&msg=Algum valor informado nao atende os requisitos de tamanho.");
    exit();
} else {
    $cont = $connection->run("SELECT nome FROM tb_personagens WHERE nome = ? LIMIT 1", 's', [
        $capitao
    ])->count();

    if ($cont != 0) {
        header("location:../../?ses=seltrip&msg=O nome de capitão informado já está cadastrado.");
        exit();
    } else {
        $result = $connection->run("SELECT * FROM tb_usuarios WHERE conta_id = ?", 'i', [
            $conta['conta_id']
        ]);
        if ($result->count() >= 3) {
            header("location:../../?ses=seltrip&msg=O limite de tripulações por conta é de 3.");
            exit();
        }

        $id_encrip = md5(time());
        $connection->run("INSERT INTO tb_usuarios (conta_id, faccao, tripulacao)  VALUES (?, ?, ?)", 'iis', [
            $conta['conta_id'],
            $faccao,
            $apelido
        ]);

        $id     = $connection->last_id();
        $navio = $connection->run("SELECT cod_navio FROM tb_navio ORDER BY limite ASC LIMIT 1")->fetch_array();
        $connection->run("INSERT INTO tb_usuario_navio (id,cod_navio,cod_casco,cod_leme,cod_velas,hp,hp_max,lvl) VALUES (?,?,'0','0','0','100','100','1')", 'ii', [
            $id,
            $navio['cod_navio']
        ]);

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
                $oceano = rand(1, 4);
            }
        }

        $connection->run("UPDATE tb_usuarios SET x = ?, y = ?, res_x = ?, res_y = ? WHERE id = ?", 'iiiii', [
            $x,
            $y,
            $x,
            $y,
            $id
        ]);
        $connection->run("INSERT INTO tb_personagens (id, img, nome, xp_max) VALUES (?, ?, ?, ?)", 'iisi', [
            $id,
            $icon,
            $capitao,
            formulaExp()
        ]);

        $codcapitao = $connection->last_id();

        $connection->run("UPDATE tb_usuarios SET cod_personagem = ? WHERE id = ?", 'ii', [
            $codcapitao,
            $id
        ]);
        $connection->run("INSERT INTO tb_personagens_skil (cod, cod_skil, tipo, nome, descricao, icon) VALUES (?, ?, ?, 'Soco', 'Tenta acerta um soco no oponente.', '1')", 'iii', [
            $codcapitao,
            COD_SKILL_SOCO,
            TIPO_SKILL_ATAQUE_CLASSE
        ]);

        $connection->run("INSERT INTO tb_vip (id) VALUES (?)", 'i', $id);

        header("location:../../?ses=seltrip");
        exit();
    }
}