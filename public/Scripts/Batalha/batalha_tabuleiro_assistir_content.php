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

    <br><br>
<?php if ($venceu_1) : ?>
    <div id="fim_batalha">
        <h2><?= $tripulacao["1"]["tripulacao"] ?> venceu a partida!</h2>
        <p>
            <a href="./?ses=home" class="link_content btn btn-success">
                Voltar a página inicial
            </a>
        </p>
    </div>
<?php elseif ($venceu_2) : ?>
    <div id="fim_batalha">
        <h2><?= $tripulacao["2"]["tripulacao"] ?> venceu a partida!</h2>
        <p>
            <a href="./?ses=home" class="link_content btn btn-success">
                Voltar a página inicial
            </a>
        </p>
    </div>
<?php else: ?>
    <h3>
        Valor acumulado das apostas:
        <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($combate["premio_apostas"]) ?>
    </h3>
    <?php $minha_aposta = $connection->run("SELECT * FROM tb_combate_apostas WHERE combate_id = ? AND tripulacao_id = ?",
        "ii", array($combate["combate"], $userDetails->tripulacao["id"])); ?>
    <?php if ($minha_aposta->count()): ?>
        <?php $minha_aposta = $minha_aposta->fetch_array()["aposta"]; ?>
        <?php $apostado = $minha_aposta == $combate["id_1"] ? $tripulacao["1"]["tripulacao"] : $tripulacao["2"]["tripulacao"]; ?>
        <h4>Você apostou em <?= $apostado ?></h4>
    <?php elseif (!$combate["fim_apostas"]): ?>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-info link_confirm"
                        href="Batalha/apostar.php?cbt=<?= $combate["combate"] ?>&aposta=<?= $combate["id_1"] ?>"
                        data-question="Deseja apostar em <?= mascara_berries($combate["preco_apostas"]) ?> Berries em <?= $tripulacao["1"]["tripulacao"] ?>? Você não poderá mudar sua aposta depois."
                    <?= $userDetails->tripulacao["berries"] < $combate["preco_apostas"] ? "disabled" : "" ?>>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($combate["preco_apostas"]) ?><br/>
                    Apostar em <?= $tripulacao["1"]["tripulacao"] ?>
                </button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-danger link_confirm"
                        href="Batalha/apostar.php?cbt=<?= $combate["combate"] ?>&aposta=<?= $combate["id_2"] ?>"
                        data-question="Deseja apostar em <?= mascara_berries($combate["preco_apostas"]) ?> Berries em <?= $tripulacao["2"]["tripulacao"] ?>? Você não poderá mudar sua aposta depois."
                    <?= $userDetails->tripulacao["berries"] < $combate["preco_apostas"] ? "disabled" : "" ?>>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($combate["preco_apostas"]) ?><br/>
                    Apostar em <?= $tripulacao["2"]["tripulacao"] ?>
                </button>
            </div>
        </div>
    <?php else: ?>
        <p>O período de apostas para essa batalha já acabou</p>
    <?php endif; ?>

    <?php render_combate_pvp_placar(array(
        "1" => array(
            "id" => $combate["id_1"],
            "quant" => count($personagens_combate["1"]),
            "personagens" => $personagens_combate["1"]
        ),
        "2" => array(
            "id" => $combate["id_2"],
            "quant" => count($personagens_combate["2"]),
            "personagens" => $personagens_combate["2"]
        )
    ), $id_blue); ?>
    <br/>
    <div id="batalha_background"
        <?php if ($combate["battle_back"]): ?>
            style="background: url(Imagens/Batalha/BattleBacks/<?= $combate["battle_back"] ?>.jpg) no-repeat top center; -webkit-background-size: cover; background-size: cover;"
        <?php endif; ?>>
        <div class="fight-zone">
            <div class="navio navio-player"
                <?php if (!$combate["battle_back"]): ?>
                    style="background: url(Imagens/Bandeiras/Navios/<?= $tripulacao["2"]["faccao"] ?>/<?= $tripulacao["2"]["skin_tabuleiro_navio"] ?>/batalha.png) no-repeat center"
                <?php endif; ?>>
                <?php render_tabuleiro($tabuleiro, 0, 5, $id_blue, NULL, $special_effects); ?>
            </div>
            <div class="navio navio-player"
                <?php if (!$combate["battle_back"]): ?>
                    style="background: url(Imagens/Bandeiras/Navios/<?= $tripulacao["1"]["faccao"] ?>/<?= $tripulacao["1"]["skin_tabuleiro_navio"] ?>/batalha.png) no-repeat center"
                <?php endif; ?>>
                <?php render_tabuleiro($tabuleiro, 5, 10, $id_blue, NULL, $special_effects); ?>
            </div>
        </div>
        <div class="personagens-info">
            <?php render_personagens_info(
                array_merge($personagens_combate["1"], $personagens_combate["2"]),
                get_buffs_combate($combate["id_1"], $combate["id_2"]),
                $id_blue,
                $special_effects
            ); ?>
        </div>
    </div>
    <div id="menu_batalha">
        <div class="tempo-combate">
            <input type="hidden" id="vez-combate" value="<?= $combate["vez"] ?>">
            <img src="Imagens/Batalha/Menu/Tempo.png"/>
            <span id="tempo_batalha"><?= $combate["vez_tempo"] - atual_segundo(); ?></span>
        </div>
    </div>

    <div id="relatorio_combate" class="panel panel-info" style="display: none;">
        <div class="panel-heading">Relatório</div>
        <div id="relatorio-combate-content" class="panel-body">
            <?php // $combate_logger = new CombateLogger($connection, $userDetails); ?>
            <?php // render_relatorio_data($combate_logger->get_relatorio_combate_pvp($combate["combate"]), $id_blue, $userDetails->tripulacao["adm"]); ?>
        </div>
    </div>
<?php endif; ?>