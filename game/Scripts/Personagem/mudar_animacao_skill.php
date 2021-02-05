<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->post_number_or_exit("pers");
$effect = $protector->post_value_or_exit("effect");
$cod_skil = $protector->post_number_or_exit("cod_skil");
$tipo = $protector->post_number_or_exit("tipo");

if (!$userDetails->get_pers_by_cod($pers)) {
    $protector->exit_error("Personagem Invalido");
}

$animacao = $connection->run("SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
    "is", array($userDetails->tripulacao["id"], $effect));

if (!$animacao->count()) {
    $protector->exit_error("Você não possui essa animação");
}

$connection->run("UPDATE tb_personagens_skil SET effect = ? WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "siii", array($effect, $pers, $cod_skil, $tipo));

$n_quant = $animacao->fetch_array()["quant"] - 1;

if ($n_quant) {
    $connection->run("UPDATE tb_tripulacao_animacoes_skills SET quant = quant - 1 WHERE tripulacao_id = ? AND effect = ?",
        "is", array($userDetails->tripulacao["id"], $effect));
} else {
    $connection->run("DELETE FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
        "is", array($userDetails->tripulacao["id"], $effect));
}

echo "-Você mudou a animação da sua habilidade!";