<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$combate = new Combate($connection, $userDetails, $protector);

$combate->pre_turn();

$combate->pos_turn();

if ($userDetails->combate_pve) {
    $combate->processa_turno_npc($combate->load_tabuleiro($userDetails->tripulacao["id"]));
}

echo "Você passou a vez";