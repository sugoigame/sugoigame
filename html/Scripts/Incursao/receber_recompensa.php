<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$nivel_id = $protector->get_number_or_exit("nivel");

$incursoes = DataLoader::load("incursoes");

$incursao = $incursoes[$userDetails->ilha["ilha"]];

if (!isset($incursao["niveis"][$nivel_id])) {
    $protector->exit_error("nivel invalido");
}

$nivel = $incursao["niveis"][$nivel_id];

$progresso = $connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ? AND ilha = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

if (!$progresso->count()) {
    $protector->exit_error("Voce nao pode receber essa recompensa ainda");
}

$progresso = $progresso->fetch_array();

$adversarios_ids = array_keys($nivel);

$adversario_max = $adversarios_ids[count($adversarios_ids) - 1];

if ($progresso["progresso"] <= $adversario_max) {
    $protector->exit_error("Voce ainda nao concluiu a incursao");
}

$recebida = $connection->run("SELECT * FROM tb_incursao_recompensa_recebida WHERE tripulacao_id = ? AND ilha = ? AND nivel = ?",
    "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $nivel_id))->count();

if ($recebida) {
    $protector->exit_error("Voce já recebeu essa recompensa");
}

$recompensas = $nivel["recompensas"];

if (!$userDetails->can_add_item(count($recompensas))) {
    $protector->exit_error("Você precisa de " . count($recompensas) . " espaço(s) vazio(s) no seu inventário para receber a recompensa");
}

foreach ($recompensas as $recompensa) {
    recebe_recompensa($recompensa);
}

$userDetails->xp_for_all(1000);

$connection->run("INSERT INTO tb_incursao_recompensa_recebida (tripulacao_id, ilha, nivel) VALUE (?,?,?)",
    "iii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"], $nivel_id));

echo "Você recebeu sua recompensa!";