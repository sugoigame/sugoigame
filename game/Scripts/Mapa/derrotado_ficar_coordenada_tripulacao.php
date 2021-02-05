<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_died();

$preco = preco_ficar_coordenada_derrotado_tripulacao();
$protector->need_berries($preco);

$connection->run("UPDATE tb_personagens SET hp = 1 WHERE id = ? AND ativo = 1",
    "i", array($userDetails->tripulacao["id"]));

$userDetails->reduz_berries($preco);

echo "%oceano";