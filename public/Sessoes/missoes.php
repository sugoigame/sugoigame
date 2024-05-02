<?php $missoes = DataLoader::load("missoes"); ?>

<?php function render_missao($missao, $index, $count_missoes_concluidas, $total_concluido_hoje)
{ ?>

    <?php global $userDetails; ?>
    <div class="panel panel-default m0 h-100">
        <div class="panel-body">
            <p>
                Missão
                <?= $index + 1 ?>
            </p>
            <div>
                <?php if ($index >= $count_missoes_concluidas) : ?>
                    <div class=" mb">
                        <small>
                            <?php if ($userDetails->capitao["lvl"] >= $missao["requisito_lvl"]) : ?>
                                <span class="label label-success"><i class="fa fa-check"></i></span>
                            <?php endif; ?>Requer Capitão Nível
                            <?= $missao["requisito_lvl"]; ?>
                        </small>
                    </div>
                    <div>
                        <small>Recompensas:</small>
                        <div>
                            <small>
                                <img src="Imagens/NPC/xp.jpg" height="18px">
                                <?= $missao["recompensa_xp"]; ?>
                            </small>
                        </div>
                        <div>
                            <small>
                                <img src="Imagens/Icones/Berries.png">
                                <?= mascara_berries($missao["recompensa_berries"]) ?>
                            </small>
                        </div>
                    </div>
                <?php else : ?>
                    <div>
                        <small>
                            Repita essa missão para ganhar pontos de Karma.
                        </small>
                    </div>
                    <div>
                        <small>
                            Você ainda pode repetir
                            <?= MAX_MISSOES_ILHA_DIA - $total_concluido_hoje ?> missões nessa ilha por
                            hoje.
                        </small>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel-footer">
            <?php if ($index <= $count_missoes_concluidas && $userDetails->capitao["lvl"] >= $missao["requisito_lvl"] && MAX_MISSOES_ILHA_DIA > $total_concluido_hoje) : ?>
                <div>
                    <button href='link_Missoes/missao_iniciar.php?cod=<?= $missao["cod_missao"]; ?>&tipo=bom'
                        class="link_send btn btn-info" title="Fazer de boa fé" data-toggle="tooltip">
                        <i class="fa fa-smile-o"></i>
                        (
                        <?= $userDetails->tripulacao["missoes_automaticas"] ? floor($missao["karma"] * 0.5) : $missao["karma"]; ?>)
                    </button>
                    <button href='link_Missoes/missao_iniciar.php?cod=<?= $missao["cod_missao"]; ?>&tipo=mau'
                        class="link_send btn btn-danger" title="Fazer de má fé" data-toggle="tooltip">
                        <i class="fa fa-frown-o"></i>
                        (
                        <?= $userDetails->tripulacao["missoes_automaticas"] ? floor($missao["karma"] * 0.5) : $missao["karma"]; ?>)
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php } ?>

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

<div class="panel-heading">
    Missões
</div>
<div class="panel-body">
    <?php if (! $userDetails->missao) : ?>

        <?php $missoes_concluidas = $connection->run(
            "SELECT *
            FROM tb_ilha_missoes ilhamis
            LEFT JOIN tb_missoes_concluidas conc ON ilhamis.cod_missao = conc.cod_missao AND conc.id = ?
            WHERE ilhamis.ilha = ? AND conc.cod_missao IS NOT NULL ",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]))->fetch_all_array(); ?>

        <?php $missoes_disponiveis = $connection->run(
            "SELECT *
            FROM tb_ilha_missoes ilhamis
            WHERE ilhamis.ilha = ?
            ORDER BY ilhamis.cod_missao",
            "i", array($userDetails->ilha["ilha"]))->fetch_all_array(); ?>

        <?php $count_missoes_concluidas = count($missoes_concluidas); ?>
        <?php $count_missoes_disponiveis = count($missoes_disponiveis) - $count_missoes_concluidas; ?>
        <?php $missoes_total = count($missoes_disponiveis); ?>

        <?php render_karma_bars(); ?>
        <p>
            <a class="link_content" href="./?ses=karma">
                Ver recompensas de Karma
            </a>
        </p>

        <?php $result = $connection->run("SELECT quant FROM tb_missoes_concluidas_dia WHERE tripulacao_id = ? AND ilha = ? AND cast(dia as date) = CURDATE()",
            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"]));

        $total_concluido_hoje = $result->count() ? $result->fetch_array()["quant"] : 0; ?>

        <div class="row p1">
            <?php if ($missoes_total) : ?>
                <?php foreach ($missoes_disponiveis as $index => $missao) : ?>
                    <?php $missao = array_merge($missao, $missoes[$missao["cod_missao"]]); ?>
                    <div class="col col-xs-2 p1px">
                        <?php render_missao($missao, $index, $count_missoes_concluidas, $total_concluido_hoje); ?>
                    </div>
                <?php endforeach; ?>
                <div class="col col-xs-2 p1px">
                    <div class="panel panel-default m0 h-100">
                        <?php $chefe_derrotado_data = $connection->run(
                            "SELECT * FROM tb_missoes_chefe_ilha WHERE tripulacao_id = ? AND ilha_derrotado = ?",
                            "ii", array($userDetails->tripulacao["id"], $userDetails->ilha["ilha"])
                        ); ?>
                        <?php $chefe_derrotado = $chefe_derrotado_data->count(); ?>
                        <?php $chefe_derrotado_data = $chefe_derrotado_data->fetch_array(); ?>
                        <div class="panel-body">
                            <p>
                                Chefe da Ilha
                            </p>
                            <?php if (! $chefe_derrotado_data["recompensa_recebida"]) : ?>
                                <?php
                                $chefes_ilha = DataLoader::load("chefes_ilha");

                                $recompensas = $chefes_ilha[$userDetails->ilha["ilha"]]["recompensas"];

                                $equipamentos = get_equipamentos_for_recompensa();
                                $reagents = get_reagents_for_recompensa();
                                ?>
                                <small>
                                    <?php foreach ($recompensas as $recompensa) : ?>
                                        <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
                                    <?php endforeach; ?>
                                </small>
                            <?php endif; ?>
                        </div>
                        <div class="panel-footer">
                            <?php if ($count_missoes_concluidas >= $missoes_total) : ?>
                                <?php if (! $chefe_derrotado) : ?>
                                    <p>
                                        <button data-question="Deseja enfrentar o Chefe da Ilha agora?" href="Missoes/atacar_chefe.php"
                                            class="link_confirm btn btn-success">
                                            Desafiar
                                        </button>
                                    </p>
                                <?php else : ?>
                                    <?php if (! $chefe_derrotado_data["recompensa_recebida"]) : ?>
                                        <p>
                                            <button class="btn btn-success link_send" href="link_Missoes/receber_recompensa_chefe.php">
                                                Receber a recompensa
                                            </button>
                                        </p>
                                    <?php else : ?>
                                        <p class="text-success">
                                            Chefe Derrotado <i class="fa fa-check"></i>
                                        </p>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <label class="link_send" href="link_Missoes/ativar_missao_automatica.php" style="cursor: pointer">
            <input type="checkbox" <?= $userDetails->tripulacao["missoes_automaticas"] ? "checked" : "" ?>>
            Fazer as missões automaticamente
        </label>

    <?php else : ?>
        <?php $missao = $userDetails->missao; ?>
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
</div>
