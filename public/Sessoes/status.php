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


<div class="panel-body">
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

        <?php foreach ($userDetails->personagens as $index => $pers) : ?>
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
                <?= $pers["nome"]; ?>
                <?= ($pers_titulo) ? " - " . $pers_titulo : "" ?>, Nível
                <?= $pers["lvl"]; ?>
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
            <br />
            <div class="tab-content">
                <div id="nav-status-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "status" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-habilidades-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "habilidades" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-classe-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "classe" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-maestria-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "maestria" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-profissao-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "profissao" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-customizacao-<?= $pers["cod"] ?>"
                    class="tab-pane <?= $nav == "customizacao" ? "active" : "" ?>">
                    <img src="Imagens/carregando.gif" />
                </div>

                <div id="nav-wanted-<?= $pers["cod"] ?>" class="tab-pane <?= $nav == "wanted" ? "active" : "" ?>">
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
