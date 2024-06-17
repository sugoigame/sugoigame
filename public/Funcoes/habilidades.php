<?php

function get_todas_habilidades()
{
    $habilidades = DataLoader::load("habilidades");
    $todas = array_merge($habilidades["padrao"], $habilidades["haki"]);

    foreach ($habilidades["classes"] as $classe) {
        $todas = array_merge($todas, $classe["habilidades"]);
    }
    return habilidades_default_values($todas);
}


function get_todas_habilidades_pers($pers)
{
    global $connection;
    global $COD_HAOSHOKU_LVL;
    $habilidades_db = $connection->run(
        "SELECT * FROM tb_personagens_skil WHERE cod_pers = ?",
        "i", $pers["cod"]
    )->fetch_all_array();

    $habilidades = DataLoader::load("habilidades");

    $habilidades_pers = $habilidades["padrao"];

    if ($pers["classe"]) {
        $habilidades_pers = array_merge($habilidades_pers, $habilidades["classes"][$pers["classe"]]["habilidades"]);
    }
    if ($pers["haki_hdr"]) {
        $habilidades_pers[] = array_find($habilidades["haki"], ["cod" => $COD_HAOSHOKU_LVL[$pers["haki_hdr"]]]);
    }

    foreach ($habilidades_pers as $key => $habilidade) {
        $habilidades_pers[$key] = array_merge(habilidade_default_values($habilidade), array_find($habilidades_db, ["cod_skil" => $habilidade["cod"]]) ?: []);
    }

    usort($habilidades_pers, function ($a, $b) {
        return $a["vontade"] - $b["vontade"];
    });

    return $habilidades_pers;
}

function get_habilidade_by_cod($cod)
{
    return array_find(get_todas_habilidades(), ["cod" => $cod]);
}

function habilidades_default_values($habilidades)
{
    foreach ($habilidades as $key => $habilidade) {
        $habilidades[$key] = habilidade_default_values($habilidade);
    }
    return $habilidades;
}
function habilidade_default_values($habilidade)
{
    $habilidade["icone"] = $habilidade["icone"] ?: 1;
    $habilidade["animacao"] = $habilidade["animacao"] ?: "Atingir fisicamente";
    $habilidade["dano"] = $habilidade["dano"] ?: 1;
    $habilidade["alcance"] = $habilidade["alcance"] ?: 1;
    $habilidade["area"] = $habilidade["area"] ?: 1;
    $habilidade["vontade"] = $habilidade["vontade"] ?: 1;
    $habilidade["recarga"] = $habilidade["recarga"] ?: 0;
    $habilidade["recarga_universal"] = $habilidade["recarga_universal"] ?: false;
    $habilidade["requisito_lvl"] = $habilidade["requisito_lvl"] ?: 1;

    if (isset($habilidade["efeitos"])) {
        if (isset($habilidade["efeitos"]["pre_ataque"])) {
            foreach ($habilidade["efeitos"]["pre_ataque"] as $index => $efeito) {
                $habilidade["efeitos"]["pre_ataque"][$index] = efeito_default_values($efeito);
            }
        }
        if (isset($habilidade["efeitos"]["acerto"])) {
            foreach ($habilidade["efeitos"]["acerto"] as $index => $efeito) {
                $habilidade["efeitos"]["acerto"][$index] = efeito_default_values($efeito, TIPO_ALVO_EFEITO_ALVO);
            }
        }
        if (isset($habilidade["efeitos"]["pos_ataque"])) {
            foreach ($habilidade["efeitos"]["pos_ataque"] as $index => $efeito) {
                $habilidade["efeitos"]["pos_ataque"][$index] = efeito_default_values($efeito);
            }
        }
        if (isset($habilidade["efeitos"]["passivos"])) {
            foreach ($habilidade["efeitos"]["passivos"] as $index => $efeito) {
                $habilidade["efeitos"]["passivos"][$index] = efeito_default_values($efeito);
            }
        }
    }
    return $habilidade;
}

function efeito_default_values($efeito, $tipo_alvo_padrao = TIPO_ALVO_EFEITO_ATACANTE)
{

    $efeito["tipo"] = $efeito["tipo"] ?: TIPO_EFEITO_POSITIVO;
    $efeito["tipo_alvo"] = $efeito["tipo_alvo"] ?: $tipo_alvo_padrao;
    $efeito["quant_alvo"] = $efeito["quant_alvo"] ?: 1;

    if (is_efeito_valor_habilidade($efeito["bonus"]["atr"])) {
        $efeito["bonus"]["valor"] = habilidade_default_values($efeito["bonus"]["valor"]);
    }
    return $efeito;
}

function is_efeito_valor_habilidade($atributo)
{
    if ($atributo == ATRIBUTO_ATACANTE_ACERTO_CRITICO) {
        return true;
    }
    return false;
}

function get_skill_table($tipo)
{
    switch ($tipo) {
        case TIPO_SKILL_ATAQUE_CLASSE:
        case TIPO_SKILL_ATAQUE_PROFISSAO:
            return "skil_atk";
        case TIPO_SKILL_BUFF_CLASSE:
        case TIPO_SKILL_BUFF_PROFISSAO:
            return "skil_buff";
        case TIPO_SKILL_ATAQUE_AKUMA:
        case TIPO_SKILL_BUFF_AKUMA:
            return "skil_akuma";
        default:
            return null;
    }
}

function get_habilidade_aleatoria_nivel($lvl)
{
    $habilidades_validas = array_filter(get_todas_habilidades(), function ($habilidade) use ($lvl) {
        return $habilidade['requisito_lvl'] <= $lvl;
    });
    return $habilidades_validas[rand(0, (sizeof($habilidades_validas) - 1))];
}

function is_editavel($skill)
{
    global $COD_HAOSHOKU_LVL;
    return $skill["cod_skil"] != 1 && ! in_array($skill["cod_skil"], $COD_HAOSHOKU_LVL);
}

