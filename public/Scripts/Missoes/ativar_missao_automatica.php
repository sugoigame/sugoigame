<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$connection->run("UPDATE tb_usuarios SET missoes_automaticas = ? WHERE id = ?",
    "ii", array($userDetails->tripulacao["missoes_automaticas"] ? 0 : 1, $userDetails->tripulacao["id"]));

echo "%missoes";