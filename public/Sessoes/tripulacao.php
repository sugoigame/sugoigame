<style type="text/css">
    .bar-step {
        position: absolute;
        z-index: 1;
        font-size: .8rem;
    }

    .label-line {
        float: right;
        background: #fff;
        height: 50px;
        width: 1px;
        margin-left: 0;
    }

    .label-percent {
        position: absolute;
        right: 5px;
        color: white;
    }
</style>

<div class="panel-heading">
    <h3 class="m0">
        <span class="mr3">
            <?= $userDetails->tripulacao["tripulacao"]; ?> -
            <?= get_patente_nome($userDetails->tripulacao["faccao"], $userDetails->tripulacao["battle_lvl"]) ?>
        </span>
        <a href="link_bandeira" class="link_content2" id="status_bandeira" data-toggle="tooltip" data-placement="right"
            title="Clique para personalizar sua bandeira">
            <img alt="bandeira"
                src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>">
        </a>
    </h3>
</div>
<div class="panel-body">
<div class="row">
            <?php if (count($userDetails->personagens) < 15) : ?>
                <?php render_progress(
                    "progress-tripulantes",
                    count($userDetails->personagens) / 15,
                    count($userDetails->personagens),
                    count($userDetails->personagens) . " de 15 tripulantes recrutados",
                    "#45859a",
                    "recrutar"
                ); ?>
            <?php endif; ?>

            <?php if ($userDetails->lvl_mais_forte < 50) : ?>
                <?php render_progress(
                    "progress-mais-forte",
                    $userDetails->lvl_mais_forte / 50,
                    $userDetails->lvl_mais_forte,
                    "Personagem mais forte no nível " . $userDetails->lvl_mais_forte . " de 50",
                    "#c04000",
                    "status"
                ); ?>
            <?php endif; ?>

            <?php $rep_mais_forte = $connection->run(
                "SELECT max(reputacao) AS total FROM tb_usuarios"
            )->fetch_array()["total"]; ?>
            <?php if (count($userDetails->personagens) >= 15) : ?>
                <?php render_progress(
                    "progress-reputacao",
                    $userDetails->tripulacao["reputacao"] / $rep_mais_forte,
                    mascara_numeros_grandes($userDetails->tripulacao["reputacao"]),
                    mascara_numeros_grandes($userDetails->tripulacao["reputacao"]) . " pontos de reputação",
                    "#62c462",
                    "ranking"
                ); ?>
            <?php endif; ?>

            <?php if ($userDetails->lvl_mais_forte >= 50) : ?>
                <?php $fa_mais_forte = $connection->run(
                    "SELECT max(fama_ameaca) AS total FROM tb_personagens"
                )->fetch_array()["total"]; ?>
                <?php render_progress(
                    "progress-wanted",
                    $userDetails->fa_mais_alta / $fa_mais_forte,
                    abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
                    "Recompensa mais alta de " . abrevia_numero_grande(calc_recompensa($userDetails->fa_mais_alta)),
                    "#ee5f5b",
                    "ranking&rank=fa"
                ); ?>
            <?php endif; ?>

            <?php $missoes_concluidas = $connection->run(
                "SELECT count(conc.cod_missao) AS total
                                        FROM tb_missoes_concluidas conc
                                        WHERE conc.id = ?",
                "i", array($userDetails->tripulacao["id"])
            )->fetch_array()["total"]; ?>
            <?php $missoes_total = 176 ?>
            <?php render_progress(
                "progress-missoes",
                $missoes_concluidas / $missoes_total,
                $missoes_concluidas,
                "$missoes_concluidas missões concluídas na ilha",
                "#45859a",
                "missoes"
            ); ?>

            <?php $chefes_derrotados = $connection->run(
                "SELECT count(conc.tripulacao_id) AS total
                                        FROM tb_missoes_chefe_ilha conc
                                        WHERE conc.tripulacao_id = ?",
                "i", array($userDetails->tripulacao["id"])
            )->fetch_array()["total"]; ?>
            <?php $chefes_total = 47 ?>
            <?php render_progress(
                "progress-chefes",
                $chefes_derrotados / $chefes_total,
                $chefes_derrotados,
                "$chefes_derrotados de $chefes_total Chefes das Ilhas derrotados",
                "#c04000",
                "missoes"
            ); ?>
        </div>
    <div class="row">
        <div class="col col-xs-3">
            <?php
            $trimestral = $connection->run("SELECT * FROM tb_ranking_reputacao WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_array();
            $mensal = $connection->run("SELECT * FROM tb_ranking_reputacao_mensal WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_array();
            ?>
            <div class="mb3">
                <div>
                    <img width="100px" src="./Imagens/Icones/poneglyph.png" alt="poneglyphs" />
                </div>
                <div>
                    Poneglyphs:
                    <?= mascara_numeros_grandes($userDetails->tripulacao["reputacao_mensal"]) ?>
                    <?= ajuda_tooltip("Os Poneglyphs aparecem misteriosamente pelo oceano. Os capitães com maior número de Poneglyphs obtem o título de Yonkou e Almirante."); ?>
                </div>
                <div>
                    <?= isset($mensal["posicao"]) ? "Posição no ranking: " . $mensal["posicao"] . "º" : "Você precisa de um Poneglyph para participar do Ranking"; ?>
                </div>
            </div>
            <div class="">
                <div>
                    <img width="100px" src="./Imagens/Icones/road-poneglyph.png" alt="road-poneglyphs" />
                </div>
                <div>
                    Road Poneglyphs:
                    <?= mascara_numeros_grandes($userDetails->tripulacao["reputacao"]) ?>
                    <?= ajuda_tooltip("Os Poneglyphs podem ser convertidos em Road Poneglyphs. Os capitães com a maior quantidade de Road Poneglyphs obtem o título de Rei dos Piratas e Almirante de Frota."); ?>
                </div>
                <div>
                    <?= isset($trimestral["posicao"]) ? "Posição no ranking: " . $trimestral["posicao"] . "º" : "Você precisa de um Road Poneglyph para participar do Ranking"; ?>
                </div>
            </div>
        </div>

        <div class="col col-xs-9">
            <div class="p1">
                <h3>
                    Nível de Batalha:
                    <?= $userDetails->tripulacao["battle_lvl"] ?>
                    <?= ajuda_tooltip("Você ganha Experiência de Batalha sempre que derrotar tripulantes adversários em batalhas PvP. Você receberá uma recompensa surpresa quando conseguir Experiência de Batalha suficiente para evoluir seu Nível de Batalha."); ?>
                </h3>

                <div class="progress">
                    <div class="progress-bar progress-bar-warning"
                        style="width: <?= $userDetails->tripulacao["battle_points"] / PONTOS_POR_NIVEL_BATALHA * 100 ?>%">
                        <span>
                            Experiência de Batalha:
                            <?= $userDetails->tripulacao["battle_points"] . " / " . PONTOS_POR_NIVEL_BATALHA ?>
                        </span>
                    </div>
                </div>
                <?php if ($userDetails->tripulacao["battle_points"] >= PONTOS_POR_NIVEL_BATALHA) : ?>
                    <button class="btn btn-success link_send" href="link_Geral/nivel_batalha.php">
                        Receber a Recompensa
                    </button>
                    <?= get_alert(); ?>
                <?php endif; ?>
            </div>
            <div>
                <h4>
                    Patente:
                    <?= get_patente_nome($userDetails->tripulacao["faccao"], $userDetails->tripulacao["battle_lvl"]) ?>
                    <img alt="patente" height="40vw"
                        src="Imagens/Ranking/Patentes/<?= $userDetails->tripulacao["faccao"] . "_" . get_patente_id($userDetails->tripulacao["battle_lvl"]) ?>.png">
                    <?= ajuda_tooltip("Sua patente é determinada de acordo com seu nível de batlha."); ?>
                </h4>
            </div>
            <div class="row p1">
                <?php $max_patente_level = get_max_patente_level(); ?>
                <?php $marks = get_patente_marks(); ?>
                <div class="col-xs-12">
                    <div class="progress m0">
                        <?php for ($id = 0; $id < 13; $id++) : ?>
                            <div class="bar-step" style="left: <?= $marks[$id] / $max_patente_level * 100 ?>%">
                                <div class="label-percent">
                                    <?= $marks[$id] ?>
                                </div>
                                <div class="label-line"></div>
                            </div>
                        <?php endfor; ?>
                        <div class="step-prorgress-bar progress-bar progress-bar-success"
                            style="width: <?= $userDetails->tripulacao["battle_lvl"] / $max_patente_level * 100 ?>%;">
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div style="position: relative;">
                        <?php $marks[] = $max_patente_level; ?>
                        <?php for ($id = 0; $id < count($marks); $id++) : ?>
                            <div class="bar-step"
                                style="left: <?= ($id ? $marks[$id - 1] : 0) / $max_patente_level * 100 ?>%;width: <?= ($marks[$id] - ($id ? $marks[$id - 1] : 0)) / $max_patente_level * 100 ?>%;">
                                <img style="max-width: 30px; margin: auto" data-toggle="tooltip"
                                    title="<?= get_patente_nome($userDetails->tripulacao["faccao"], $marks[$id] - 1) ?>"
                                    src="Imagens/Ranking/Patentes/<?= $userDetails->tripulacao["faccao"] . "_" . $id ?>.png" />
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
                <br />
                <br />
                <br />
            </div>
        </div>
    </div>
</div>
