<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (isset($_GET["tripulacao_alvo"])) {
    $alvo_tripulacao = trim($protector->get_alphanumeric_or_exit("alvo"));

    $id = $connection->run("SELECT id FROM tb_usuarios WHERE tripulacao = ?",
        "s", array($alvo_tripulacao));

    if (!$id->count()) {
        $protector->exit_error("Alvo não encontrado");
    }

    $alvo = $id->fetch_array()["id"];
} else {
    $alvo = $protector->get_number_or_exit("alvo");
}

if ($alvo == $userDetails->tripulacao["id"]) {
    $protector->exit_error("Você não pode desafiar a si mesmo");
}

$desafiado = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?",
    "i", array($userDetails->tripulacao["id"]));

if ($desafiado->count()) {
    $protector->exit_error("Você não pode enviar desafios enquanto tiver um desafio pendente");
}

$desafiado = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?",
    "i", array($alvo));

if ($desafiado->count()) {
    $protector->exit_error("O seu alvo já possui um desafio pendente");
}

$in_combate = $connection->run("SELECT * FROM tb_combate WHERE id_1 = ? OR id_2 = ?",
    "ii", array($alvo, $alvo));

if ($in_combate->count()) {
    $protector->exit_error("O seu alvo já está em combate");
}

$in_combate = $connection->run("SELECT * FROM tb_combate_npc WHERE id = ?",
    "i", array($alvo));

if ($in_combate->count()) {
    $protector->exit_error("O seu alvo já está em combate");
}

$in_combate = $connection->run("SELECT * FROM tb_combate_bot WHERE tripulacao_id = ?",
    "i", array($alvo));

if ($in_combate->count()) {
    $protector->exit_error("O seu alvo já está em combate");
}

$connection->run("INSERT INTO tb_combate_desafio (desafiante, desafiante_nome, desafiado) VALUE (?,?,?)",
    "isi", array($userDetails->tripulacao["id"], $userDetails->tripulacao["tripulacao"], $alvo));

echo "-Desafio enviado";
