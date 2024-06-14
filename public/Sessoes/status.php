<?php function render_nav($desired_nav, $label, $icon, $nav, $pers)
{ ?>
    <?php global $userDetails; ?>
    <li class="<?= $nav == $desired_nav ? "active" : "" ?>" onclick="setQueryParam('nav','<?= $desired_nav ?>')"
        id="nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>-seletor">
        <a href="#nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>" data-toggle="tab"
            onclick="loadNav('<?= $desired_nav ?>', <?= $pers["cod"] ?>, 'nav-<?= $desired_nav ?>-<?= $pers["cod"] ?>')">
            <i class="<?= $icon ?>"></i><br />
            <small>
                <?= $label ?>
            </small>
            <?php $userDetails->render_alert("status.$desired_nav." . $pers["cod"], "pull-right"); ?>
        </a>
    </li>
<?php } ?>
<style type="text/css">
    .tripulantes-layout>.nav-pills {
        display: none;
    }

    .tripulante_quadro_info {
        display: none !important;
    }

    .tripulantes-layout>.tab-content .tripulantes-body {
        display: flex;
        flex-direction: row;
        position: relative;
        height: 65vh;
        overflow: auto;
    }

    .tripulantes-layout>.tab-content .tripulantes-body>.nav-pills {
        max-width: 10vh;
        margin: 0;
        margin-right: 1em;
        position: sticky;
        top: 0;
    }

    .tripulantes-layout>.tab-content .tripulantes-body>.nav-pills li {
        display: inline-block;
        width: 10vh;
        margin: 0;
    }

    .tripulantes-layout>.tab-content .tripulantes-body>.nav-pills li a {
        margin: 0;
        padding: 2px;
    }

    .tripulantes-layout>.tab-content .tripulantes-body .tab-content {
        width: 100%;
        padding-left: 2em;
        padding-right: 2em;
    }

    .attribute-box {
        position: relative;
        padding: 0 1vw 1vw;
    }

    .attribute-progress-bar-bg {
        position: absolute;
        bottom: 0;
        left: 5%;
        width: 90%;
        background: rgba(0, 0, 0, 0.1);
        border: 5px solid transparent;
        border-radius: 5px;
    }

    .attribute-progress-bar {
        position: absolute;
        bottom: 0;
        left: 5%;
        width: 90%;
        background: rgba(0, 0, 0, 0.2);
        border: 5px solid transparent;
        border-radius: 5px;
    }

    .build-intermediaria-atr {
        margin: 0px;
        cursor: pointer;
        display: inline-block;
        border: 1px solid transparent;
        padding: 5px;
    }

    .build-intermediaria-atr.active {
        border: 1px solid #cccccc;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        padding: 5px;
        background: rgba(0, 0, 0, 0.5);
    }

    .atr-input {
        display: inline-block !important;
        width: 70% !important;
    }

    .icon-pers-skin {
        width: 4vw;
    }
</style>

<div class="panel-body">
    <div class="tripulantes-layout">
        <?php render_personagens_pills(null, "", function ($pers) {
            global $userDetails;
            $userDetails->render_alert("status." . $pers["cod"]);
        }); ?>

        <div class="tab-content">
            <?php $titulos_compartilhados = $connection->run(
                "SELECT tit.cod_titulo, tit.nome, tit.nome_f, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit
    INNER JOIN tb_personagens per ON pertit.cod = per.cod
    INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
    WHERE tit.compartilhavel = 1 AND per.id = ?",
                "i", array($userDetails->tripulacao["id"])
            )->fetch_all_array(); ?>

            <?php foreach ($userDetails->personagens as $index => $pers) : ?>
                <?php render_personagem_panel_top($pers, $index) ?>
                <div class="tripulantes-body">
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


                    <?php $nav = isset($_GET["cod"]) && isset($_GET["nav"]) && $_GET["cod"] == $pers["cod"] ? $_GET["nav"] : "status"; ?>
                    <ul class="nav nav-pills nav-justified subsessions">
                        <?php render_nav("status", "Status", "fa fa-file-text", $nav, $pers) ?>
                        <?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_HAKI)) : ?>
                            <?php render_nav("haki", "Haki", "fa fa-certificate", $nav, $pers) ?>
                        <?php endif; ?>
                        <?php render_nav("customizacao", "Customização", "fa fa-male", $nav, $pers) ?>
                        <?php render_nav("akuma", "Akuma no Mi", "glyphicon glyphicon-apple", $nav, $pers) ?>
                        <?php render_nav("classe", "Classe", "fa fa-star-o", $nav, $pers) ?>
                        <?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_PROFISSOES)) : ?>
                            <?php render_nav("profissao", "Profissão", "fa fa-gavel", $nav, $pers) ?>
                        <?php endif; ?>
                        <?php render_nav("habilidades", "Habilidades", "fa fa-star", $nav, $pers) ?>
                        <?php if ($userDetails->is_sistema_desbloqueado(SISTEMA_EQUIPAMENTOS)) : ?>
                            <?php render_nav("equipamentos", "Equipamentos", "fa fa-shield", $nav, $pers) ?>
                        <?php endif; ?>
                        <?php render_nav("wanted", "Nível de Procurado", "fa fa-fire", $nav, $pers) ?>
                    </ul>

                    <div class="w-100">
                        <div class="panel panel-default" id="header-info">
                            <strong>
                                <?= icon_pers_skin($pers["img"], $pers["skin_r"], "header-skin") ?>
                                <?= $pers["nome"]; ?>
                                <?= ($pers_titulo) ? " - " . $pers_titulo : "" ?> -
                                <?= nome_classe($pers["classe"]) ?>
                                <?= nome_prof($pers["profissao"]) ?> Nível
                                <?= $pers["lvl"]; ?>
                                - Recompensa de
                                <?= mascara_berries(calc_recompensa($pers["fama_ameaca"])); ?>
                            </strong>
                        </div>
                        <div class="tab-content">
                            <div id="nav-status-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "status" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-haki-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "haki" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-habilidades-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "habilidades" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-classe-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "classe" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-maestria-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "maestria" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-profissao-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "profissao" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-customizacao-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "customizacao" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-wanted-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "wanted" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-akuma-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "akuma" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>

                            <div id="nav-equipamentos-<?= $pers["cod"] ?>"
                                class="tab-pane <?= $nav == "equipamentos" ? "active" : "" ?>">
                                <img src="Imagens/carregando.gif" />
                            </div>
                        </div>
                    </div>
                </div>
                <?php render_personagem_panel_bottom() ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        <?php $cod = isset($_GET["cod"]) ? $_GET["cod"] : $userDetails->capitao["cod"]; ?>
        <?php $nav = isset($_GET["nav"]) ? $_GET["nav"] : "status"; ?>

        loadNavAndShowTab('<?= $nav ?>', <?= $cod ?>);

        $('.tripulante_quadro').on('click', function (e) {
            e.preventDefault();
            const queryParams = getQueryParams();
            const nav = queryParams.nav ? queryParams.nav : 'status';
            const cod = queryParams.cod ? queryParams.cod : <?= $userDetails->capitao["cod"]; ?>;
            loadNavAndShowTab(nav, cod);
        });
    });

    function loadNavAndShowTab(nav, cod) {
        const elem = 'nav-' + nav + '-' + cod;
        loadNav(nav, cod, elem);
        $('a[href="#personagem-' + cod + '"]').tab('show');
        $('a[href="#' + elem + '"]').tab('show');
    }

    function loadNav(name, cod, elem) {
        const params = getQueryParams();
        loadSubSession('Personagem/status/' + name + '.php?cod=' + cod + (params.buildtype ? '&buildtype=' + params.buildtype : ''), '#' + elem);
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
                            <?php for ($x = 1; $x <= PERSONAGENS_MAX; $x++) : ?>
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
