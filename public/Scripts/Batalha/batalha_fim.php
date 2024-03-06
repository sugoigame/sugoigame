<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";
include "../../Includes/verifica_combate.php";

$protector->need_tripulacao();

if (! $userDetails->combate_pve && ! $userDetails->combate_pvp && ! $userDetails->combate_bot) {
    echo ("%oceano");
    exit();
}

$combate_logger = new CombateLogger($connection, $userDetails);

$venceu = false;
if ($userDetails->combate_pve) {

    $personagens_in_combate = get_personagens_combate();

    $venceu = check_vitoria_npc($personagens_in_combate);

    if (! $venceu) {
        zera_hp_tripulantes($personagens_in_combate);
    } else {
        $rdms = DataLoader::load("rdm");

        $rdm = $rdms[$userDetails->combate_pve["zona"]];

        $xp = $rdm["xp"];

        if (! $userDetails->combate_pve["boss_id"]) {
            add_rdm_loot($rdm);

            increment_registro_pve();

            $xp *= atualiza_missao_alianca_and_calc_xp_bonus();

            atualiza_missao_caca();

            registra_chefe_ilha();

            registra_chefe_especial();

            atualiza_mini_eventos($rdm);
        }

        atualiza_hp_tripulantes($personagens_in_combate);

        $userDetails->xp_for_all($xp);
    }

    registra_log_npc();

    remove_batalha_npc();

} elseif ($userDetails->combate_pvp) {

    $duracao = get_batalha_duracao($userDetails->combate_pvp["combate"]);

    $personagens_combate_1 = get_personagens_combate($userDetails->tripulacoes_pvp["1"]["id"]);
    $personagens_combate_2 = get_personagens_combate($userDetails->tripulacoes_pvp["2"]["id"]);

    $vencedor_ref = check_vitoria_pvp($personagens_combate_1, $personagens_combate_2);
    $vencedor = $userDetails->tripulacoes_pvp[$vencedor_ref];
    $perdedor = $userDetails->tripulacoes_pvp[$vencedor_ref == "1" ? "2" : "1"];

    $personagens_vencedor = $vencedor_ref == "1" ? $personagens_combate_1 : $personagens_combate_2;
    $personagens_perdedor = $vencedor_ref == "1" ? $personagens_combate_2 : $personagens_combate_1;

    $venceu = $vencedor["id"] == $userDetails->tripulacao["id"];

    $vale_premio_participacao = pvp_vale_premio_participacao($duracao, $personagens_vencedor, $personagens_perdedor);

    $reputacao = [
        "vencedor_rep" => 0,
        "vencedor_rep_mensal" => 0,
        "perdedor_rep" => 0,
        "perdedor_rep_mensal" => 0
    ];

    if (pvp_tipo_deve_atualizar_hp($userDetails->combate_pvp["tipo"])) {
        zera_hp_tripulantes($personagens_perdedor);
        atualiza_hp_tripulantes($personagens_vencedor);
    }

    if (is_pvp_competitivo_valido($userDetails->combate_pvp["tipo"], $vencedor, $perdedor)) {
        // reputacao agora equivale a poneglyphs
        $reputacao = atualiza_reputacao($vencedor, $perdedor);

        atualiza_pontos_coliseu($vencedor, $perdedor, $vale_premio_participacao);

        atualiza_battle_points($vendord, $perdedor, $personagens_vencedor, $personagens_perdedor);

        atualiza_xp_pvp($vendord, $perdedor, $personagens_vencedor, $personagens_perdedor);

        atualiza_controle_ilha_pvp($vencedor);

        envia_noticia_pvp($vencedor, $perdedor);
    }

    registra_log_pvp($vencedor, $perdedor, $reputacao);

    finaliza_apostas($vencedor);

    remove_batalha_pvp($vencedor, $perdedor);

} elseif ($userDetails->combate_bot) {

    $personagens_in_combate = get_personagens_combate();
    $venceu = check_vitoria_bot($personagens_in_combate);

    if (! $venceu) {
        zera_hp_tripulantes($personagens_in_combate);
    } else {
        atualiza_hp_tripulantes($personagens_in_combate);
        $userDetails->xp_for_all(150);

        rouba_carga_mercador();

        atualiza_disputa_ilha();

        atualiza_incursao();

        atualiza_missao();
    }

    remove_missao();

    remove_batalha_bot();
}

if ($venceu) {
    if ($userDetails->combate_pve && isset($rdm['haki'])) {
        echo ("%haki");
    } elseif ($userDetails->combate_bot && $userDetails->combate_bot["incursao"]) {
        echo ("%incursao");
    } elseif ($userDetails->combate_bot && $userDetails->combate_bot["disputa_ilha"]) {
        echo ("%politicaIlha");
    } elseif ($userDetails->combate_pve && $userDetails->combate_pve["chefe_especial"]) {
        echo ("%eventoChefesIlhas");
    } elseif ($userDetails->missao) {
        echo "%missoes";
    } else {
        echo ("%oceano");
    }
} else {
    echo ("%oceano");
}


