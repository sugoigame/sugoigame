<?php $missoes = DataLoader::load("missoes"); ?>

<?php function render_missao($missao, $recompensa = true) { ?>
    <?php global $userDetails; ?>
    <div class="panel panel-default">
        <div class="panel-heading">Missão de nível <?= $missao["requisito_lvl"] ?></div>
        <div class="panel-body">
            <p> Intervalo: <?= transforma_tempo_min($missao["duracao"]) ?> </p>

            <?php if ($recompensa): ?>
                <div class="row text-left">
                    <div class="col-md-6">
                        <h4>Requisitos:</h4>
                        <ul>
                            <li>
                                Capitão Nível <?= $missao["requisito_lvl"]; ?>
                                <?php if ($userDetails->capitao["lvl"] >= $missao["requisito_lvl"]): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i></span>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h4>Recompensas:</h4>
                        <ul>
                            <li>
                                <?= $missao["recompensa_xp"]; ?>
                                pontos de experiência para toda a tripulação
                            </li>
                            <li>
                                <img src="Imagens/Icones/Berries.png"> <?= mascara_berries($missao["recompensa_berries"]) ?>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <div class="panel-footer">
            <?php if ($userDetails->capitao["lvl"] >= $missao["requisito_lvl"]) : ?>
                <p>
                    <label class="link_send" href="link_Missoes/ativar_missao_automatica.php" style="cursor: pointer">
                        <input type="checkbox" <?= $userDetails->tripulacao["missoes_automaticas"] ? "checked" : "" ?>>
                        Fazer as missões automaticamente
                    </label>
                </p>
                <div>
                    <button href='link_Missoes/missao_iniciar.php?cod=<?= $missao["cod_missao"]; ?>&tipo=bom'
                            class="link_send btn btn-info">
                        <i class="fa fa-smile-o"></i>
                        Fazer de boa fé
                        (<?= $userDetails->tripulacao["missoes_automaticas"] ? floor($missao["karma"] * 0.5) : $missao["karma"]; ?>
                        pontos de Karma Bom)
                    </button>
                    <button href='link_Missoes/missao_iniciar.php?cod=<?= $missao["cod_missao"]; ?>&tipo=mau'
                            class="link_send btn btn-danger">
                        <i class="fa fa-frown-o"></i>
                        Fazer de má fé
                        (<?= $userDetails->tripulacao["missoes_automaticas"] ? floor($missao["karma"] * 0.5) : $missao["karma"]; ?>
                        pontos de Karma Mau)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

<div class="panel-heading">
    <h3>Missões</h3>
</div>

<script type="text/javascript">
    $(function () {
        timeOuts["atualiza_tempo_missao"] = setTimeout("atualiza_tempo_missao()", 1000);
    });
    var conttmp = 0;
    function atualiza_tempo_missao() {
        var sec_rest = "tempo_sec";
        var min_rest = "tempo_min";
        if (document.getElementById(sec_rest) != null) {
            var tmp = document.getElementById(sec_rest).innerHTML - conttmp;
            document.getElementById(min_rest).innerHTML = transforma_tempo(tmp);
            if (tmp <= 0) {
                enviarNotificacao('O tempo de espera acabou!', {
                    body: 'Sua tripulação já pode fazer uma nova missão',
                    icon: 'https://sugoigame.com.br/Imagens/favicon.png'
                });
                reloadPagina();
            } else {
                timeOuts["atualiza_tempo_missao"] = setTimeout("atualiza_tempo_missao()", 1000);
                if (tmp) {
                    document.title = '[' + transforma_tempo(tmp) + '] ' + gameTitle;
                } else {
                    document.title = gameTitle;
                }
            }
        }

        conttmp += 1;
    }
</script>

<div class="panel-body">
    <?= ajuda("Missões", "Realize missões para ganhar ótimas recompensas") ?>

    <?php if (!$userDetails->missao && $userDetails->tripulacao["tempo_missao"] > atual_segundo()): ?>
        <p>
            Sua tripulação ainda não pode fazer outra missão
        </p>
        <p>
            Tempo restante:
            <span id="tempo_min"><?= transforma_tempo_min($userDetails->tripulacao["tempo_missao"] - atual_segundo()); ?></span>
            <span id="tempo_sec"
                  style="display: none;"><?= $userDetails->tripulacao["tempo_missao"] - atual_segundo(); ?></span>
        </p>
    <?php elseif (!$userDetails->missao) : ?>

        <?php $missoes_concluidas = $connection->run(
            "SELECT *
            FROM tb_ilha_missoes ilhamis
            LEFT JOIN tb_missoes_concluidas conc ON ilhamis.cod_missao = conc.cod_missao AND conc.id = ?
            WHERE ilhamis.ilha = ? AND conc.cod_missao IS NOT NULL ",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]))->fetch_all_array(); ?>

        <?php $missoes_disponiveis = $connection->run(
            "SELECT *
            FROM tb_ilha_missoes ilhamis
            LEFT JOIN tb_missoes_concluidas conc ON ilhamis.cod_missao = conc.cod_missao AND conc.id = ?
            WHERE ilhamis.ilha = ? AND conc.cod_missao IS NULL",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]))->fetch_all_array(); ?>

        <?php $count_missoes_concluidas = count($missoes_concluidas); ?>
        <?php $count_missoes_disponiveis = count($missoes_disponiveis); ?>
        <?php $missoes_total = count($missoes_disponiveis) + $count_missoes_concluidas; ?>

        <?php render_karma_bars(); ?>
        <p style="margin-top: -20px">
            <a class="link_content" href="./?ses=karma">
                Ver recompensas de Karma
            </a>
        </p>

        <div class="progress">
            <div class="progress-bar progress-bar-success"
                 style="width: <?= $count_missoes_concluidas / $missoes_total * 100 ?>%;">
            </div>
            <a class="noHref">
                Missões concluídas <?= $count_missoes_concluidas ?> / <?= $missoes_total ?>
            </a>
        </div>

        <?php if ($count_missoes_disponiveis): ?>
            <h3>Sua próxima missão:</h3>
            <?php foreach (array($missoes_disponiveis[0]) as $missao) : ?>
                <?php $missao = array_merge($missao, $missoes[$missao["cod_missao"]]); ?>
                <?php render_missao($missao); ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="panel panel-default">
            <div class="panel-heading">
                O Chefe da ilha está te esperando...
            </div>
            <div class="panel-body">
                <?php $chefe_derrotado_data = $connection->run(
                    "SELECT * FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?",
                    "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
                ); ?>
                <?php $chefe_derrotado = $chefe_derrotado_data->count(); ?>
                <?php $chefe_derrotado_data = $chefe_derrotado_data->fetch_array(); ?>
                <?php if ($count_missoes_concluidas >= $missoes_total): ?>
                    <?php if (!$chefe_derrotado): ?>
                        <h3 class="text-warning">Você desbloqueou a batalha contra o Chefe da Ilha!</h3>
                        <p>
                            <button data-question="Deseja enfrentar o Chefe da Ilha agora?"
                                    href="Missoes/atacar_chefe.php"
                                    class="link_confirm btn btn-success">
                                Desafiar o Chefe da Ilha
                            </button>
                        </p>
                    <?php else: ?>
                        <h3 class="text-success">Você já derrotou o Chefe dessa Ilha!</h3>
                        <?php if (!$chefe_derrotado_data["recompensa_recebida"]): ?>
                            <p>
                                <button class="btn btn-success link_send"
                                        href="link_Missoes/receber_recompensa_chefe.php">
                                    Receber a recompensa
                                </button>
                            </p>
                        <?php endif; ?>

                        <?php $proxima_ilha = $userDetails->ilha["ilha"] + 1; ?>
                        <?php $proxima_ilha = $proxima_ilha == 8 || $proxima_ilha == 15 || $proxima_ilha == 22 ? 29 : $proxima_ilha; ?>
                        <?php $coord = $connection->run("SELECT * FROM tb_mapa WHERE ilha = ?", "i", $proxima_ilha)->fetch_array(); ?>
                        <h3>
                            A próxima ilha se chama <?= nome_ilha($proxima_ilha) ?> e
                            fica em <?= get_human_location($coord["x"], $coord["y"]); ?>
                        </h3>
                    <?php endif; ?>
                <?php else: ?>
                    <h4>Você precisa concluir todas as missões para enfrentar o Chefe da Ilha.</h4>
                <?php endif; ?>

                <?php if (!$chefe_derrotado_data["recompensa_recebida"]): ?>
                    <p>
                        Recompensas por derrotar o chefe da ilha:
                    </p>
                    <?php
                    $chefes_ilha = DataLoader::load("chefes_ilha");

                    $recompensas = $chefes_ilha[$userDetails->ilha["ilha"]]["recompensas"];

                    $equipamentos = get_equipamentos_for_recompensa();
                    $reagents = get_reagents_for_recompensa();
                    ?>
                    <?php foreach ($recompensas as $recompensa): ?>
                        <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($count_missoes_concluidas): ?>
            <h3>Missões que você já concluiu nessa ilha:</h3>

            <?php $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ?",
                "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

            $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0; ?>
            <h4>
                Você ainda pode repetir <?= MAX_MISSOES_ILHA_DIA - $total_concluido_hoje ?> missões nessa ilha por
                hoje.
            </h4>

            <?php foreach ($missoes_concluidas as $missao) : ?>
                <?php $missao = array_merge($missao, $missoes[$missao["cod_missao"]]); ?>
                <?php render_missao($missao, false); ?>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else: ?>
        <?php $missao = $userDetails->missao; ?>
        <?php if (($missao["fim"] - atual_segundo()) > 0): ?>
            <h3>Missão de nível <?= $missao["requisito_lvl"] ?></h3>
            <p>
                A missão está em andamento...
            </p>
            <p>
                Tempo restante:
                <span id="tempo_min"><?= transforma_tempo_min($missao["fim"] - atual_segundo()); ?></span>
                <span id="tempo_sec" style="display: none;"><?= $missao["fim"] - atual_segundo(); ?></span>
            </p>
        <?php else: ?>
            <h3>
                <span class="<?= $missao["venceu"] ? "text-success" : "text-danger" ?>">
                    <?= $missao["venceu"] ? "Você venceu!" : "Que pena, você perdeu." ?>
                </span>
            </h3>
            <p>
                <button href='link_Missoes/missao_finalizar.php' class="link_send btn btn-success">
                    Finalizar missão
                </button>
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>