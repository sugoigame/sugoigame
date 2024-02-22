<?php
include "../public/Classes/DataLoader.php";
include "../public/Classes/MapLoader.php";
include "../public/Funcoes/atributos.php";
include "../public/Constantes/skills.php";

$akumas = DataLoader::load('akumas');

$skills = [];

function get_bonus_atr($atrs_list_string, $cod_arquetipo, $index)
{
    $atrs = explode(",", $atrs_list_string);
    if (count($atrs) == 1) {
        $bonus_atr = cod_atributo_tabela($atrs[0]);
    } else {
        if (count($atrs) < 5) {
            for ($i = count($atrs); $i < 5; $i++) {
                $atrs[] = $atrs[$i - 1];
            }
        }
        if ($cod_arquetipo == 0) {
            $bonus_atr = cod_atributo_tabela($atrs[$index]);
        } elseif ($cod_arquetipo == 1) {
            $bonus_atr = cod_atributo_tabela($atrs[$index / 2]);
        } else {
            $bonus_atr = cod_atributo_tabela($atrs[$index == 1 ? 0 : 1]);
        }
    }
    return $bonus_atr;
}

function createSkill($akuma, $index, $lvl, $categoria)
{
    $vontades = [10 => 12, 20 => 20, 30 => 28, 40 => 45, 50 => 50];
    $arquetipos = [10 => [1 => 0, 2 => 1], 20 => [1 => 0, 2 => 2], 30 => [1 => 0, 2 => 1], 40 => [1 => 0, 2 => 2], 50 => [1 => 0, 2 => 1]];

    $vontade = $vontades[$lvl];
    $cod_arquetipo = $arquetipos[$lvl][$categoria];
    $arquetipo = $akuma["arquetipos"][$cod_arquetipo];

    $cod_skill = ($akuma["cod_akuma"] * 100) + $lvl + $categoria;
    $description = $akuma["nome"] . " - " . $lvl . $categoria . " " . $arquetipo;

    $skill = [
        "cod_skil" => $cod_skill,
        "cod_akuma" => $akuma["cod_akuma"],
        "description" => $description,
        "consumo" => $vontade,
        "requisito_lvl" => $lvl,
        "requisito_classe" => 0,
        "requisito_prof" => 0,
        "categoria" => $categoria
    ];

    if ($arquetipo == "Dano") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.2 + 1.2, 2);
        $skill["alcance"] = 1;
        $skill["area"] = 1;
        $skill["espera"] = 0;
        $skill["categoria"] = $categoria;
    } elseif ($arquetipo == "Alcance") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.15 + 1, 2);
        $skill["alcance"] = 10;
        $skill["area"] = 1;
        $skill["espera"] = 0;
    } elseif ($arquetipo == "Area") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.15 + 0.6, 2);
        $skill["alcance"] = 1;
        $skill["area"] = 4;
        $skill["espera"] = 0;
    } elseif ($arquetipo == "Imobilizar") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.15 + 1, 2);
        $skill["alcance"] = 3;
        $skill["area"] = 0;
        $skill["espera"] = 0;
        $skill["special_apply_type"] = 1;
        $skill["special_effect"] = 3;
        $skill["special_target"] = 2;
    } elseif ($arquetipo == "Veneno") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.15 + 1, 2);
        $skill["alcance"] = 3;
        $skill["area"] = 0;
        $skill["espera"] = 0;
        $skill["special_apply_type"] = 1;
        $skill["special_effect"] = 2;
        $skill["special_target"] = 2;
    } elseif ($arquetipo == "Sangramento") {
        $skill["tipo"] = TIPO_SKILL_ATAQUE_AKUMA;
        $skill["dano"] = round(($vontade / 4) * 0.15 + 1, 2);
        $skill["alcance"] = 3;
        $skill["area"] = 0;
        $skill["espera"] = 0;
        $skill["special_apply_type"] = 1;
        $skill["special_effect"] = 1;
        $skill["special_target"] = 2;
    } elseif (str_starts_with($arquetipo, "AB")) {
        $bonus_atr = get_bonus_atr(str_replace("AB:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["tipo"] = TIPO_SKILL_BUFF_AKUMA;
        $skill["bonus_atr"] = $bonus_atr;
        $skill["bonus_atr_qnt"] = 100;
        $skill["duracao"] = 4;
        $skill["alcance"] = 0;
        $skill["area"] = 1;
        $skill["espera"] = 4;
    } elseif (str_starts_with($arquetipo, "Buff")) {
        $bonus_atr = get_bonus_atr(str_replace("Buff:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["tipo"] = TIPO_SKILL_BUFF_AKUMA;
        $skill["bonus_atr"] = $bonus_atr;
        $skill["bonus_atr_qnt"] = 100;
        $skill["duracao"] = 3;
        $skill["alcance"] = 1;
        $skill["area"] = 2;
        $skill["espera"] = 4;
    } elseif (str_starts_with($arquetipo, "Debuff")) {
        $bonus_atr = get_bonus_atr(str_replace("Debuff:", "", $arquetipo), $cod_arquetipo, $index);
        $skill["tipo"] = TIPO_SKILL_BUFF_AKUMA;
        $skill["bonus_atr"] = $bonus_atr;
        $skill["bonus_atr_qnt"] = -100;
        $skill["duracao"] = 3;
        $skill["alcance"] = 1;
        $skill["area"] = 2;
        $skill["espera"] = 4;
    } else {
        echo $arquetipo;
        return [];
    }

    return $skill;
}

foreach ($akumas as $akuma) {
    $lvls = [10, 20, 30, 40, 50];
    foreach ($lvls as $index => $lvl) {
        $skills[] = createSkill($akuma, $index, $lvl, 1);
        $skills[] = createSkill($akuma, $index, $lvl, 2);
    }
}

MapLoader::save_full_path($skills, "../public/Data/skil_akuma.json");

