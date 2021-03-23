<?php

$personagens_combate["1"] = get_pers_in_combate($combate["id_1"]);
$personagens_combate["2"] = get_pers_in_combate($combate["id_2"]);

$venceu_1 = TRUE;
$venceu_2 = TRUE;

$tabuleiro = [];

$obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 1",
    "i", array($combate["id_1"]))->fetch_all_array();
foreach ($obstaculos as $obstaculo) {
    $tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = obstaculo_para_tabuleiro($obstaculo);
}

$obstaculos = $connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 2",
    "i", array($combate["id_2"]))->fetch_all_array();
foreach ($obstaculos as $obstaculo) {
    $tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = obstaculo_para_tabuleiro($obstaculo);
}

foreach ($personagens_combate["1"] as $pers) {
    if ($pers["hp"]) {
        $venceu_2 = FALSE;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
foreach ($personagens_combate["2"] as $pers) {
    if ($pers["hp"]) {
        $venceu_1 = FALSE;
        $tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
    }
}
$special_effects = get_special_effects($combate["id_1"], $combate["id_2"]);

$id_blue = $combate["id_1"];
?>
<?php if ($venceu_1) : ?>
    <div id="fim_batalha">
        <h2><?= $tripulacao["1"]["tripulacao"] ?> venceu a partida!</h2>
    </div>
<?php elseif ($venceu_2) : ?>
    <div id="fim_batalha">
        <h2><?= $tripulacao["2"]["tripulacao"] ?> venceu a partida!</h2>
    </div>
<?php endif; ?>
<div id="relatorio_combate" class="panel panel-info">
    <div class="panel-heading">Relatório</div>
    <div id="relatorio-combate-content" class="panel-body">
        <?php $combate_logger = new CombateLogger($connection, $userDetails); ?>
        <?php render_relatorio_data($combate_logger->get_relatorio_combate_pvp($combate["combate"]), $id_blue, $userDetails->tripulacao["adm"]); ?>
    </div>
</div>