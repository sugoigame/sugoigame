<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->post_number_or_exit("pers");
$effect = $protector->post_value_or_exit("effect");
$cod_skil = $protector->post_number_or_exit("cod_skil");

if (! $userDetails->get_pers_by_cod($pers)) {
    $protector->exit_error("Personagem Invalido");
}

$habilidade = \Regras\Habilidades::get_habilidade_by_cod($cod_skill);

if (! $habilidade) {
    $protector->exit_error("Habilidade inválida");
}

$animacao = $connection->run("SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
    "is", array($userDetails->tripulacao["id"], $effect));

if (! $animacao->count()) {
    $protector->exit_error("Você não possui essa animação");
}

$n_quant = $animacao->fetch_array()["quant"] - 1;

$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod_pers = ? AND cod_skil = ?",
    "ii", array($cod_pers, $cod_skill));

if ($skill->count()) {
    $connection->run("UPDATE tb_personagens_skil SET animacao = ? WHERE cod_pers = ? AND cod_skil = ?",
        "sii", array($effect, $pers, $cod_skil));
} else {
    $connection->run(
        "INSERT INTO tb_personagens_skil SET nome = ?, descricao = ?, icone = ?, editado = 1, cod_pers = ?, cod_skil = ?, animacao = ?",
        "ssiiis", array($habilidade["nome"], $habilidade["descricao"], $habilidade["icone"], $cod_pers, $cod_skill, $effect));
}

if ($n_quant) {
    $connection->run("UPDATE tb_tripulacao_animacoes_skills SET quant = quant - 1 WHERE tripulacao_id = ? AND effect = ?",
        "is", array($userDetails->tripulacao["id"], $effect));
} else {
    $connection->run("DELETE FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
        "is", array($userDetails->tripulacao["id"], $effect));
}

echo "-Você mudou a animação da sua habilidade!";
