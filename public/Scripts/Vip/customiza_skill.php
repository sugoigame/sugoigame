<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$cod_pers = $protector->post_number_or_exit("codpers");
$cod_skill = $protector->post_number_or_exit("codskil");
$tipo_skill = $protector->post_number_or_exit("tiposkil");
$icon = $protector->post_number_or_exit("img");
$nome = $protector->post_value_or_exit("nome");
$descricao = $protector->post_value_or_exit("descricao");

$pers = $userDetails->get_pers_by_cod($cod_pers);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}


$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "iii", array($cod_pers, $cod_skill, $tipo_skill));

if (!$skill->count()) {
    $protector->exit_error("Habilidade não encontrada");
}

$skill = $skill->fetch_array();

if ($skill["editado"]) {
    $tipo = $protector->post_enum_or_exit("tipo_pagamento", array("gold", "dobrao"));

    if ($tipo == "gold") {
        $protector->need_gold(PRECO_GOLD_CUSTOMIZAR_SKILL);
    } else {
        $protector->need_dobroes(PRECO_DOBRAO_CUSTOMIZAR_SKILL);
    }
}

$connection->run(
    "UPDATE tb_personagens_skil SET nome = ?, descricao = ?, icon = ?, editado = 1 
      WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "ssiiii", array($nome, $descricao, $icon, $cod_pers, $cod_skill, $tipo_skill));


if ($skill["editado"]) {
    $tipo = $protector->post_enum_or_exit("tipo_pagamento", array("gold", "dobrao"));

    if ($tipo == "gold") {
        $userDetails->reduz_gold(PRECO_GOLD_CUSTOMIZAR_SKILL, "customizar_skill");
    } else {
        $userDetails->reduz_dobrao(PRECO_DOBRAO_CUSTOMIZAR_SKILL, "customizar_skill");
    }
}

echo "-Habilidade modificada!";