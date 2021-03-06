<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$mar = $protector->get_number_or_exit("mar");
$codmapa = $protector->get_number_or_exit("cod");

$result = $connection->run(
    "SELECT mapa.desenho AS desenho
    FROM tb_usuario_itens itn
    INNER JOIN tb_item_mapa mapa ON itn.cod_item = mapa.cod_mapa AND itn.tipo_item = " . TIPO_ITEM_MAPA .
    " WHERE itn.id = ? AND itn.cod_item = ?",
    "ii", array($userDetails->tripulacao["id"], $codmapa)
);

if (!$result->count()) {
    $protector->exit_error("Você precisa de um mapa");
}
$mapa = $result->fetch_array();

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
        $protector->exit_error("Mar inválido");
        break;
}

$visivel = $mapa["desenho"] ? json_decode($mapa["desenho"], true) : [];

$ilhas = $connection->run("SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ?",
    "iiii", array($xmin, $xmax, $ymin, $ymax))->fetch_all_array();

$rdm_data = DataLoader::load("rdm");

$rdms = $connection->run("SELECT * FROM tb_mapa_rdm WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND visivel_cartografo = 1",
    "iiii", array($xmin, $xmax, $ymin, $ymax))->fetch_all_array();

$tesouros = $connection->run("SELECT * FROM tb_usuario_itens ui INNER JOIN tb_item_missao m ON ui.tipo_item = ? AND ui.cod_item = m.id WHERE ui.id = ?",
    "ii", array(TIPO_ITEM_MISSAO, $userDetails->tripulacao["id"]))->fetch_all_array();
?>

<style type="text/css">
    #hidden-areas {
        position: absolute;
        top: 0;
        left: 0;
        z-index: 1;
        width: 100%;
        height: 100%;
    }

    .map-indicator {
        position: absolute;
        z-index: 2;
    }
</style>
<div style="height: <?= $mar > 4 ? '800px' : '500px'; ?>; width: 1000px; position: relative;">
    <?php if ($userDetails->tripulacao["x"] >= $xmin
        && $userDetails->tripulacao["x"] <= $xmax
        && $userDetails->tripulacao["y"] >= $ymin
        && $userDetails->tripulacao["y"] <= $ymax): ?>
        <img src="Imagens/Mapa/Mapa_Cartografo/mostra_2.png"
             class="map-indicator"
             style="top: <?= ($userDetails->tripulacao["y"] - $ymin - 1) * 5; ?>px; left: <?= ($userDetails->tripulacao["x"] - $xmin - 1) * 5; ?>px;"/>
    <?php endif; ?>

    <?php foreach ($ilhas as $ilha): ?>
        <?php if (isset($visivel[$ilha["x"]]) && isset($visivel[$ilha["x"]][$ilha["y"]])): ?>
        <img src="Imagens/Mapa/Mapa_Cartografo/mostra_3.png"
             class="map-indicator" data-toggle="tooltip" title="<?= nome_ilha($ilha["ilha"]) ?>"
             style="top: <?= ($ilha["y"] - $ymin - 1) * 5; ?>px; left: <?= ($ilha["x"] - $xmin - 1) * 5; ?>px;"/>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php foreach ($tesouros as $tesouro): ?>
        <?php if (isset($visivel[$tesouro["x"]]) && isset($visivel[$tesouro["x"]][$tesouro["y"]])): ?>
            <img src="Imagens/Mapa/Mapa_Cartografo/mostra_4.png"
                 class="map-indicator" data-toggle="tooltip" title="Tesouro escondido"
                 style="top: <?= ($tesouro["y"] - $ymin - 1) * 5; ?>px; left: <?= ($tesouro["x"] - $xmin - 1) * 5; ?>px;"/>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php foreach ($rdms as $rdm): ?>
        <?php if (isset($visivel[$rdm["x"]]) && isset($visivel[$rdm["x"]][$rdm["y"]])): ?>
            <img src="Imagens/Mapa/Mapa_Cartografo/mostra_5.png"
                 class="map-indicator" data-toggle="tooltip" title="<?= $rdm_data[$rdm["rdm_id"]]["nome"] ?>"
                 style="top: <?= ($rdm["y"] - $ymin - 1) * 5; ?>px; left: <?= ($rdm["x"] - $xmin - 1) * 5; ?>px;"/>
        <?php endif; ?>
    <?php endforeach; ?>
    <img id="hidden-areas"
         src="Scripts/Cartografo/hidden_areas.php?mar=<?= $mar ?>&cod=<?= $codmapa ?>&_=<?= atual_segundo() ?>"/>
</div>
