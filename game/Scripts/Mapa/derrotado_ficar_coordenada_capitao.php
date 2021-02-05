<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_died();

$preco = preco_ficar_coordenada_derrotado_capitao();
$protector->need_berries($preco);

$connection->run("UPDATE tb_personagens SET hp = 1 WHERE cod = ?",
    "i", array($userDetails->capitao["cod"]));

$userDetails->reduz_berries($preco);

echo "%oceano";