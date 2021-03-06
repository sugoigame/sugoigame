<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();
$protector->need_tripulacao_alive();

$protector->need_berries(PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO);

$connection->run("UPDATE tb_usuarios SET berries = berries - ?, x = ?, y = ?, mar_visivel = 0 WHERE id = ?",
    "iiii", array(PRECO_BERRIES_TRANSPORTE_ILHA_RETORNO, $userDetails->tripulacao["res_x"], $userDetails->tripulacao["res_y"], $userDetails->tripulacao["id"]));

echo("%oceano");