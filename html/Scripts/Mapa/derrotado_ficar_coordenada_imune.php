<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_died();

$preco = preco_ficar_coordenada_derrotado_imune();
$protector->need_berries($preco);

$pvp_recente = $connection->run(
    "SELECT vencedor FROM tb_combate_log WHERE (id_1 = ? OR id_2 = ?) ORDER BY horario DESC LIMIT 1",
    "ii", array($userDetails->tripulacao["id"], $userDetails->tripulacao["id"])
);

if (!$pvp_recente->count()) {
    $protector->exit_error("Você não participou de PvP pecentemente");
}

$vencedor = $pvp_recente->fetch_array();

if ($vencedor["vencedor"] == $userDetails->tripulacao["id"]) {
    $protector->exit_error("Você venceu seu último PvP");
}

$connection->run("UPDATE tb_personagens SET hp = 1 WHERE id = ? AND ativo = 1",
    "i", array($userDetails->tripulacao["id"]));

$connection->run("INSERT INTO tb_pvp_imune (tripulacao_id, adversario_id) VALUE (?,?)",
    "ii", array($userDetails->tripulacao["id"], $vencedor["vencedor"]));

$userDetails->reduz_berries($preco);

echo "%oceano";