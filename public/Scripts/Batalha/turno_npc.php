<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_pve();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

$combate->turno_npc();


