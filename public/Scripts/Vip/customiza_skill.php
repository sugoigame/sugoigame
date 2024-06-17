<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod_pers = $protector->post_number_or_exit("codpers");
$cod_skill = $protector->post_number_or_exit("codskil");
$icon = $protector->post_number_or_exit("img");
$nome = $protector->post_value_or_exit("nome");
$descricao = $protector->post_value_or_exit("descricao");

$pers = $userDetails->get_pers_by_cod($cod_pers);

if (! $pers) {
    $protector->exit_error("Personagem inválido");
}

if (! get_habilidade_by_cod($cod_skill)) {
    $protector->exit_error("Habilidade inválida");
}

$protector->need_gold(PRECO_GOLD_CUSTOMIZAR_SKILL);

$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod_pers = ? AND cod_skil = ?",
    "ii", array($cod_pers, $cod_skill));

if ($skill->count()) {
    $connection->run(
        "UPDATE tb_personagens_skil SET nome = ?, descricao = ?, icone = ?, editado = 1
          WHERE cod_pers = ? AND cod_skil = ?",
        "ssiii", array($nome, $descricao, $icon, $cod_pers, $cod_skill));
} else {
    $connection->run(
        "INSERT INTO tb_personagens_skil SET nome = ?, descricao = ?, icone = ?, editado = 1, cod_pers = ?, cod_skil = ?",
        "ssiii", array($nome, $descricao, $icon, $cod_pers, $cod_skill));
}

$userDetails->reduz_gold(PRECO_GOLD_CUSTOMIZAR_SKILL, "customizar_skill");

echo "-Habilidade modificada!";
