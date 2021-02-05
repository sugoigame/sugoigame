<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id = $protector->get_number_or_exit("rec");

$recompensas = DataLoader::load("recompensas_boss");

if (!isset($recompensas[$id])) {
    $protector->exit_error("Recompensa invalida");
}

$recompensa = $recompensas[$id];

$recompensado = $connection->run("SELECT count(*) AS total FROM tb_evento_recompensa WHERE tripulacao_id = ? AND recompensa_id = ?",
    "ii", array($userDetails->tripulacao["id"], $id))->fetch_array()["total"];

if ($recompensado) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

$dano = $connection->run("SELECT damage FROM tb_boss_damage WHERE real_boss_id = 10 AND tripulacao_id = ?",
    "i", $userDetails->tripulacao["id"])->fetch_array();
$dano = $dano["damage"] ? $dano["damage"] : 0;

if ($dano < $recompensa["minimo"]) {
    $protector->exit_error("Você ainda não pode receber essa recompensa");
}

$msg = recebe_recompensa($recompensa);

$userDetails->xp_for_all(500);

$connection->run("INSERT INTO tb_evento_recompensa (tripulacao_id, recompensa_id) VALUE (?, ?)",
    "ii", array($userDetails->tripulacao["id"], $id));

$response->send_share_msg($msg);