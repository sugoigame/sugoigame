<?php
include "../../Funcoes/bandeira.php";

header('Content-type: image/jpeg');

$fac = isset($_GET["f"]) ? (int)$_GET["f"] : 1;

if (!isset($_GET["cod"]) || strlen($_GET["cod"]) != 36) {
    $background = $fac . ".png";
    $background = imagecreatefrompng($background);
    imagejpeg($background);
    imagedestroy($background);
} else {
    $flag = generate_flag($fac, $_GET["cod"]);
    imagejpeg($flag["bandeira"]);
    destroy_flag($flag);
}

