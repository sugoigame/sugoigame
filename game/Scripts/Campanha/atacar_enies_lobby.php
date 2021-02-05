<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_campanha_enies_lobby();

$campanha = DataLoader::load("campanha_enies_lobby");
$validator = new CampanhaEniesLobby($campanha, $connection, $userDetails, $protector);
$etapa = $validator->get_current_stage();

if (!isset_and_true($etapa, "atacar_rdm_id")) {
    $protector->exit_error("Ação invalida");
}

atacar_rdm($etapa["atacar_rdm_id"]);

echo "%combate";