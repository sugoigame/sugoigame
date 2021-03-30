<?php
$codok  = FALSE;
$fac    = isset($_GET["f"]) ? (int)$_GET["f"] : 1;
$skin   = isset($_GET["s"]) ? (int)$_GET["s"] : 0;
$dir    = isset($_GET["d"]) ? (int)$_GET["d"] : 4;

$ajustes    = [
    0 => [
        3 => [
            "adx" => 30,
            "ady" => 18,
            "scala" => 0.3
        ],
        4 => [
            "adx" => 15,
            "ady" => 15,
            "scala" => 0.4
        ],
        5 => [
            "adx" => 10,
            "ady" => 18,
            "scala" => 0.3
        ]
    ],
    1 => [
        3 => [
            "adx" => 30,
            "ady" => 23,
            "scala" => 0.25
        ],
        4 => [
            "adx" => 15,
            "ady" => 15,
            "scala" => 0.4
        ],
        5 => [
            "adx" => 13,
            "ady" => 24,
            "scala" => 0.25
        ]
    ],
    2 => array(
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
        )
    ),
    3 => array(
        3 => array(
            "adx" => 67,
            "ady" => 20,
            "scala" => 0.3
        ),
        4 => array(
            "adx" => 67,
            "ady" => 15,
            "scala" => 0.25
        ),
        5 => array(
            "adx" => 60,
            "ady" => 20,
            "scala" => 0.3
        )
    ),
    4 => array(
        3 => array(
            "adx" => 67,
            "ady" => 20,
            "scala" => 0.3
        ),
        4 => array(
            "adx" => 67,
            "ady" => 15,
            "scala" => 0.25
        ),
        5 => array(
            "adx" => 60,
            "ady" => 20,
            "scala" => 0.3
        )
    ),
    5 => array(
        3 => array(
            "adx" => 75,
            "ady" => 36,
            "scala" => 0.25
        ),
        4 => array(
            "adx" => 68,
            "ady" => 35,
            "scala" => 0.24
        ),
        5 => array(
            "adx" => 65,
            "ady" => 37,
            "scala" => 0.25
        )
    ),
    6 => array(
        3 => array(
            "adx" => 107,
            "ady" => 27,
            "scala" => 0.3
        ),
        4 => array(
            "adx" => 110,
            "ady" => 20,
            "scala" => 0.3
        ),
        5 => array(
            "adx" => 113,
            "ady" => 27,
            "scala" => 0.3
        )
    ),
    7 => array(
        3 => array(
            "adx" => 115,
            "ady" => 40,
            "scala" => 0.25
        ),
        4 => array(
            "adx" => 114,
            "ady" => 35,
            "scala" => 0.25
        ),
        5 => array(
            "adx" => 115,
            "ady" => 40,
            "scala" => 0.25
        )
    ),
    8 => array(
        3 => array(
            "adx" => 68,
            "ady" => 28,
            "scala" => 0.25
        ),
        4 => array(
            "adx" => 67,
            "ady" => 28,
            "scala" => 0.25
        ),
        5 => array(
            "adx" => 68,
            "ady" => 28,
            "scala" => 0.25
        )
    ),
    9 => array(
        3 => array(
            "adx" => 113,
            "ady" => 40,
            "scala" => 0.25
        ),
        4 => array(
            "adx" => 115,
            "ady" => 35,
            "scala" => 0.25
        ),
        5 => array(
            "adx" => 110,
            "ady" => 40,
            "scala" => 0.25
        )
    ),
    10 => array(
        3 => array(
            "adx" => 107,
            "ady" => 27,
            "scala" => 0.3
        ),
        4 => array(
            "adx" => 110,
            "ady" => 20,
            "scala" => 0.3
        ),
        5 => array(
            "adx" => 115,
            "ady" => 27,
            "scala" => 0.3
        )
    ),
];

if (isset($_GET["cod"])) {
    $background = "Navios/{$fac}/{$skin}/sprite.png";
    $background = imagecreatefrompng($background);
    imageAlphaBlending($background, true);
    imageSaveAlpha($background,     true);

    for ($dir = 3; $dir <= 5; $dir++) {
        $ajuste = $ajustes[$skin][$dir];
        $scala  = $ajuste["scala"];
        $adx    = $ajuste["adx"] + (65 * $dir);
        $ady    = $ajuste["ady"];

        $cod    = $_GET["cod"];
        $F      = substr($cod, 0, 2);
        $FC     = substr($cod, 2, 2);
        $FX     = (int)substr($cod, 4, 2) * $scala + $adx;
        $FY     = (int)substr($cod, 6, 2) * $scala + $ady;
        $FW     = substr($cod, 8, 2) * $scala;
        $FH     = substr($cod, 10, 2) * $scala;

        $C      = substr($cod, 12, 2);
        $CC     = substr($cod, 14, 2);
        $CX     = (int)substr($cod, 16, 2) * $scala + $adx;
        $CY     = (int)substr($cod, 18, 2) * $scala + $ady;
        $CW     = substr($cod, 20, 2) * $scala;
        $CH     = substr($cod, 22, 2) * $scala;

        $A      = substr($cod, 24, 2);
        $AC     = substr($cod, 26, 2);
        $AX     = (int)substr($cod, 28, 2) * $scala + $adx;
        $AY     = (int)substr($cod, 30, 2) * $scala + $ady;
        $AW     = substr($cod, 32, 2) * $scala;
        $AH     = substr($cod, 34, 2) * $scala;


        $fundo  = $fac . "/F/" . $F . "/" . $FC . ".png";
        $meio   = $fac . "/C/" . $C . "/" . $CC . ".png";
        $frente = $fac . "/A/" . $A . "/" . $AC . ".png";

        $fundo      = imagecreatefrompng($fundo);
        $fundo_x    = imagesx($fundo);
        $fundo_y    = imagesy($fundo);
        $meio       = imagecreatefrompng($meio);
        $meio_x     = imagesx($meio);
        $meio_y     = imagesy($meio);
        $frente     = imagecreatefrompng($frente);
        $frente_x   = imagesx($frente);
        $frente_y   = imagesy($frente);

        imagecopyresampled($background, $fundo, $FX, $FY, 0, 0, $FW, $FH, $fundo_x, $fundo_y);
        imagecopyresampled($background, $meio, $CX, $CY, 0, 0, $CW, $CH, $meio_x, $meio_y);
        imagecopyresampled($background, $frente, $AX, $AY, 0, 0, $AW, $AH, $frente_x, $frente_y);
    }

    header('Content-type: image/png');
    imagepng($background);

    imagedestroy($fundo);
    imagedestroy($meio);
    imagedestroy($frente);
}
imagedestroy($background);