<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_bot();

if ($userDetails->combate_bot["vez"] != 1) {
    $protector->exit_error("Ainda não é a sua vez");
}

$cod_skil = $protector->post_number_or_exit("cod_skil");
$tipo_skil = $protector->post_number_or_exit("tipo");
$cod_pers = $protector->post_number_or_exit("pers");
$quadro = $protector->post_value_or_exit("quadro");

$combate = new Combate($connection, $userDetails, $protector);

$quadros = $combate->extract_quadros($quadro);

if (!$combate->is_area_valida($quadros)) {
    $protector->exit_error("Área inválida");
}

$personagem_combate = $combate->load_personagem_combate($cod_pers);

$habilidade = $combate->check_and_load_habilidade($personagem_combate, $cod_skil, $tipo_skil, $quadros);

$tabuleiro = $combate->load_tabuleiro($userDetails->tripulacao["id"], null, $userDetails->combate_bot["id"]);

if (!$combate->is_quadro_ataque_valido($personagem_combate, $quadros[0], $habilidade, $tabuleiro)) {
    $protector->exit_error("Quadro inválido");
}

$combate->pre_turn($personagem_combate, $habilidade, $cod_skil, $tipo_skil);

//relatorio
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

//ataca os outros quadros
foreach ($quadros as $x => $quadro) {
    if (!isset($tabuleiro[$quadro["x"]]) || !isset($tabuleiro[$quadro["x"]][$quadro["y"]])) {
        $relatorio_afetado[$x]["acerto"] = "0";
    } else {
        $relatorio_afetado[$x] = $combate->ataca_quadro($personagem_combate, $habilidade, $tipo_skil, $tabuleiro[$quadro["x"]][$quadro["y"]]);
    }
    $relatorio_afetado[$x]["quadro"] = $quadro["x"] . "_" . $quadro["y"];
}

$relatorio["afetados"] = $relatorio_afetado;
$relatorio["id"] = atual_segundo();

$combate->pos_turn();

$combate->logger->registra_turno_combate_bot($relatorio);
