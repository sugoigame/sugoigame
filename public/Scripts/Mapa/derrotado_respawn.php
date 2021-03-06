<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_died();

$connection->run("UPDATE tb_usuarios SET x = res_x, y = res_y, mar_visivel = 0 WHERE id = ?",
    "i", array($userDetails->tripulacao["id"]));

echo "%hospital";