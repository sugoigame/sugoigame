<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$link = $protector->post_value_or_exit("link");

$link = explode("&id=", $link);

if (!isset($link[1])) {
    $protector->exit_error("Link incorreto");
}

$id_encrip = $link[1];

if ($id_encrip == $userDetails->conta["id_encrip"]) {
    $protector->exit_error("Link inválido");
}

$result = $connection->run("SELECT * FROM tb_afilhados WHERE afilhado = ?",
    "i", array($userDetails->conta["conta_id"]));
if ($result->count()) {
    $protector->exit_error("Você já foi recrutado");
}

if ((time() - strtotime($userDetails->conta["cadastro"])) > (7 * 24 * 60 * 60)) {
    $protector->exit_error("Sua conta é muita antiga para ser recrutada");
}

$result = $connection->run("SELECT * FROM tb_conta WHERE id_encrip=?", "s", $id_encrip);
if (!$result->count()) {
    $protector->exit_error("Link não encontrado.");
}
$padrinho_info = $result->fetch_array();

$connection->run("INSERT INTO tb_afilhados (id, afilhado) VALUES (?, ?)", "ss", array($padrinho_info["conta_id"], $userDetails->conta["conta_id"]));

echo "-Parabéns! O jogador informado agora é reconhecido como o seu recrutador!";