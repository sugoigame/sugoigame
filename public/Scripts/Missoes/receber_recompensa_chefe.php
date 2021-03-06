<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();

$chefe_derrotado = $connection->run(
    "SELECT * FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
);
if (!$chefe_derrotado->count()) {
    $protector->exit_error("Você ainda não derrotou o chefe dessa ilha");
}
$chefe_derrotado = $chefe_derrotado->fetch_array();

if ($chefe_derrotado["recompensa_recebida"]) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

$chefes_ilha = DataLoader::load("chefes_ilha");

$recompensas = $chefes_ilha[$userDetails->ilha["ilha"]]["recompensas"];

foreach ($recompensas as $recompensa) {
    recebe_recompensa($recompensa);
}

$connection->run("UPDATE tb_missoes_chefe_ilha SET recompensa_recebida = 1 WHERE tripulacao_id = ? AND ilha_derrotado = ?",
    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

$response->send_share_msg("Você derrotou o chefe de " . nome_ilha($userDetails->ilha["ilha"]));