<?php
require __DIR__ . "/../Classes/DataLoader.php";

$mapa = DataLoader::load("mapa");

define('MAX_X', 22);
define('MAX_Y', 12);
?>

<html>
<head>

</head>
<body>
<style type="text/css">
    table {
        border-collapse: collapse;
    }

    td {
        padding: 0;
    }

    #selection {
        position: absolute;
        width: 18400px;
    }

    #selection td {
        width: 40px;
        height: 40px;
        border: 1px solid rgba(0, 0, 0, 0.5);
        box-sizing: border-box;
        display: inline-block;
    }

    #selection td:hover {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.7);
    }

    .nao-navegavel {
        background: rgba(255, 0, 0, 0.7);
    }
</style>
<table id="selection">
    <?php for ($y = 0; $y < (MAX_Y + 1) * 20; $y++): ?>
        <tr>
            <?php for ($x = 0; $x < (MAX_X + 1) * 20; $x++): ?>
                <td title="<?= $x . "_" . $y ?>" class="<?= isset($mapa[$x]) && isset($mapa[$x][$y])
                    ? (isset($mapa[$x][$y]["nao_navegavel"]) ? "nao-navegavel" : "") :
                    "" ?>">

                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>
<table id="mar">
    <?php for ($y = 0; $y <= MAX_Y; $y++): ?>
        <tr>
            <?php for ($x = 0; $x <= MAX_X; $x++): ?>
                <td>
                    <img src="../Imagens/Mapa/Mapa_Mundi/<?= $x ?>_<?= $y ?>.jpg">
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>
</body>
</html>