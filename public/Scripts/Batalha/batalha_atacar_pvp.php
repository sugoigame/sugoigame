<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_pvp();

$combate = new Combate($connection, $userDetails, $protector);

$eu = $combate->get_my_id_index_in_pvp();
if ($userDetails->combate_pvp["vez"] != $eu) {
    $protector->exit_error("Ainda não é a sua vez");
}

$cod_skil = $protector->post_number_or_exit("cod_skil");
$tipo_skil = $protector->post_number_or_exit("tipo");
$cod_pers = $protector->post_number_or_exit("pers");
$quadro = $protector->post_value_or_exit("quadro");

$quadros = $combate->extract_quadros($quadro);

if (!$combate->is_area_valida($quadros)) {
    $protector->exit_error("Área inválida");
}

$personagem_combate = $combate->load_personagem_combate($cod_pers);

$habilidade = $combate->check_and_load_habilidade($personagem_combate, $cod_skil, $tipo_skil, $quadros);

$tabuleiro = $combate->load_tabuleiro($userDetails->combate_pvp["id_1"], $userDetails->combate_pvp["id_2"]);

if (!$combate->is_quadro_ataque_valido($personagem_combate, $quadros[0], $habilidade, $tabuleiro)) {
    $protector->exit_error("Quadro inválido");
}

if ($tipo_skil == 10 && !$personagem_combate["medico_usado"]) {
    $medicos_usados = $connection->run("SELECT count(*) AS total FROM tb_combate_personagens WHERE id = ? AND medico_usado = 1",
        "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];

    if ($medicos_usados >= 3) {
        $protector->exit_error("Você só pode usar 3 médicos diferentes em uma única batalha PvP");
    }
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

//ataca os outros quadros
$relatorio_afetado = array();
foreach ($quadros as $x => $quadro) {
    if (!isset($tabuleiro[$quadro["x"]]) || !isset($tabuleiro[$quadro["x"]][$quadro["y"]])) {
        $relatorio_afetado[$x]["acerto"] = "0";
    } else {
        $relatorio_afetado[$x] = $combate->ataca_quadro($personagem_combate, $habilidade, $tipo_skil, $tabuleiro[$quadro["x"]][$quadro["y"]]);
    }
    $relatorio_afetado[$x]["quadro"] = $quadro["x"] . "_" . $quadro["y"];
}

$combate->pos_turn();

$vivos_1 = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0"
    , "i", $userDetails->combate_pvp["id_1"])->fetch_array()["total"];
$total_1 = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ?"
    , "i", $userDetails->combate_pvp["id_1"])->fetch_array()["total"];

$vivos_2 = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ? AND hp > 0"
    , "i", $userDetails->combate_pvp["id_2"])->fetch_array()["total"];
$total_2 = $connection->run("SELECT count(cod) AS total FROM tb_combate_personagens WHERE id = ?"
    , "i", $userDetails->combate_pvp["id_2"])->fetch_array()["total"];

if ($vivos_1 < floor($total_1 / 2) || $vivos_2 < floor($total_2 / 2)) {
    $connection->run("UPDATE tb_combate SET fim_apostas = 1 WHERE combate = ?",
        "i", $userDetails->combate_pvp["combate"]);
}

$relatorio["id"] = atual_segundo();
if (!isset($relatorio["tipo"])) {
    $relatorio["tipo"] = 0;
}
$relatorio["afetados"] = $relatorio_afetado;

if ($tipo_skil == 10 && !$personagem_combate["medico_usado"]) {
    $connection->run("UPDATE tb_combate_personagens SET medico_usado = 1 WHERE cod = ?",
        "i", array($cod_pers));
}

$combate->logger->registra_turno_combate_pvp($relatorio);
