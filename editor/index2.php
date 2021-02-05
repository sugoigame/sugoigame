<?php include "../game/Includes/conectdb.php"; ?>
<?php
$x1 = isset($_GET["x1"]) ? $_GET["x1"] : 1;
$x2 = isset($_GET["x2"]) ? $_GET["x2"] : 50;
$y1 = isset($_GET["y1"]) ? $_GET["y1"] : 1;
$y2 = isset($_GET["y2"]) ? $_GET["y2"] : 50;
?>
<table>
    <?php for ($y = $y1; $y <= $y2; $y++): ?>
        <tr>
            <?php for ($x = $x1; $x <= $x2; $x++): ?>
                <td>
                    <img title="<?= $x ?>_<?= $y ?> / <?= get_human_location($x, $y); ?>"
                         src="../game/Imagens/Mapa/Mapa_Oceano/Mapa_<?= sprintf("%02d", $x) ?>_<?= sprintf("%02d", $y) ?>.jpg">
                </td>
            <?php endfor; ?>
        </tr>
    <?php endfor; ?>
</table>
    