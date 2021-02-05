<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->post_tripulante_or_exit("cod");
$cod_skill = $protector->post_number_or_exit("codskill");
$tipo_skill = $protector->post_enum_or_exit("tiposkill", array(TIPO_SKILL_ATAQUE_AKUMA, TIPO_SKILL_BUFF_AKUMA, TIPO_SKILL_PASSIVA_AKUMA));

$skill = $connection->run("SELECT * FROM tb_personagens_skil WHERE cod = ? AND cod_skil = ? AND tipo = ?",
    "iii", array($pers["cod"], $cod_skill, $tipo_skill));

if (!$skill->count()) {
    $protector->exit_error("Habilidade inválida");
}

$skill = $skill->fetch_array();

if ($tipo_skill == TIPO_SKILL_ATAQUE_AKUMA) {
    $area = $protector->post_number_or_exit("area");
    $alcance = $protector->post_number_or_exit("alcance");

    $skill_info = $connection->run("SELECT * FROM tb_akuma_skil_atk WHERE cod_skil = ?",
        "i", array($skill["cod_skil"]))->fetch_array();

    $dano = floor($skill_info["lvl"] / 5) * 9 + 10;
    if ($alcance != 1) {
        $dano = round(($dano / $alcance) + ($dano * 0.4));
    }
    if ($area != 1) {
        $dano = round($dano * (1 / $area));
    }

    $connection->run("UPDATE tb_akuma_skil_atk SET dano = ?, alcance = ?, area = ? WHERE cod_skil = ?",
        "iiii", array($dano, $alcance, $area, $cod_skill));
} else if ($tipo_skill == TIPO_SKILL_BUFF_AKUMA) {
    $atributo = $protector->post_number_or_exit("atributo");
    $efeito_negativo = $protector->post_number_or_exit("negativo");
    $area = $protector->post_number_or_exit("area");
    $alcance = $protector->post_number_or_exit("alcance");

    $skill_info = $connection->run("SELECT * FROM tb_akuma_skil_buff WHERE cod_skil = ?",
        "i", array($skill["cod_skil"]))->fetch_array();

    $bonus = floor($skill_info["lvl"] / 5) * 5 + 50;

    if ($alcance != 1) {
        $bonus = round(($bonus / $alcance) + ($bonus * 0.4));
    }
    if ($area != 1) {
        $bonus = round($bonus * (1 / $area));
    }

    if ($efeito_negativo) {
        $bonus *= -1;
    }

    $connection->run("UPDATE tb_akuma_skil_buff SET bonus_atr = ?, bonus_atr_qnt = ?, alcance = ?, area = ? WHERE cod_skil = ?",
        "iiiii", array($atributo, $bonus, $alcance, $area, $cod_skill));
} else {
    $atributo = $protector->post_number_or_exit("atributo");

    $connection->run("UPDATE tb_akuma_skil_passiva SET bonus_atr = ? WHERE cod_skil = ?",
        "ii", array($atributo, $cod_skill));
}

$connection->run("UPDATE tb_personagens SET selos_xp = selos_xp - 1 WHERE cod = ?",
    "i", array($pers["cod"]));

echo "Habilidade modificada!";