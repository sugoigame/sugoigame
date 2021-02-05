<?php
require_once "../../Includes/database/mywrap.php";

if (!isset($_GET["mar"]) || !isset($_GET["cod"])) {
    exit();
}

// mysqli
$connection = new mywrap_con();

$connection->run("SET NAMES 'utf8'");
$connection->run("SET character_set_connection=utf8");
$connection->run("SET character_set_client=utf8");
$connection->run("SET character_set_results=utf8");
$connection->run("SET CHARACTER SET utf8");

$mar = $_GET["mar"];
$codmapa = $_GET["cod"];

$result = $connection->run(
    "SELECT mapa.desenho AS desenho FROM tb_item_mapa mapa WHERE mapa.cod_mapa= ?",
    "i", array($codmapa)
);

if (!$result->count()) {
    exit();
}

$mapa = $result->fetch_array();

function draw_grid(&$img, $x0, $y0, $width, $height, $cols, $rows, $color) {
    //first draw horizontal
    $x1 = $x0 * $width;
    $x2 = ($x0 + 1) * $width - 1;
    $y1 = $y0 * $height;
    $y2 = ($y0 + 1) * $height - 1;

    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $color);
}

switch ($mar) {
    case 1:
        $ymin = 0;
        $ymax = 5 * 20;
        $xmin = 13 * 20;
        $xmax = 23 * 20;
        break;

    case 2:
        $ymin = 0;
        $ymax = 5 * 20;
        $xmin = 0;
        $xmax = 10 * 20;
        break;

    case 3:
        $ymin = 13 * 20;
        $ymax = 18 * 20;
        $xmin = 0;
        $xmax = 10 * 20;
        break;

    case 4:
        $ymin = 13 * 20;
        $ymax = 18 * 20;
        $xmin = 13 * 20;
        $xmax = 23 * 20;
        break;

    case 5:
        $ymin = 5 * 20;
        $ymax = 13 * 20;
        $xmin = 13 * 20;
        $xmax = 23 * 20;
        break;

    case 6:
        $ymin = 5 * 20;
        $ymax = 13 * 20;
        $xmin = 0;
        $xmax = 10 * 20;

        break;
    default:
        exit();
        break;
}

$visivel = $mapa["desenho"] ? json_decode($mapa["desenho"], true) : [];
$proporcao = 1000 / 8000;

$image = imagecreatetruecolor(1000, $mar > 4 ? 800 : 500);
imagesavealpha($image, true);

$hidden_color = imagecolorallocatealpha($image, 0, 0, 0, 50);
$visible_color = imagecolorallocatealpha($image, 255, 255, 255, 127);

// primeiro esconde tudo
imagefill($image, 0, 0, $visible_color);

// agora vai clareando
for ($y = $ymin; $y <= $ymax; $y++) {
    for ($x = $xmin; $x <= $xmax; $x++) {
        if (!isset($visivel[$x]) || !isset($visivel[$x][$y])) {
            draw_grid($image, $x - $xmin - 1, $y - $ymin - 1, round($proporcao * 40), round($proporcao * 40), $xmax - $xmin, $ymax - $ymin, $hidden_color);
        }
    }
}

header('Content-type: image/png');
imagepng($image);
imagedestroy($image);