<?php $realizacoes_mng = new Realizacoes($userDetails, $connection); ?>

<?php function render_realizacao($realizacao, $key, $pers = NULL) { ?>
    <?php global $realizacoes_mng; ?>
    <?php
    $status_key = "status_" . $realizacao["cod_realizacao"];
    $status = $realizacoes_mng->$status_key($pers);
    if ($realizacao["cod_pers"]) {
        if ($pers != null && $realizacao["cod_pers"] == $pers["cod"]) {
            $status["status"]["status"] = REALIZACAO_STATUS_RECOMPENSADO;
        }
    } else if ($realizacao["conclusao"]) {
        $status["status"]["status"] = REALIZACAO_STATUS_RECOMPENSADO;
    }
    ?>
    <li class="list-group-item aprendido_<?= $key ?>_<?= $status["status"]["status"] ?>">
        <h4>
            <img src="Imagens/Icones/r_<?= $status["status"]["status"] ?>.png">
            <?= $realizacao["nome"] ?>
        </h4>
        <p><?= $realizacao["descricao"] ?></p>
        <p><?= $realizacao["pontos"] ?> Pontos de Realização</p>

        <?php if ($realizacao["titulo"]) : ?>
            <p>Titulo: <?= $realizacao["titulo"] ?></p>
            <?php if ($realizacao["bonus_quant"]): ?>
                <p> <?= nome_atributo($realizacao["bonus_atr"]) ?> + <?= $realizacao["bonus_quant"] ?></p>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($status["status"]["status"] == REALIZACAO_STATUS_COMPLETO) : ?>
            <button href="link_Realizacoes/concluir.php?cod=<?= $realizacao["cod_realizacao"] ?>&pers=<?= $pers["cod"] ?>"
                    class="link_send btn btn-success">
                Concluir
            </button>
        <?php elseif ($status["status"]["status"] != REALIZACAO_STATUS_RECOMPENSADO && $status["progresso"]): ?>
            <div>
                <div class="progress">
                    <div class="progress-bar progress-bar-primary"
                         style="width: <?= $status["progresso"]["current"] / $status["progresso"]["max"] * 100 ?>%;">
                        <span><?= $status["progresso"]["current"] . "/" . $status["progresso"]["max"] ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </li>
<?php } ?>

<div class="panel-heading">
    Conquistas
</div>

<script type="text/javascript">
    $(function () {
        $(".aprendidos").click(function (e) {
            e.preventDefault();
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');
            $(".aprendido_" + $(this).data('tipo') + "_1").fadeOut(100);
            $(".aprendido_" + $(this).data('tipo') + "_2").fadeOut(100);
            $(".aprendido_" + $(this).data('tipo') + "_3").fadeIn(100);
        });
        $(".n_aprendidos").click(function (e) {
            e.preventDefault();
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');
            $(".aprendido_" + $(this).data('tipo') + "_1").fadeIn(100);
            $(".aprendido_" + $(this).data('tipo') + "_2").fadeIn(100);
            $(".aprendido_" + $(this).data('tipo') + "_3").fadeOut(100);
        });
        $(".todos").click(function (e) {
            e.preventDefault();
            $(this).parent().find('li').removeClass('active');
            $(this).addClass('active');
            $(".aprendido_" + $(this).data('tipo') + "_1").fadeIn(100);
            $(".aprendido_" + $(this).data('tipo') + "_2").fadeIn(100);
            $(".aprendido_" + $(this).data('tipo') + "_3").fadeIn(100);
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Conquistas", "Conquistas são atos importantes que quando concluidos, dão pontos para rankear os 
        jogadores que mais completaram grandes feitos no jogo.<br>
        Existem também conquistas individuais que dão titulos aos personagens, títulos esses que por sua vez
        dão algum bonus ao  personagem que o adquirir.") ?>

    <h3> Total: <?= $userDetails->tripulacao["realizacoes"] ?> Pontos </h3>


    <?php $realizacoes = $connection->run(
        "SELECT 
          rel.tipo AS tipo,
          rel.categoria AS categoria,
          rel.cod_realizacao AS cod_realizacao,
          rel.nome AS nome,
          rel.pontos AS pontos,
          rel.descricao AS descricao,
          conc.momento AS conclusao,
          conc.personagem AS cod_pers, 
          titulo.nome AS titulo,
          titulo.bonus_atr AS bonus_atr,
          titulo.bonus_atr_quant AS bonus_quant
        FROM tb_realizacoes rel 
        LEFT JOIN tb_realizacoes_concluidas conc ON rel.cod_realizacao = conc.cod_realizacao AND conc.id = ?
        LEFT JOIN tb_titulos titulo ON rel.titulo = titulo.cod_titulo",
        "i", array($userDetails->tripulacao["id"])
    )->fetch_all_array(); ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="active"><a href="#realizacoes_gerais" data-toggle="tab">Conquistas Gerais</a></li>
            <li><a href="#realizacoes_indiv" data-toggle="tab">Conquistas Individuais</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div id="realizacoes_gerais" class="tab-pane active">
            <div>
                <ul class="nav nav-pills nav-justified">
                    <li class="active"><a href="#categoria_1_sel" data-toggle="tab">Nível</a></li>
                    <li><a href="#categoria_2_sel" data-toggle="tab">Reputação</a></li>
                    <li><a href="#categoria_3_sel" data-toggle="tab">PVP</a></li>
                    <li><a href="#categoria_4_sel" data-toggle="tab">PVE</a></li>
                    <!--<li><a href="#categoria_5_sel" data-toggle="tab">Exploração</a></li>-->
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="categoria_1_sel">
                    <div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="todos active" data-tipo="1"><a href="#">Todas</a></li>
                            <li class="aprendidos" data-tipo="1"><a href="#">Concluídas</a></li>
                            <li class="n_aprendidos" data-tipo="1"><a href="#">Incompletas</a></li>
                        </ul>
                    </div>

                    <ul class="list-group">
                        <?php foreach ($realizacoes as $realizacao) : ?>
                            <?php if ($realizacao["tipo"] == 0 && $realizacao["categoria"] == 1) {
                                render_realizacao($realizacao, 1);
                            } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-pane" id="categoria_2_sel">
                    <div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="todos active" data-tipo="2"><a href="#">Todas</a></li>
                            <li class="aprendidos" data-tipo="2"><a href="#">Concluídas</a></li>
                            <li class="n_aprendidos" data-tipo="2"><a href="#">Incompletas</a></li>
                        </ul>
                    </div>

                    <ul class="list-group">
                        <?php foreach ($realizacoes as $realizacao) : ?>
                            <?php if ($realizacao["tipo"] == 0 && $realizacao["categoria"] == 2) {
                                render_realizacao($realizacao, 2);
                            } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-pane" id="categoria_3_sel">
                    <div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="todos active" data-tipo="3"><a href="#">Todas</a></li>
                            <li class="aprendidos" data-tipo="3"><a href="#">Concluídas</a></li>
                            <li class="n_aprendidos" data-tipo="3"><a href="#">Incompletas</a></li>
                        </ul>
                    </div>
                    <ul class="list-group">
                        <?php foreach ($realizacoes as $realizacao) : ?>
                            <?php if ($realizacao["tipo"] == 0 && $realizacao["categoria"] == 3) {
                                render_realizacao($realizacao, 3);
                            } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-pane" id="categoria_4_sel">
                    <div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="todos active" data-tipo="4"><a href="#">Todas</a></li>
                            <li class="aprendidos" data-tipo="4"><a href="#">Concluídas</a></li>
                            <li class="n_aprendidos" data-tipo="4"><a href="#">Incompletas</a></li>
                        </ul>
                    </div>

                    <ul class="list-group">
                        <?php foreach ($realizacoes as $realizacao) : ?>
                            <?php if ($realizacao["tipo"] == 0 && $realizacao["categoria"] == 4) {
                                render_realizacao($realizacao, 4);
                            } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-pane" id="categoria_5_sel">
                    <div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="todos active" data-tipo="5"><a href="#">Todas</a></li>
                            <li class="aprendidos" data-tipo="5"><a href="#">Concluídas</a></li>
                            <li class="n_aprendidos" data-tipo="5"><a href="#">Incompletas</a></li>
                        </ul>
                    </div>

                    <ul class="list-group">
                        <?php foreach ($realizacoes as $realizacao) : ?>
                            <?php if ($realizacao["tipo"] == 0 && $realizacao["categoria"] == 5) {
                                render_realizacao($realizacao, 5);
                            } ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div id="realizacoes_indiv" class="tab-pane">

            <div>
                <?php render_personagens_pills(); ?>
            </div>

            <div class="tab-content">
                <?php foreach ($userDetails->personagens as $index => $pers): ?>
                    <?php $realizacoes = $connection->run(
                        "SELECT 
                          rel.tipo AS tipo,
                          rel.categoria AS categoria,
                          rel.cod_realizacao AS cod_realizacao,
                          rel.nome AS nome,
                          rel.pontos AS pontos,
                          rel.descricao AS descricao,
                          conc.momento AS conclusao,
                          conc.personagem AS cod_pers, 
                          titulo.nome AS titulo,
                          titulo.bonus_atr AS bonus_atr,
                          titulo.bonus_atr_quant AS bonus_quant
                        FROM tb_realizacoes rel 
                        LEFT JOIN tb_realizacoes_concluidas conc ON rel.cod_realizacao = conc.cod_realizacao AND conc.id = ? AND conc.personagem = ?
                        LEFT JOIN tb_titulos titulo ON rel.titulo = titulo.cod_titulo",
                        "ii", array($userDetails->tripulacao["id"], $pers["cod"])
                    )->fetch_all_array(); ?>
                    <?php render_personagem_panel_top($pers, $index); ?>
                    <ul class="list-group">
                        <div>
                            <ul class="nav nav-pills nav-justified">
                                <li class="active"><a href="#categoria_2_<?= $pers["cod"] ?>_sel" data-toggle="tab">habilidades</a>
                                </li>
                            </ul>
                        </div>
                        <div class="tab-content">
                            <div class="tab-pane active" id="categoria_2_<?= $pers["cod"] ?>_sel">
                                <div>
                                    <ul class="nav nav-pills nav-justified">
                                        <li class="todos active" data-tipo="2_<?= $pers["cod"] ?>">
                                            <a href="#">Todas</a>
                                        </li>
                                        <li class="aprendidos" data-tipo="2_<?= $pers["cod"] ?>">
                                            <a href="#">Concluídas</a>
                                        </li>
                                        <li class="n_aprendidos" data-tipo="2_<?= $pers["cod"] ?>">
                                            <a href="#">Incompletas</a>
                                        </li>
                                    </ul>
                                </div>

                                <ul class="list-group">
                                    <?php foreach ($realizacoes as $realizacao) : ?>
                                        <?php if ($realizacao["tipo"] == 1 && $realizacao["categoria"] == 2) {
                                            render_realizacao($realizacao, "2_" . $pers["cod"], $pers);
                                        } ?>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </ul>
                    <?php render_personagem_panel_bottom(); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>