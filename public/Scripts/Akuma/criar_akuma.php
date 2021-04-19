<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_missao();

$nome = $protector->post_value_or_exit("nome");
$descricao = $protector->post_value_or_exit("descricao");
$cod_pers = $protector->post_number_or_exit("cod_pers");
$img_akuma = $protector->post_number_or_exit("img_akuma");
$tipoakuma = $protector->post_number_or_exit("tipoakuma");

$pers = $userDetails->get_pers_by_cod($cod_pers);

if (!$pers || $pers["akuma"]) {
    $protector->exit_error("Personagem invalido");
}

if (!$userDetails->get_item($img_akuma, $tipoakuma)) {
    $protector->exit_error("Você não tem essa akuma");
}

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
        $energia[$y] = 100;
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

$userDetails->reduz_item($img_akuma, $tipoakuma, 1, true);

$categoria = rand(1, 100);
if ($categoria < 15) $categoria = 1;
else if ($categoria < 30) $categoria = 2;
else if ($categoria < 45) $categoria = 3;
else if ($categoria < 60) $categoria = 4;
else if ($categoria < 75) $categoria = 5;
else if ($categoria < 90) $categoria = 6;
else if ($categoria < 93) $categoria = 7;
else if ($categoria < 96) $categoria = 8;
else $categoria = 9;


$result = $connection->run("INSERT INTO tb_akuma (cod, nome, descricao, tipo, img, categoria) VALUE (?,?,?,?,?,?)",
    "issiii", array($cod_pers, $nome, $descricao, $tipoakuma, $img_akuma, $categoria));

$cod_akuma = $result->last_id();

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

$connection->run("UPDATE tb_personagens SET akuma='$cod_akuma', maestria = 0 WHERE cod='$cod_pers'");

$connection->run(
    "DELETE ps FROM tb_personagens_skil ps 
    INNER JOIN tb_skil_atk a ON ps.cod_skil = a.cod_skil AND ps.tipo = ? AND a.maestria = 1
    WHERE ps.cod = ?",
    "ii", array(TIPO_SKILL_ATAQUE_CLASSE, $cod_pers)
);
$connection->run(
    "DELETE ps FROM tb_personagens_skil ps 
    INNER JOIN tb_skil_buff a ON ps.cod_skil = a.cod_skil AND ps.tipo = ? AND a.maestria = 1
    WHERE ps.cod = ?",
    "ii", array(TIPO_SKILL_BUFF_CLASSE, $cod_pers)
);
$connection->run(
    "DELETE ps FROM tb_personagens_skil ps 
    INNER JOIN tb_skil_passiva a ON ps.cod_skil = a.cod_skil AND ps.tipo = ? AND a.maestria = 1
    WHERE ps.cod = ?",
    "ii", array(TIPO_SKILL_PASSIVA_CLASSE, $cod_pers)
);

$pers["akuma"] = $cod_akuma;

aprende_todas_habilidades_disponiveis_akuma($pers);

echo "%status&nav=akuma&cod=" . $pers["cod"];
