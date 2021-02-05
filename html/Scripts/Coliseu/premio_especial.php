<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if ($userDetails->tripulacao["coliseu_points_edicao_passada"] < 600) {
    $protector->exit_error("Você não alcançou seu objetivo para receber a recompensa");
}

recebe_recompensa(array(
    "tipo" => "reagent",
    "cod_item" => 155,
    "quant" => 1
));
recebe_recompensa(array(
    "tipo" => "reagent",
    "cod_item" => 121,
    "quant" => 1
));
recebe_recompensa(array(
    "tipo" => "reagent",
    "cod_item" => 200,
    "quant" => 5
));

$connection->run("UPDATE tb_usuarios SET coliseu_points_edicao_passada = 0 WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

$response->send_share_msg("Você recebeu uma Essência Verde e uma Akuma no Mi!");