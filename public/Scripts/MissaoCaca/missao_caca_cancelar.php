<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$connection->run("UPDATE tb_usuarios SET missao_caca = NULL, missao_caca_progress = NULL WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

echo "-Missão cancelada.";