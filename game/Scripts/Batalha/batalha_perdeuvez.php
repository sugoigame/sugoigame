<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_pvp();

$combate = new Combate($connection, $userDetails, $protector);

$combate->perdeu_vez_pvp();

echo("%combate");