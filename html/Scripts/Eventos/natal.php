<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("recompensas_natal");

if (!isset($recompensas[$id])) {
    $protector->exit_error("Recompensa invalida");
}

$recompensa = $recompensas[$id];

$recompensado = $connection->run("SELECT count(*) AS total FROM tb_evento_amizade_recompensa WHERE tripulacao_id = ? AND recompensa_id = ?",
    "ii", array($userDetails->tripulacao["id"], $id))->fetch_array()["total"];

if ($recompensado) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

if (isset($recompensa["rdm_id"])) {
    $derrotados = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona = ?",
        "ii", array($userDetails->tripulacao["id"], $recompensa["rdm_id"]))->fetch_array()["total"];
} else {
    $todos_ids = [];
    foreach ($recompensas as $id => $recompensa) {
        if (isset($recompensa["rdm_id"])) {
            $todos_ids[] = $recompensa["rdm_id"];
        }
    }
    $derrotados = $connection->run("SELECT count(*) AS total FROM tb_pve WHERE id= ? AND zona IN (" . (implode(",", $todos_ids)) . ")",
        "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];
}

if ($derrotados < $recompensa["minimo"]) {
    $protector->exit_error("Você ainda não pode receber essa recompensa");
}

$msg = recebe_recompensa($recompensa);

$userDetails->xp_for_all(500);

$connection->run("INSERT INTO tb_evento_amizade_recompensa (tripulacao_id, recompensa_id) VALUE (?, ?)",
    "ii", array($userDetails->tripulacao["id"], $id));

$response->send_share_msg($msg);