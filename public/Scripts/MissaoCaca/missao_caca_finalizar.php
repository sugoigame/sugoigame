<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$missao_cod = $userDetails->tripulacao["missao_caca"];

if (!$missao_cod) {
    $protector->exit_error("Você não iniciou uma missão de caça ainda");
}

$missoes = DataLoader::load("missoes_caca");

$missao = $missoes[$missao_cod];

if ($userDetails->tripulacao["missao_caca_progress"] < $missao["quant"]) {
    $protector->exit_error("Você ainda não concluiu a missão");
}

if (isset($missao["recompensas"])) {
    foreach ($missao["recompensas"] as $recompensa) {
        recebe_recompensa($recompensa);
    }
}

$connection->run("UPDATE tb_usuarios SET berries = berries + ?, missao_caca = NULL, missao_caca_progress = NULL WHERE id = ?",
    "ii", array($missao["berries"], $userDetails->tripulacao["id"]));


if (isset($missao["diario"]) && $missao["diario"]) {
    $connection->run("INSERT INTO tb_missoes_caca_diario (tripulacao_id, missao_caca_id) VALUE (?, ?)",
        "ii", array($userDetails->tripulacao["id"], $missao_cod));
}

echo "-Missão concluída!";