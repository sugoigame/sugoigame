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


$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod_pers = ? AND cod_skil = ?",
    "ii", array($cod_pers, $cod_skill));

if (! $skill->count()) {
    $protector->exit_error("Habilidade não encontrada");
}

$skill = $skill->fetch_array();

$tipo = $protector->post_enum_or_exit("tipo_pagamento", array("gold"));

if ($tipo == "gold") {
    $protector->need_gold(PRECO_GOLD_CUSTOMIZAR_SKILL);
}
$connection->run(
    "UPDATE tb_personagens_skil SET nome = ?, descricao = ?, icone = ?, editado = 1
      WHERE cod_pers = ? AND cod_skil = ?",
    "ssiii", array($nome, $descricao, $icon, $cod_pers, $cod_skill));


if ($skill["editado"]) {
    $tipo = $protector->post_enum_or_exit("tipo_pagamento", array("gold"));

    if ($tipo == "gold") {
        $userDetails->reduz_gold(PRECO_GOLD_CUSTOMIZAR_SKILL, "customizar_skill");
    }
}

echo "-Habilidade modificada!";
