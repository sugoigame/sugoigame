<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

if (!$userDetails->vip["formacoes"]) {
    $protector->exit_error("Você precisa adquirir uma vantagem VIP para usar esse recurso.");
}

$formacao_id = $protector->post_value_or_exit("formacao_id");

$exists = $connection->run("SELECT * FROM tb_tripulacao_formacao WHERE formacao_id = ?",
    "s", array($formacao_id))->count();

if ($exists) {
    $protector->exit_error("Essa formação já existe");
}

foreach ($userDetails->personagens as $pers) {
    if ($pers["cod"] != $userDetails->capitao["cod"]) {
        $connection->run(
            "INSERT INTO tb_tripulacao_formacao (tripulacao_id, formacao_id, personagem_id) VALUE (?,?,?)",
            "isi", array($userDetails->tripulacao["id"], $formacao_id, $pers["cod"])
        );
    }
}

echo "Formação criada!";