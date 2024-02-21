<?php
include "../public/Classes/DataLoader.php";
include "../public/Classes/MapLoader.php";
include "../public/Funcoes/atributos.php";

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

    if ($arquetipo == "Dano") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.2 + 1.2, 2),
            "alcance" => 1,
            "area" => 1,
            "espera" => 0,
            "categoria" => $categoria
        ];
    } elseif ($arquetipo == "Alcance") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.15 + 1, 2),
            "alcance" => 10,
            "area" => 1,
            "espera" => 0,
            "categoria" => $categoria
        ];
    } elseif ($arquetipo == "Area") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.15 + 0.6, 2),
            "alcance" => 1,
            "area" => 4,
            "espera" => 0,
            "categoria" => $categoria
        ];
    } elseif ($arquetipo == "Imobilizar") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.15 + 1, 2),
            "alcance" => 3,
            "area" => 0,
            "espera" => 0,
            "categoria" => $categoria,
            "special_apply_type" => 1,
            "special_effect" => 3,
            "special_target" => 2
        ];
    } elseif ($arquetipo == "Veneno") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.15 + 1, 2),
            "alcance" => 3,
            "area" => 0,
            "espera" => 0,
            "categoria" => $categoria,
            "special_apply_type" => 1,
            "special_effect" => 2,
            "special_target" => 2
        ];
    } elseif ($arquetipo == "Sangramento") {
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "dano" => round(($vontade / 4) * 0.15 + 1, 2),
            "alcance" => 3,
            "area" => 0,
            "espera" => 0,
            "categoria" => $categoria,
            "special_apply_type" => 1,
            "special_effect" => 1,
            "special_target" => 2
        ];
    } elseif (str_starts_with($arquetipo, "AB")) {
        $bonus_atr = get_bonus_atr(str_replace("AB:", "", $arquetipo), $cod_arquetipo, $index);
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "bonus_atr" => $bonus_atr,
            "bonus_atr_qnt" => 100,
            "duracao" => 4,
            "alcance" => 0,
            "area" => 1,
            "espera" => 4,
            "categoria" => $categoria
        ];
    } elseif (str_starts_with($arquetipo, "Buff")) {
        $bonus_atr = get_bonus_atr(str_replace("Buff:", "", $arquetipo), $cod_arquetipo, $index);
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "bonus_atr" => $bonus_atr,
            "bonus_atr_qnt" => 100,
            "duracao" => 3,
            "alcance" => 1,
            "area" => 2,
            "espera" => 4,
            "categoria" => $categoria
        ];
    } elseif (str_starts_with($arquetipo, "Debuff")) {
        $bonus_atr = get_bonus_atr(str_replace("Debuff:", "", $arquetipo), $cod_arquetipo, $index);
        return [
            "cod_skil" => $cod_skill,
            "description" => $description,
            "consumo" => $vontade,
            "requisito_lvl" => $lvl,
            "requisito_classe" => 0,
            "requisito_prof" => 0,
            "bonus_atr" => $bonus_atr,
            "bonus_atr_qnt" => -100,
            "duracao" => 3,
            "alcance" => 1,
            "area" => 2,
            "espera" => 4,
            "categoria" => $categoria
        ];
    } else {
        echo $arquetipo;
        return [];
    }
}

foreach ($akumas as $akuma) {
    $lvls = [10, 20, 30, 40, 50];
    foreach ($lvls as $index => $lvl) {
        $skills[] = createSkill($akuma, $index, $lvl, 1);
        $skills[] = createSkill($akuma, $index, $lvl, 2);
    }
}

MapLoader::save_full_path($skills, "../public/Data/skil_akuma.json");

