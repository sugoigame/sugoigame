<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_missao();

$cod_pers = $protector->post_number_or_exit("cod_pers");
$tipo = $protector->post_enum_or_exit("tipo_reset", array("gold", "dobrao"));

if ($tipo == 'gold') {
    $protector->need_gold(PRECO_GOLD_REDEFINE_AKUMA);
} else {
    $protector->need_dobroes(PRECO_DOBRAO_REDEFINE_AKUMA);
}

$pers = $userDetails->get_pers_by_cod($cod_pers);

if (!$pers || !$pers["akuma"]) {
    $protector->exit_error("Personagem invalido");
}

$cod_akuma = $pers["akuma"];

$akuma = $connection->run("SELECT * FROM tb_akuma WHERE cod_akuma = ?",
    "i", array($cod_akuma))->fetch_array();

$tipoakuma = $akuma["tipo"];
$img_akuma = $akuma["img"];

for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    $tipo_skil[$y] = $protector->post_number_or_exit("tipo_skil_" . $y);
    $bonus_value[$y] = $protector->post_number_or_exit("bonus_value_" . $y);
}
for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    if ($tipo_skil[$y] == 1 OR $tipo_skil[$y] == 2) {
        $atr_skil[$y] = $protector->post_number_or_exit("atr_skil_" . $y);
    }
}
for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    if ($tipo_skil[$y] == 1) {
        $add_ou_sub[$y] = $protector->post_enum_or_exit("add_ou_sub_" . $y, array("1", "-1"));
        $duracao[$y] = $protector->post_number_or_exit("duracao_" . $y);
    }
}
for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    if ($tipo_skil[$y] == 0 OR $tipo_skil[$y] == 1) {
        $alcance[$y] = $protector->post_number_or_exit("alcance_" . $y);
        $area[$y] = $protector->post_number_or_exit("area_" . $y);
        $energia[$y] = $protector->post_number_or_exit("energia_" . $y);
        $cooldown[$y] = $protector->post_number_or_exit("cooldown_" . $y);
    }
}

$atk = 10;
$buff = 50;
$pas = 2;

$ok = TRUE;
for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    if ($tipo_skil[$y] == 0) {
        $energia[$y] = $atk;
        $dano[$y] = $atk;
        if ($alcance[$y] != 1) {
            $dano[$y] = round(($dano[$y] / $alcance[$y]) + ($dano[$y] * 0.4));
        }
        if ($area[$y] != 1) {
            $dano[$y] = round($dano[$y] * (1 / $area[$y]));
        }
    } else if ($tipo_skil[$y] == 1) {
        $energia[$y] = $buff + 10;
        $dano[$y] = $buff;
        if ($alcance[$y] != 1) {
            $dano[$y] = round(($dano[$y] / $alcance[$y]) + ($dano[$y] * 0.4));
        }
        if ($area[$y] != 1) {
            $dano[$y] = round($dano[$y] * (1 / $area[$y]));
        }
    } else if ($tipo_skil[$y] == 2) {
        $dano[$y] = $pas;
    }

    $atk += 9;
    $buff += 5;
    $pas += 2;
}

$userDetails->restaura_effects($pers, "(tipo IN (7,8,9))");

$connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND tipo IN (?, ?, ?)",
    "iiii", array($cod_pers, TIPO_SKILL_ATAQUE_AKUMA, TIPO_SKILL_BUFF_AKUMA, TIPO_SKILL_PASSIVA_AKUMA));

$connection->run("DELETE FROM tb_akuma_skil_atk WHERE cod_akuma = ?",
    "i", array($cod_akuma));
$connection->run("DELETE FROM tb_akuma_skil_passiva WHERE cod_akuma = ?",
    "i", array($cod_akuma));
$connection->run("DELETE FROM tb_akuma_skil_buff WHERE cod_akuma = ?",
    "i", array($cod_akuma));

for ($x = 0; $x < 51; $x += 5) {
    if ($x == 0) {
        $y = 1;
    } else {
        $y = $x;
    }
    if ($tipo_skil[$y] == 0) {
        $connection->run("INSERT INTO tb_akuma_skil_atk (cod_akuma, consumo, lvl, dano, alcance, area, espera) 
				VALUES ('$cod_akuma', '$energia[$y]', '$y', '$dano[$y]', '$alcance[$y]', '$area[$y]', '$cooldown[$y]')");
    } else if ($tipo_skil[$y] == 1) {
        $bonus = $dano[$y] * $add_ou_sub[$y];
        $cooldown[$y] = $duracao[$y] * 2;
        $connection->run("INSERT INTO tb_akuma_skil_buff (cod_akuma, consumo, lvl, bonus_atr, bonus_atr_qnt, duracao, alcance, area, espera) 
				VALUES ('$cod_akuma', '$energia[$y]', '$y', '$atr_skil[$y]', '$bonus', '$duracao[$y]', '$alcance[$y]', '$area[$y]', '$cooldown[$y]')");
    } else if ($tipo_skil[$y] == 2) {
        $connection->run("INSERT INTO tb_akuma_skil_passiva (cod_akuma, lvl, bonus_atr, bonus_atr_qnt) 
				VALUES ('$cod_akuma', '$y', '$atr_skil[$y]', '$dano[$y]')");
    }
}

aprende_todas_habilidades_disponiveis_akuma($pers);

if ($tipo == 'gold') {
    $userDetails->reduz_gold(PRECO_GOLD_REDEFINE_AKUMA, "redefinir_akuma");
} else {
    $userDetails->reduz_dobrao(PRECO_DOBRAO_REDEFINE_AKUMA, "redefinir_akuma");
}

echo "%status&nav=akuma&cod=" . $pers["cod"];
