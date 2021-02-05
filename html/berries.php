<?
$valida = "EquipeSugoiGame2012";
require "Includes/conectdb.php";

if ($userDetails->tripulacao) {
    echo mascara_berries($userDetails->tripulacao["berries"]);
}