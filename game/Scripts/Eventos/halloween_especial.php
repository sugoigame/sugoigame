<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$especial = $connection->run("SELECT * FROM tb_evento_amizade_recompensa WHERE tripulacao_id = ?",
    "i", array($userDetails->tripulacao["id"]))->count();

if ($especial) {
    $protector->exit_error("Você já recebeu essa recompensa");
}

$userDetails->add_item(198, TIPO_ITEM_REAGENT, 1);

$especial = $connection->run("INSERT INTO tb_evento_amizade_recompensa (tripulacao_id, recompensa_id) VALUE (?,1)",
    "i", array($userDetails->tripulacao["id"]))->count();

$response->send_share_msg("Você recebeu um Contrato de extermínio!");