<?php
include "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

$protector->need_conta();

if (! isset($_POST["faccao"])
    || ! isset($_POST["capitao"])
    || ! isset($_POST["icon_capitao"])
    || ! isset($_POST["oceano"])
    || ! isset($_POST["apelido"])
) {
    header("location:../../?ses=seltrip&msg=F||mulário incompleto.");
    exit();
}

$faccao = $protector->post_enum_or_exit("faccao", [FACCAO_PIRATA, FACCAO_MARINHA]);
$capitao = $protector->post_alphanumeric_with_space_or_exit("capitao");
$icon = $protector->post_number_or_exit("icon_capitao");
$oceano = $protector->post_number_or_exit("oceano");
$apelido = $protector->post_alphanumeric_with_space_or_exit("apelido");

$erro = false;
if (strlen($capitao) < 3) {
    $protector->exit_error("O nome do capitão precisa de 3 caracteres");
}
if (strlen($apelido) < 3) {
    $protector->exit_error("O nome da tripulação precisa de 3 caracteres");
}

$cont = $connection->run("SELECT nome FROM tb_personagens WHERE nome = ? LIMIT 1", 's', [
    $capitao
])->count();
if ($cont != 0) {
    $protector->exit_error("O nome de capitão informado já está cadastrado.");
}

$result = $connection->run("SELECT * FROM tb_usuarios WHERE conta_id = ?", 'i', [
    $conta['conta_id']
]);
if ($result->count() >= 3) {
    $protector->exit_error("O limite de tripulações por conta é de 3.");
}

$id_encrip = md5(time());
$connection->run("INSERT INTO tb_usuarios (conta_id, faccao, tripulacao)  VALUES (?, ?, ?)", 'iis', [
    $conta['conta_id'],
    $faccao,
    $apelido
]);

$id = $connection->last_id();

$i = 0;
while ($i == 0) {
    if ($oceano == "1") {
        $x = 428;
        $y = 31;
        $i++;
    } elseif ($oceano == "2") {
        $x = 70;
        $y = 51;
        $i++;
    } elseif ($oceano == "3") {
        $x = 424;
        $y = 341;
        $i++;
    } elseif ($oceano == "4") {
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

$connection->run("INSERT INTO tb_vip (id) VALUES (?)", 'i', [$id]);

echo "%seltrip";
