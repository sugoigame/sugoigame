<?php
$codok = FALSE;
$fac = isset($_GET["f"]) ? (int)$_GET["f"] : 1;
$skin = isset($_GET["s"]) ? (int)$_GET["s"] : 0;
$dir = isset($_GET["d"]) ? (int)$_GET["d"] : 4;

$ajustes = array(
    0 => array(
        0 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 30,
                "ady" => 18,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 8,
                "ady" => 18,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        1 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 29,
                "ady" => 23,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 12,
                "ady" => 23,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        2 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 25,
                "ady" => 16,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 13,
                "ady" => 16,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        3 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 20,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 26,
                "ady" => 15,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 8,
                "ady" => 20,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        4 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 20,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 26,
                "ady" => 15,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 8,
                "ady" => 20,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        5 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 35,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 27,
                "ady" => 35,
                "scala" => 0.24
            ),
            5 => array(
                "adx" => 11,
                "ady" => 35,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        6 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 27,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 30,
                "ady" => 20,
                "scala" => 0.3
            ),
            5 => array(
                "adx" => 11,
                "ady" => 27,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        7 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 57,
                "ady" => 40,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 33,
                "ady" => 35,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 11,
                "ady" => 40,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        8 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 28,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 26,
                "ady" => 28,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 15,
                "ady" => 28,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        9 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 57,
                "ady" => 40,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 33,
                "ady" => 35,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 11,
                "ady" => 40,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        10 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 27,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 30,
                "ady" => 20,
                "scala" => 0.3
            ),
            5 => array(
                "adx" => 11,
                "ady" => 27,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
    ),
    1 => array(
        0 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 30,
                "ady" => 18,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 8,
                "ady" => 18,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        1 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 29,
                "ady" => 23,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 12,
                "ady" => 23,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        2 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 30,
                "ady" => 18,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 15,
                "ady" => 15,
                "scala" => 0.4
            ),
            5 => array(
                "adx" => 8,
                "ady" => 18,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        3 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 20,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 26,
                "ady" => 15,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 8,
                "ady" => 20,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        4 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 20,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 26,
                "ady" => 15,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 8,
                "ady" => 20,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        5 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 35,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 27,
                "ady" => 35,
                "scala" => 0.24
            ),
            5 => array(
                "adx" => 11,
                "ady" => 35,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        6 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 27,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 30,
                "ady" => 20,
                "scala" => 0.3
            ),
            5 => array(
                "adx" => 11,
                "ady" => 27,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
        7 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 57,
                "ady" => 40,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 33,
                "ady" => 35,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 11,
                "ady" => 40,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        8 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 40,
                "ady" => 28,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 26,
                "ady" => 28,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 15,
                "ady" => 28,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        9 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 57,
                "ady" => 40,
                "scala" => 0.25
            ),
            4 => array(
                "adx" => 33,
                "ady" => 35,
                "scala" => 0.25
            ),
            5 => array(
                "adx" => 11,
                "ady" => 40,
                "scala" => 0.25
            ),
            6 => false,
            7 => false
        ),
        10 => array(
            0 => false,
            1 => false,
            2 => false,
            3 => array(
                "adx" => 47,
                "ady" => 27,
                "scala" => 0.3
            ),
            4 => array(
                "adx" => 30,
                "ady" => 20,
                "scala" => 0.3
            ),
            5 => array(
                "adx" => 11,
                "ady" => 27,
                "scala" => 0.3
            ),
            6 => false,
            7 => false
        ),
    )
);

if (isset($_GET["cod"]) && strlen($_GET["cod"]) == 36 && isset($ajustes[$fac]) && isset($ajustes[$fac][$skin]) && $ajustes[$fac][$skin][$dir]) {
    $ajuste = $ajustes[$fac][$skin][$dir];
    $scala = $ajuste["scala"];
    $adx = $ajuste["adx"];
    $ady = $ajuste["ady"];

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

$background = "Navios/$fac/$skin/$dir.png";
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