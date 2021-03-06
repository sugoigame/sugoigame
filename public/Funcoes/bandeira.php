<?php
function generate_flag($fac, $cod, $path = "") {
    $F = substr($cod, 0, 2);
    $FC = substr($cod, 2, 2);
    $FX = substr($cod, 4, 2);
    $FY = substr($cod, 6, 2);
    $FW = substr($cod, 8, 2);
    $FH = substr($cod, 10, 2);

    $C = substr($cod, 12, 2);
    $CC = substr($cod, 14, 2);
    $CX = substr($cod, 16, 2);
    $CY = substr($cod, 18, 2);
    $CW = substr($cod, 20, 2);
    $CH = substr($cod, 22, 2);

    $A = substr($cod, 24, 2);
    $AC = substr($cod, 26, 2);
    $AX = substr($cod, 28, 2);
    $AY = substr($cod, 30, 2);
    $AW = substr($cod, 32, 2);
    $AH = substr($cod, 34, 2);

    $background = $path . $fac . ".png";
    $background = imagecreatefrompng($background);

    $fundo = $path . $fac . "/F/" . $F . "/" . $FC . ".png";
    $meio = $path . $fac . "/C/" . $C . "/" . $CC . ".png";
    $frente = $path . $fac . "/A/" . $A . "/" . $AC . ".png";

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

    return array(
        "bandeira" => $background,
        "layers" => arraY($background, $fundo, $meio, $frente)
    );
}

function destroy_flag($flag) {
    foreach ($flag["layers"] as $layer) {
        imagedestroy($layer);
    }
}