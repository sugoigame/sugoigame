<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();
$protector->need_tripulacao_alive();

$cod = $protector->get_number_or_exit("cod");
$tipo = $protector->get_alphanumeric_or_exit("tipo");

if ($tipo != TIPO_KARMA_BOM && $tipo != TIPO_KARMA_MAU) {
    $protector->exit_error("Um dado inválido foi informado.");
}

if ($userDetails->tripulacao["tempo_missao"] > atual_segundo()) {
    $protector->exit_error("Você ainda não pode iniciar a missão");
}

$result = $connection->run("SELECT * FROM tb_ilha_missoes WHERE ilha = ? AND cod_missao = ?",
    "ii", array($userDetails->ilha["ilha"], $cod));

if (!$result->count()) {
    $protector->exit_error("Missao nao encontrada");
}

$concluida = $connection->run("SELECT count(*) AS total FROM tb_missoes_concluidas WHERE id = ? AND cod_missao = ?",
    "ii", array($userDetails->tripulacao["id"], $cod))->fetch_array()["total"];
if ($concluida) {
    $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ?",
        "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

    $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0;

    if ($total_concluido_hoje >= MAX_MISSOES_ILHA_DIA) {
        $protector->exit_error("Você já atingiu o limite de missões repetidas nessa ilha por hoje.<br/> Viaje para uma nova ilha ou volte aqui amanhã.");
    }
}

$missao = DataLoader::load("missoes")[$cod];
$inimigos = DataLoader::load("inimigos_missao");

foreach ($missao["inimigos"] as $index => $inimigo_id) {
    $missao["inimigos"][$index] = $inimigos[$inimigo_id];
}

if ($userDetails->capitao['lvl'] < $missao["requisito_lvl"]) {
    $protector->exit_error("Seu capitão não tem o nível mínimo para iniciar essa missão.");
}

$espera = array();
$buffs = array();

function set_espera($equipe, $pers, $habilidade) {
    global $espera;
    if ($habilidade["espera"]) {
        $espera[] = array(
            "equipe" => $equipe,
            "cod" => $pers["cod"],
            "tipo" => $habilidade["tipo"],
            "cod_skil" => $habilidade["cod_skil"],
            "turnos" => $habilidade["espera"]
        );
    }
}

function set_buff($equipe, &$pers, $habilidade) {
    global $buffs;
    if ($habilidade["espera"]) {
        $buffs[] = array(
            "equipe" => $equipe,
            "pers" => &$pers,
            "bonus_atr" => $habilidade["bonus_atr"],
            "bonus_atr_qnt" => $habilidade["bonus_atr_qnt"],
            "turnos" => $habilidade["espera"]
        );
    }
}

function in_espera($equipe, $pers, $habilidade) {
    global $espera;

    foreach ($espera as $skill) {
        if ($skill["equipe"] == $equipe
            && $skill["cod"] == $pers["cod"]
            && $skill["tipo"] == $habilidade["tipo"]
            && $skill["cod_skil"] == $habilidade["cod_skil"]
        ) {
            return true;
        }
    }
    return false;
}

function clear_esperas() {
    global $espera;

    $espera_copy = $espera;

    $espera = array();

    foreach ($espera_copy as $skill) {
        if ($skill["turnos"]) {
            $skill["turnos"]--;
            $espera[] = $skill;
        }
    }
}

function clear_buffs() {
    global $buffs;

    $buffs_copy = $buffs;

    $buffs = array();

    foreach ($buffs_copy as $skill) {
        if ($skill["turnos"]) {
            $skill["turnos"]--;
            $buffs[] = $skill;
        } else {
            $skill["pers"][nome_atributo_tabela($skill["bonus_atr"])] -= $skill["bonus_atr_qnt"];
        }
    }
}

function nome_faccao($item) {
    global $userDetails;
    global $tipo;
    $faccao = $userDetails->tripulacao["faccao"];
    return isset($item["nome_" . $tipo . "_" . $faccao]) ? $item["nome_" . $tipo . "_" . $faccao] : $item["nome"];
}

function attack($atacante, & $alvo, $habilidade) {
    $golpe = calc_dano($atacante, $alvo, $habilidade["dano"] * 10);

    $alvo["hp"] = max(0, $alvo["hp"] - $golpe["dano"]);

    $golpe["alvo_nome"] = nome_faccao($alvo);
    $golpe["alvo_hp"] = $alvo["hp"];
    $golpe["alvo_hp_max"] = $alvo["hp_max"];

    if (isset($alvo["img"])) {
        $golpe["alvo_img"] = $alvo["img"];
        $golpe["alvo_skin_r"] = $alvo["skin_r"];
    }

    if ($alvo["hp"] <= 0) {
        $golpe["derrotado"] = true;
    }

    return $golpe;
}

function apply_buff(&$alvo_pers, $habilidade) {
    $alvo_pers[nome_atributo_tabela($habilidade["bonus_atr"])] += $habilidade["bonus_atr_qnt"];

    $golpe = array();
    $golpe["bonus_atr"] = $habilidade["bonus_atr"];
    $golpe["bonus_atr_qnt"] = $habilidade["bonus_atr_qnt"];

    $golpe["alvo_nome"] = nome_faccao($alvo_pers);
    $golpe["alvo_hp"] = $alvo_pers["hp"];
    $golpe["alvo_hp_max"] = $alvo_pers["hp_max"];
    if (isset($alvo_pers["img"])) {
        $golpe["alvo_img"] = $alvo_pers["img"];
        $golpe["alvo_skin_r"] = $alvo_pers["skin_r"];
    }

    return $golpe;
}

function is_all_defeated($personagens) {
    foreach ($personagens as $pers) {
        if ($pers["hp"] > 0) {
            return false;
        }
    }
    return true;
}

function processa_golpe($equipe, &$atacante, &$inimigos, $habilidade, $alvos_buff, &$personagens_buff) {
    $golpe = array();

    if ($atacante["mp"] < $habilidade["consumo"]) {
        $golpe["sem_mp"] = true;
    } else if (in_espera($equipe, $atacante, $habilidade)) {
        $golpe["in_espera"] = true;
    } else {
        $atacante["mp"] -= $habilidade["consumo"];
        set_espera($equipe, $atacante, $habilidade);
        if (nome_tipo_skill($habilidade) == "Ataque") {
            for ($area = 0; $area < $habilidade["area"]; $area++) {
                if (!is_all_defeated($inimigos)) {
                    do {
                        $alvo = &$inimigos[array_rand($inimigos)];
                    } while ($alvo["hp"] <= 0);

                    $golpe["alvo"][$area] = attack($atacante, $alvo, $habilidade);
                }
            }
        } else {
            foreach ($alvos_buff as $index => $alvo) {
                $alvo_pers = &$personagens_buff[$alvo];
                if ($alvo_pers["hp"] > 0) {
                    set_buff($equipe, $alvo_pers, $habilidade);
                    $golpe["alvo"][$index] = apply_buff($alvo_pers, $habilidade);
                }
            }
        }
    }

    $golpe["tipo"] = $habilidade["tipo"];
    $golpe["personagem_nome"] = nome_faccao($atacante);
    $golpe["personagem_mp"] = $atacante["mp"];
    $golpe["personagem_mp_max"] = $atacante["mp_max"];
    $golpe["skill_nome"] = nome_faccao($habilidade);

    if (isset($atacante["img"])) {
        $golpe["personagem_img"] = $atacante["img"];
        $golpe["personagem_skin_r"] = $atacante["skin_r"];
    }

    return $golpe;
}

$rotacao = $userDetails->tripulacao["missao_rotation"]
    ? json_decode($userDetails->tripulacao["missao_rotation"], true)
    : array(
        0 => array(
            "cod" => $userDetails->capitao["cod"],
            "cod_skil" => COD_SKILL_SOCO,
            "tipo" => TIPO_SKILL_ATAQUE_CLASSE,
            "area" => 1
        )
    );
$personagens_usados = array();

$personagens_usados = array();
foreach ($rotacao as $pers) {
    $personagens_usados[$pers["cod"]] = true;
}

if (count($personagens_usados) < count($userDetails->personagens)) {
    $protector->exit_error("A sua ESTRATÉGIA DE COMBATE não está completa. Você precisa que todos seus tripulantes executem ao menos 1 ataque.");
}


$personagens = array();
$habilidades = array();

foreach ($userDetails->personagens as $pers) {
    $personagens[$pers["cod"]] = aplica_bonus($pers);
    $habs = get_many_results_joined_mapped_by_type("tb_personagens_skil", "cod_skil", "tipo", array(
        array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_ATAQUE_CLASSE),
        array("nome" => "tb_skil_atk", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_ATAQUE_PROFISSAO),
        array("nome" => "tb_akuma_skil_atk", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_ATAQUE_AKUMA),
        array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_BUFF_CLASSE),
        array("nome" => "tb_skil_buff", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_BUFF_PROFISSAO),
        array("nome" => "tb_akuma_skil_buff", "coluna" => "cod_skil", "tipo" => TIPO_SKILL_BUFF_AKUMA)
    ), "WHERE origem.cod = ?", "i", $pers["cod"]);

    foreach ($habs as $hab) {
        $habilidades[$pers["cod"]][$hab["tipo"]][$hab["cod_skil"]] = $hab;
    }
}
function shuffle_assoc(&$array) {
    $keys = array_keys($array);

    shuffle($keys);

    $new = array();
    foreach ($keys as $key) {
        $new[$key] = $array[$key];
    }

    $array = $new;

    return true;
}

shuffle_assoc($personagens);

$log = array();
for ($i = 0, $j = 0, $turno = 0; $turno < $missao["turnos"] && !is_all_defeated($personagens) && !is_all_defeated($missao["inimigos"]); $i++, $j++) {
    if ($i >= count($rotacao)) {
        $i = -1;
        $j -= 1;
        continue;
    }
    if (!isset($rotacao[$i])) {
        $j -= 1;
        continue;
    }
    if ($j >= count($missao["inimigos"])) {
        $i -= 1;
        $j = -1;
        continue;
    }

    $atacante_jogador = &$personagens[$rotacao[$i]["cod"]];
    $atacante_inimigo = &$missao["inimigos"][$j];

    if ($atacante_jogador["hp"] <= 0) {
        $j--;
        continue;
    }
    if ($atacante_inimigo["hp"] <= 0) {
        $i--;
        continue;
    }

    $habilidade_jogador = $habilidades[$rotacao[$i]["cod"]][$rotacao[$i]["tipo"]][$rotacao[$i]["cod_skil"]];
    $habilidade_inimigo = $atacante_inimigo["habilidades"][rand(0, count($atacante_inimigo["habilidades"]) - 1)];

    $golpe_jogador = processa_golpe(
        "jogador",
        $atacante_jogador,
        $missao["inimigos"],
        $habilidade_jogador,
        isset($rotacao[$i]["alvo"]) ? $rotacao[$i]["alvo"] : array(),
        $personagens
    );

    $golpe_inimigo = processa_golpe(
        "inimigo",
        $atacante_inimigo,
        $personagens,
        $habilidade_inimigo,
        isset($habilidade_inimigo["alvo"]) ? $habilidade_inimigo["alvo"] : array(),
        $missao["inimigos"]
    );

    $log[] = array($golpe_jogador, $golpe_inimigo);

    clear_esperas();
    clear_buffs();
    $turno++;
}

$hp_final = array();
foreach ($personagens as $pers) {
    $hp_final[$pers["cod"]] = $pers["hp"];
}

$mp_final = array();
foreach ($personagens as $pers) {
    $mp_final[$pers["cod"]] = $pers["mp"];
}

$venceu = is_all_defeated($missao["inimigos"]) ? 1 : 0;

$connection->run(
    "INSERT INTO tb_missoes_iniciadas (id, cod_missao, fim, log, venceu, hp_final, mp_final, tipo_karma) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
    "iiisisss", array(
        $userDetails->tripulacao["id"],
        $cod,
        atual_segundo(),
        json_encode($log, JSON_NUMERIC_CHECK),
        $venceu,
        json_encode($hp_final, JSON_NUMERIC_CHECK),
        json_encode($mp_final, JSON_NUMERIC_CHECK),
        $tipo
    )
);
$fim = atual_segundo() + $missao["duracao"];
$connection->run("UPDATE tb_usuarios SET tempo_missao = ? WHERE id = ?",
    "ii", array($fim, $userDetails->tripulacao["id"]));

echo("-Missão iniciada");