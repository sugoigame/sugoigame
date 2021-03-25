<?php function render_nav($desired_nav, $label, $icon, $nav, $pers) { ?>
    <?php global $userDetails; ?>
    <li class="<?= $nav == $desired_nav ? "active" : "" ?>" onclick="setQueryParam('nav','<?= $desired_nav ?>')"
        id="nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>-seletor">
        <a href="#nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>" data-toggle="tab"
           onclick="loadNav('<?= $desired_nav ?>', <?= $pers["cod"] ?>, 'nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>')">
            <i class="<?= $icon ?>"></i><br/>
            <small><?= $label ?></small>
            <?php $userDetails->render_alert("status.$desired_nav." . $pers["cod"], "pull-right"); ?>
        </a>
    </li>
<?php } ?>

<style type="text/css">
    .bar-step {
        position: absolute;
        z-index: 1;
        font-size: 12px;
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
    }
</style>

<div class="panel-heading">
    Tripulação
</div>

<div class="panel-body">

    <?= ajuda("Visão Geral - Tripulação", "Aqui você visualiza as principais informações dos seus personagens e da sua 
    tripulação, como atributos,  recompensas, posições no Ranking, etc.") ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="link_bandeira" class="link_content2" id="status_bandeira" data-toggle="tooltip"
               data-placement="right" title="Clique para personalizar sua bandeira"><img
                        src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>"></a>
            <br>
            <h3><?= $userDetails->tripulacao["tripulacao"]; ?></h3>
        </div>
        <div class="panel-body">
            <div>
                <p>
                    <img src="Imagens/Ranking/Patentes/<?= $userDetails->tripulacao["faccao"] . "_" . get_patente_id($userDetails->tripulacao["reputacao"]) ?>.png">
                </p>
                <h3><?= get_patente_nome($userDetails->tripulacao["faccao"], $userDetails->tripulacao["reputacao"]) ?></h3>
                <h3> Poder de Batalha: <?= mascara_numeros_grandes($userDetails->tripulacao['poder']) ?></h3>
                <h3> Reputação nessa Era: <?= mascara_numeros_grandes($userDetails->tripulacao["reputacao"]) ?></h3>
                <p>A cada 3 meses a reputação é resetada para o início de uma nova Grande Era dos Piratas!</p>
            </div>
            <div class="row hidden-xs"><br>
                <?php $marks = get_patente_marks(); ?>
                <div class="col-xs-12">
                    <div class="progress">
                        <?php for ($id = 0; $id < 13; $id++): ?>
                            <div class="bar-step" style="left: <?= $marks[$id] / 36000 * 100 ?>%">
                                <div class="label-percent"><?= $marks[$id] ?></div>
                                <div class="label-line"></div>
                            </div>
                        <?php endfor; ?>
                        <div class="step-prorgress-bar progress-bar progress-bar-success"
                             style="width: <?= $userDetails->tripulacao["reputacao"] / 36000 * 100 ?>%;"></div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div style="position: relative;">
                        <?php $marks[] = 36000; ?>
                        <?php for ($id = 0; $id <= 13; $id++): ?>
                            <div class="bar-step"
                                 style="left: <?= ($id ? $marks[$id - 1] : 0) / 36000 * 100 ?>%;width: <?= ($marks[$id] - ($id ? $marks[$id - 1] : 0)) / 36000 * 100 ?>%;">
                                <img style="max-width: 30px; margin: auto"
                                     data-toggle="tooltip"
                                     title="<?= get_patente_nome($userDetails->tripulacao["faccao"], $marks[$id] - 1) ?>"
                                     src="Imagens/Ranking/Patentes/<?= $userDetails->tripulacao["faccao"] . "_" . $id ?>.png"/>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
            <br/>
            <br/>
            <br/>
            <br/>
            <br/>
            <div class=" row text-left">
                <div class="col-md-6">
                    <ul>
                        <li>Reputação nessa era: <?= $userDetails->tripulacao["reputacao"]; ?></li>
                        <li>Reputação nesse mês: <?= $userDetails->tripulacao["reputacao_mensal"]; ?></li>
                        <li>Vitórias: <?= $userDetails->tripulacao["vitorias"]; ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <?php
                    $trimestral = $connection->run("SELECT * FROM tb_ranking_reputacao WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_array();
                    $mensal = $connection->run("SELECT * FROM tb_ranking_reputacao_mensal WHERE id = ?", "i", $userDetails->tripulacao["id"])->fetch_array();
                    ?>
                    <ul>
                        <li>Ranking dessa
                            era: <?= isset($trimestral["posicao"]) ? $trimestral["posicao"] . "º" : "Você precisa participar de pelo menos 10 batalhas PvP durante essa Era para entrar para o Ranking"; ?></li>
                        <li>Ranking desse
                            mês: <?= isset($mensal["posicao"]) ? $mensal["posicao"] . "º" : "Você precisa participar de pelo menos 3 batalhas PvP durante esse mês para entrar para o Ranking"; ?></li>
                    </ul>
                </div>
            </div>
            <div>
                <h3>Nível de Batalha: <?= $userDetails->tripulacao["battle_lvl"] ?></h3>

                <div class="progress">
                    <div class="progress-bar progress-bar-warning"
                         style="width: <?= $userDetails->tripulacao["battle_points"] / PONTOS_POR_NIVEL_BATALHA * 100 ?>%">
                    </div>
                    <a>
                        Experiência de Batalha:
                        <?= $userDetails->tripulacao["battle_points"] . " / " . PONTOS_POR_NIVEL_BATALHA ?>
                    </a>
                </div>
                <p>
                    Você ganha Experiência de Batalha sempre que derrotar tripulantes adversários em batalhas PvP.
                </p>
                <?php if ($userDetails->tripulacao["battle_points"] >= PONTOS_POR_NIVEL_BATALHA): ?>
                    <button class="btn btn-success link_send" href="link_Geral/nivel_batalha.php">
                        Receber a Recompensa
                    </button>
                    <?= get_alert(); ?>
                <?php else: ?>
                    <p>
                        Você receberá uma recompensa surpresa quando conseguir Experiência de Batalha suficiente para
                        evoluir seu Nível de Batalha.
                    </p>
                    <p>
                        A cada 30 Níveis de Batalha você é recompensado com uma peça de Equipamento Preto, que é um dos
                        conjuntos de equipamentos mais fortes do jogo!
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div>
        <?php render_personagens_pills(null, "", function ($pers) {
            global $userDetails;
            $userDetails->render_alert("status." . $pers["cod"]);
        }); ?>
    </div>

    <div class="tab-content">

        <?php $titulos_compartilhados = $connection->run(
            "SELECT tit.cod_titulo, tit.nome, tit.nome_f, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
            INNER JOIN tb_personagens per ON pertit.cod = per.cod
            INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
            WHERE tit.compartilhavel = 1 AND per.id = ?",
            "i", array($userDetails->tripulacao["id"])
        )->fetch_all_array(); ?>

        <?php foreach ($userDetails->personagens as $index => $pers): ?>
            <?php render_personagem_panel_top($pers, $index) ?>

            <?php
            $titulos_bd = $connection->run(
                "SELECT tit.cod_titulo, tit.nome, tit.nome_f, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
                            INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
                            WHERE pertit.cod = ?", "i", $pers["cod"]
            )->fetch_all_array();
            $titulos = array();
            foreach ($titulos_bd as $titulo) {
                if ($pers["sexo"] == 1) {
                    $titulo["nome"] = $titulo["nome_f"];
                }
                $titulos[$titulo["cod_titulo"]] = $titulo;
            }
            foreach ($titulos_compartilhados as $titulo) {
                if ($pers["sexo"] == 1) {
                    $titulo["nome"] = $titulo["nome_f"];
                }
                $titulos[$titulo["cod_titulo"]] = $titulo;
            }
            $pers_titulo = FALSE;
            if ($pers["titulo"]) {
                foreach ($titulos as $titulo) {
                    if ($pers["titulo"] == $titulo["cod_titulo"]) {
                        $pers_titulo = $titulo["nome"];
                    }
                }
            }
            ?>

            <h3>
                <?= $pers["nome"]; ?><?= ($pers_titulo) ? " - " . $pers_titulo : "" ?>, Nível <?= $pers["lvl"]; ?>
            </h3>

            <?php $nav = isset($_GET["cod"]) && isset($_GET["nav"]) && $_GET["cod"] == $pers["cod"] ? $_GET["nav"] : "status"; ?>
            <ul class="nav nav-pills nav-justified">
                <?php render_nav("status", "Status", "fa fa-file-text", $nav, $pers) ?>
                <?php render_nav("customizacao", "Customização", "fa fa-male", $nav, $pers) ?>
                <?php render_nav("akuma", "Akuma no Mi", "glyphicon glyphicon-apple", $nav, $pers) ?>
                <?php render_nav("classe", "Classe", "fa fa-star-o", $nav, $pers) ?>
                <?php render_nav("maestria", "Maestria", "fa fa-superpowers", $nav, $pers) ?>
                <?php render_nav("profissao", "Profissão", "fa fa-gavel", $nav, $pers) ?>
                <?php render_nav("habilidades", "Habilidades", "fa fa-star", $nav, $pers) ?>
                <?php render_nav("equipamentos", "Equipamentos", "fa fa-shield", $nav, $pers) ?>
                <?php render_nav("wanted", $userDetails->tripulacao["faccao"] == FACCAO_PIRATA ? "Nível de Procurado" : "Honrarias", "fa fa-fire", $nav, $pers) ?>
            </ul>
            <br/>
            <div class="tab-content">
                <div id="nav-status-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "status" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-habilidades-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "habilidades" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-classe-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "classe" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-maestria-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "maestria" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-profissao-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "profissao" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-customizacao-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "customizacao" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-wanted-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "wanted" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-akuma-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "akuma" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>

                <div id="nav-equipamentos-<?= $pers["cod"] ?>"
                     class="tab-pane <?= $nav == "equipamentos" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif"/>
                </div>
            </div>
            <?php render_personagem_panel_bottom() ?>
        <?php endforeach; ?>
    </div>
</div>

<script type="text/javascript">
    $(function () {

        <?php $cod = isset($_GET["cod"]) ? $_GET["cod"] : $userDetails->capitao["cod"]; ?>
        <?php $nav = isset($_GET["nav"]) ? $_GET["nav"] : "status"; ?>

        $('#nav-<?= $nav ?>-<?= $cod ?>-seletor a').click();

        $('.personagem-pill').on('click', function () {
            var queryParams = getQueryParams();
            var nav = queryParams.nav ? queryParams.nav : 'status';
            $('#nav-' + nav + '-' + $(this).data('cod') + '-seletor a').click();
        });
    });

    function loadNav(name, cod, elem) {
        loadSubSession('Personagem/status/' + name + '.php?cod=' + cod, '#' + elem);
    }
</script>

<div class="modal fade" id="modal-trocar-personagem">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4>Selecione um novo personagem</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" id="pers-trocar-personagem">
                    <input type="hidden" id="img-trocar-personagem">
                    <input type="hidden" id="tipo-trocar-personagem">
                    <div class="col-md-4">
                        <img id="img_capitao" src="Imagens/Personagens/Big/0000.png" width="200px" height="300px">
                    </div>
                    <div class="col-md-8">
                        <div class="sel-personagem">
                            <?php for ($x = 1; $x <= PERSONAGENS_MAX; $x++): ?>
                                <img class="capitao-selectable-img" data-img="<?= sprintf("%04d", $x) ?>"
                                     src="Imagens/Personagens/Icons/<?= sprintf("%04d", $x) ?>(0).jpg" width="50px">
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    Cancelar
                </button>
                <button class="trocar-personagem-confirm btn btn-success" data-dismiss="modal">
                    Confirmar
                </button>
            </div>
        </div>
    </div>
</div>
