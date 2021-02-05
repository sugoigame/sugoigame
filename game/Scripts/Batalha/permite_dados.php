<?php
require "../../Includes/conectdb.php";


$protector->need_tripulacao();
$protector->must_be_in_combat_pvp();

$my_id = $userDetails->combate_pvp["id_1"] == $userDetails->tripulacao["id"] ? "1" : "2";

$connection->run("UPDATE tb_combate SET permite_dados_$my_id = 1 WHERE combate = ?",
    "i", $userDetails->combate_pvp["combate"]);

echo "Você permitiu o relatório avançado na sua batalha";