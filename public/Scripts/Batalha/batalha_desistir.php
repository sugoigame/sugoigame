<?php
require_once "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$connection->run("UPDATE tb_combate_personagens SET hp = 0, desistencia = 1 WHERE id = ? AND hp > 0", "i", $userDetails->tripulacao["id"]);

echo "-Você desistiu do combate";
