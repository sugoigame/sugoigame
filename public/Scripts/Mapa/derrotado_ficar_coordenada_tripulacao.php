<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_tripulacao_died();


$connection->run("UPDATE tb_personagens SET hp = hp_max WHERE id = ? AND ativo = 1",
    "i", array($userDetails->tripulacao["id"]));


echo "%oceano";