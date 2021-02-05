<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_pve();

$cod_skil = $protector->post_number_or_exit("cod_skil");
$tipo_skil = $protector->post_number_or_exit("tipo");
$cod_pers = $protector->post_number_or_exit("pers");
$quadro = $protector->post_value_or_exit("quadro");

$combate = new Combate($connection, $userDetails, $protector);

$quadros = $combate->extract_quadros($quadro);

$personagem_combate = $combate->load_personagem_combate($cod_pers);

$habilidade = $combate->check_and_load_habilidade($personagem_combate, $cod_skil, $tipo_skil, $quadros);

$tabuleiro = $combate->load_tabuleiro($userDetails->tripulacao["id"]);

//batalha NPC
if (!$userDetails->combate_pve["hp_npc"]) {
    $protector->exit_error("Seu adversário já foi derrotado");
}

$combate->pre_turn($personagem_combate, $habilidade, $cod_skil, $tipo_skil);

$relatorio = array();

$npc_stats = $combate->get_npc_status();

$combate->aplica_buffs_npc($npc_stats);

if ($tipo_skil != 10) {
    $relatorio["tipo"] = 1;
} else {
    $relatorio["tipo"] = 2;
}

$relatorio["nome"] = $personagem_combate["nome"];
$relatorio["cod"] = $personagem_combate["cod"];
$relatorio["img"] = $personagem_combate["img"];
$relatorio["skin_r"] = $personagem_combate["skin_r"];
$relatorio["nome_skil"] = $habilidade["nome"];
$relatorio["img_skil"] = $habilidade["icon"];
$relatorio["effect"] = $habilidade["effect"];
$relatorio["descricao_skil"] = $habilidade["descricao"];
$relatorio_afetado = array();

foreach ($quadros as $x => $quadro) {
    if ($quadro["npc"]) {
        $relatorio_afetado[$x] = $combate->ataca_npc($personagem_combate, $habilidade, $tipo_skil, $npc_stats);
    } else if (isset($tabuleiro[$quadro["x"]]) && isset($tabuleiro[$quadro["x"]][$quadro["y"]])) {
        $relatorio_afetado[$x] = $combate->ataca_quadro($personagem_combate, $habilidade, $tipo_skil, $tabuleiro[$quadro["x"]][$quadro["y"]]);
    }
}

$relatorio["afetados"] = $relatorio_afetado;
$relatorio["id"] = atual_segundo();

$combate->logger->registra_turno_combate_pve($relatorio);

//verifica se havera turno do npc
if ($userDetails->combate_pve["hp_npc"]) {
    $combate->processa_turno_npc($tabuleiro);
}
