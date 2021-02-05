<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$premios = DataLoader::load("recompensas_coliseu");

$premio_atual = $premios[$userDetails->tripulacao["coliseu_premio"]];

if ($userDetails->tripulacao["coliseu_points"] < $premio_atual["minimo"]) {
    $protector->exit_error("Você não alcançou seu objetivo para receber a recompensa");
}

$msg = recebe_recompensa($premio_atual);

$connection->run("UPDATE tb_usuarios SET coliseu_premio = coliseu_premio + 1 WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

$response->send_share_msg($msg);