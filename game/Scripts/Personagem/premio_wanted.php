<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers_cod = $protector->get_number_or_exit("cod");

$personagem = $userDetails->get_pers_by_cod($pers_cod);

if (!$personagem) {
    $protector->exit_error("Personagem não encontrado");
}

$premios = DataLoader::load("fa_premios");

$premio_atual = $premios[$personagem["fa_premio"]];

if ($personagem["fama_ameaca"] < $premio_atual["objetivo"]) {
    $protector->exit_error("Você não alcançou seu objetivo para receber a recompensa");
}

if (!$userDetails->can_add_item(count($premio_atual["recompensas"]))) {
    $protector->exit_error("Você não possui espaço suficiente no inventário");
}

foreach ($premio_atual["recompensas"] as $recompensa) {
    if (!isset($recompensa["unico"]) || !$userDetails->tripulacao["fa_premio_unico_" . $recompensa["unico"]]) {
        recebe_recompensa($recompensa, $personagem);
    }

    if (isset($recompensa["unico"]) && !$userDetails->tripulacao["fa_premio_unico_" . $recompensa["unico"]]) {
        $connection->run("UPDATE tb_usuarios SET fa_premio_unico_" . $recompensa["unico"] . " = 1 WHERE id = ?",
            "i", array($userDetails->tripulacao["id"]));
    }
}

$connection->run("UPDATE tb_personagens SET fa_premio = fa_premio + 1 WHERE cod = ?",
    "i", array($pers_cod));

$response->send_conquista_pers($personagem, $personagem["nome"] . " tem uma " . ($userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "recompensa" : "gratificação") . " de " . mascara_berries($premio_atual["objetivo"]) . " de Berries");