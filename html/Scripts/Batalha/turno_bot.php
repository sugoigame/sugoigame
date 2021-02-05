<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_combat_bot();

if ($userDetails->combate_bot["vez"] == 1) {
    $protector->exit_error("Ainda não é a vez do bot");
}

$combate = new Combate($connection, $userDetails, $protector);

$combate_bot = new CombateBot($connection, $userDetails, $combate);

$combate_bot->executa_acao();


