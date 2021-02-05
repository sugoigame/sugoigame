<?php
$codok = FALSE;
$fac = isset($_GET["f"]) ? (int)$_GET["f"] : 1;

if ($fac == 1) {
    $adx = 50;
    $ady = 50;
    $scala = 1;
} else {
    $adx = 60;
    $ady = 100;
    $scala = 1;
}

if (isset($_GET["cod"]) && strlen($_GET["cod"]) == 36) {
    $cod = $_GET["cod"];
    $F = substr($cod, 0, 2);
    $FC = substr($cod, 2, 2);
    $FX = (int)substr($cod, 4, 2) * $scala + $adx;
    $FY = (int)substr($cod, 6, 2) * $scala + $ady;
    $FW = substr($cod, 8, 2) * $scala;
    $FH = substr($cod, 10, 2) * $scala;

    $C = substr($cod, 12, 2);
    $CC = substr($cod, 14, 2);
    $CX = (int)substr($cod, 16, 2) * $scala + $adx;
    $CY = (int)substr($cod, 18, 2) * $scala + $ady;
    $CW = substr($cod, 20, 2) * $scala;
    $CH = substr($cod, 22, 2) * $scala;

    $A = substr($cod, 24, 2);
    $AC = substr($cod, 26, 2);
    $AX = (int)substr($cod, 28, 2) * $scala + $adx;
    $AY = (int)substr($cod, 30, 2) * $scala + $ady;
    $AW = substr($cod, 32, 2) * $scala;
    $AH = substr($cod, 34, 2) * $scala;
    $codok = TRUE;
}

$background = "navio_g_$fac.png";
$background = imagecreatefrompng($background);
imageAlphaBlending($background, true);
imageSaveAlpha($background, true);

if ($codok) {
    $fundo = $fac . "/F/" . $F . "/" . $FC . ".png";
    $meio = $fac . "/C/" . $C . "/" . $CC . ".png";
    $frente = $fac . "/A/" . $A . "/" . $AC . ".png";

    $fundo = imagecreatefrompng($fundo);
    $fundo_x = imagesx($fundo);
    $fundo_y = imagesy($fundo);
    $meio = imagecreatefrompng($meio);
    $meio_x = imagesx($meio);
    $meio_y = imagesy($meio);
    $frente = imagecreatefrompng($frente);
    $frente_x = imagesx($frente);
    $frente_y = imagesy($frente);

    imagecopyresampled($background, $fundo, $FX, $FY, 0, 0, $FW, $FH, $fundo_x, $fundo_y);
    imagecopyresampled($background, $meio, $CX, $CY, 0, 0, $CW, $CH, $meio_x, $meio_y);
    imagecopyresampled($background, $frente, $AX, $AY, 0, 0, $AW, $AH, $frente_x, $frente_y);
}
header('Content-type: image/png');
imagepng($background);
if ($codok) {
    imagedestroy($fundo);
    imagedestroy($meio);
    imagedestroy($frente);
}
imagedestroy($background);