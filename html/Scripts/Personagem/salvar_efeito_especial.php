<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers_cod = $protector->post_number_or_exit("pers");
$cod_skill = $protector->post_number_or_exit("skill");
$tipo_skill = $protector->post_number_or_exit("tipo");
$apply_type = $protector->post_number_or_exit("apply_type");
$effect = $protector->post_number_or_exit("effect");
$target = $protector->post_number_or_exit("target");

if (!$pers = $userDetails->get_pers_by_cod($pers_cod)) {
    $protector->exit_error("Personagem invalido");
}

if ($tipo_skill != TIPO_SKILL_BUFF_AKUMA
    && $tipo_skill != TIPO_SKILL_ATAQUE_AKUMA
    && $tipo_skill != TIPO_SKILL_ATAQUE_CLASSE) {
    $protector->exit_error("Tipo de abilidade invalida");
}

$connection->run(
    "UPDATE tb_personagens_skil SET special_effect = ?, special_apply_type = ?, special_target = ? WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "iiiiii", array($effect, $apply_type, $target, $pers_cod, $cod_skill, $tipo_skill)
);

echo "Efeito especial definido!";