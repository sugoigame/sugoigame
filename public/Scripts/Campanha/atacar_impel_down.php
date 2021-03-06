<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_campanha_impel_down();

$campanha = DataLoader::load("campanha_impel_down");
$validator = new CampanhaImpelDown($campanha, $connection, $userDetails, $protector);
$etapa = $validator->get_current_stage();

if (!isset_and_true($etapa, "atacar_rdm_id")) {
    $protector->exit_error("Ação invalida");
}

atacar_rdm($etapa["atacar_rdm_id"]);

echo "%combate";