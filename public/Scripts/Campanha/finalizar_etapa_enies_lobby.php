<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_campanha_enies_lobby();

$campanha = DataLoader::load("campanha_enies_lobby");
$validator = new CampanhaEniesLobby($campanha, $connection, $userDetails, $protector);
$progresso = $validator->get_current_progress();

if ($progresso["progresso_atual"] < $progresso["progresso_total"]) {
    $protector->exit_error("Você não cumpre os requisitos para concluir essa etapa.");
}

$msg = $validator->finaliza_etapa_atual();

echo $msg;