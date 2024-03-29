<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");
$cod_skill = $protector->get_number_or_exit("codskill");
$tipo_skill = $protector->get_enum_or_exit("tiposkill", [
    TIPO_SKILL_ATAQUE_CLASSE,
    TIPO_SKILL_BUFF_CLASSE,
    TIPO_SKILL_PASSIVA_CLASSE,
    TIPO_SKILL_ATAQUE_AKUMA,
    TIPO_SKILL_BUFF_AKUMA,
    TIPO_SKILL_PASSIVA_AKUMA,
]);

$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?", "iii", [
    $pers["cod"],
    $cod_skill,
    $tipo_skill
]);

if (! $skill->count()) {
    $protector->exit_error("Habilidade inválida");
}

$connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?", "iii", [
    $pers["cod"],
    $cod_skill,
    $tipo_skill
]);

echo ":";
