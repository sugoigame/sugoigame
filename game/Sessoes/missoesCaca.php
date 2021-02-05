<div class="panel-heading">
    Missões de caça
</div>

<div class="panel-body">
    <?= ajuda("Missões de caça", "Missões de caça são missões que pagam uma boa grana para que você derrote criaturas marítimas diversas.") ?>

    <?php $missoes = DataLoader::load("missoes_caca"); ?>
    <?php $rdms = DataLoader::load("rdm"); ?>

    <p>A sua tripulação não perde Score de classe se for derrotada pela criatura alvo de uma missão de caça.</p>
    <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
    <?php $reagents = get_reagents_for_recompensa(); ?>

    <?php if ($userDetails->tripulacao["missao_caca"]): ?>
        <?php $missao = $missoes[$userDetails->tripulacao["missao_caca"]]; ?>
        <?php $rdm = $rdms[$missao["objetivo"]]; ?>
        <h3><?= $missao["nome"] ?></h3>
        <h4>
            Objetivo: <?= $rdm["nome"] ?> x<?= $missao["quant"] ?>
        </h4>
        <h4>
            Recompensas:
        </h4>
        <ul class="text-left">
            <li><img src="Imagens/Icones/Berries.png"> <?= mascara_berries($missao["berries"]) ?></li>
            <?php if (isset($missao["recompensas"])): ?>
                <?php foreach ($missao["recompensas"] as $recompensa): ?>
                    <li><?php render_recompensa($recompensa, $reagents, $equipamentos); ?></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
        <?php if (isset($missao["boss"]) && $missao["boss"]): ?>
            <h5>Essa criatura pode ser encontrada nas seguintes coordenadas:</h5>
            <?php $zonas = $connection->run("SELECT x, y FROM tb_mapa_rdm WHERE rdm_id = ?", "i", $missao["objetivo"]); ?>
            <?php while ($quadro = $zonas->fetch_array()) : ?>
                <p>
                    <?= get_human_location($quadro["x"], $quadro["y"]) ?>
                    - <?= nome_mar(get_mar($quadro["x"], $quadro["y"])) ?>
                </p>
            <?php endwhile; ?>
        <?php endif; ?>
        <p>
            Agora vá! Só volte aqui quando tiver derrotado todas as criaturas necessárias para pegar sua recompensa.
        </p>
        <div class="progress">
            <div class="progress-bar progress-bar-info"
                 style="width: <?= $userDetails->tripulacao["missao_caca_progress"] / $missao["quant"] * 100 ?>%">
                <span><?= $userDetails->tripulacao["missao_caca_progress"] . "/" . $missao["quant"] ?></span>
            </div>
        </div>

        <p>
            <?php if ($userDetails->tripulacao["missao_caca_progress"] < $missao["quant"]) : ?>
                <button href="MissaoCaca/missao_caca_cancelar.php"
                        data-question="Deseja cancelar essa missão?"
                        class="link_confirm btn btn-danger">
                    Cancelar
                </button>
            <?php else: ?>
                <button href="link_MissaoCaca/missao_caca_finalizar.php"
                        class="link_send btn btn-success">
                    Finalizar
                </button>
            <?php endif; ?>
        </p>
    <?php else: ?>
        <?php foreach ($missoes as $id => $missao): ?>
            <?php if (in_array($userDetails->ilha["mar"], $missao["mares"])): ?>
                <?php $rdm = $rdms[$missao["objetivo"]]; ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <?= $missao["nome"]; ?> <?= isset($missao["diario"]) && $missao["diario"] ? "(Diária)" : "" ?>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>
                                    Objetivo: <?= $rdm["nome"] ?> x<?= $missao["quant"] ?>
                                </h4>
                            </div>
                            <div class="col-md-6 ">
                                <h4>
                                    Recompensas:
                                </h4>
                                <ul class="text-left">
                                    <li>
                                        <img src="Imagens/Icones/Berries.png"> <?= mascara_berries($missao["berries"]) ?>
                                    </li>
                                    <?php if (isset($missao["recompensas"])): ?>
                                        <?php foreach ($missao["recompensas"] as $recompensa): ?>
                                            <li><?php render_recompensa($recompensa, $reagents, $equipamentos); ?></li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div>
                            <?php if (isset($missao["diario"]) && $missao["diario"]): ?>
                                <?php $completa = $connection->run("SELECT * FROM tb_missoes_caca_diario WHERE tripulacao_id = ? AND missao_caca_id = ?",
                                    "ii", array($userDetails->tripulacao["id"], $id))->count(); ?>
                                <?php if (!$completa): ?>
                                    <button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
                                            data-question="Deseja iniciar essa missão?"
                                            class="link_confirm btn btn-success">
                                        Iniciar
                                    </button>
                                <?php else: ?>
                                    <p>Você já completou essa missão hoje, volte aqui amanhã.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <button href="MissaoCaca/missao_caca_iniciar.php?cod=<?= $id ?>"
                                        data-question="Deseja iniciar essa missão?"
                                        class="link_confirm btn btn-success">
                                    Iniciar
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>