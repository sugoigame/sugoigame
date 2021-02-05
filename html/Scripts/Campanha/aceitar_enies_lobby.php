<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_campanha_enies_lobby();

$campanha = DataLoader::load("campanha_enies_lobby");
$validator = new CampanhaEniesLobby($campanha, $connection, $userDetails, $protector);
$etapa = $validator->get_current_stage();

if (!isset_and_true($etapa, "aceitavel")) {
    $protector->exit_error("Ação invalida");
}

$msg = $validator->finaliza_etapa_atual();

if (substr($msg, 0, 1) == "%") {
    echo $msg;
}