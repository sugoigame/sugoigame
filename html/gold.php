<?php
$valida = "EquipeSugoiGame2012";
require "Includes/conectdb.php";

if ($userDetails->tripulacao) {
    echo $userDetails->tripulacao["gold"];
}
