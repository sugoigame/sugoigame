<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();

$pers = $protector->get_tripulante_or_exit("cod");
?>
<?php function get_atributo_max($pers)
{
    $max = 1;
    for ($x = 1; $x <= 8; $x++) {
        $abr = nome_atributo_tabela($x);
        $total = round($pers[$abr]);
        if ($total > $max) {
            $max = $total;
        }
    }

    return $max;
} ?>

<?php function row_atributo($abr, $nome, $img, $desc, $pers, $bonus)
{ ?>
    <?php $total = $pers[$abr]; ?>
    <?php $max = get_atributo_max($pers); ?>
    <div class="col-xs-3 mb attribute-box" data-toggle="tooltip" data-html="true" data-placement="bottom"
        data-container="body" title="<strong><?= $nome ?></strong><br/> <?= $desc ?>">
        <div class="attribute-progress-bar-bg" style="height: 100%"> </div>
        <div class="attribute-progress-bar" id="atribute-progress-<?= $abr ?>-<?= $pers["cod"] ?>"
            style="height: <?= $total / $max * 100 ?>%"></div>
        <div class="pt1">
            <div id="<?= $abr . '_' . $pers["cod"]; ?>">
                <?= $pers[$abr]; ?>
            </div>
            <div class="text-center">
                <small>
                    <?= "+" . ($bonus[$abr] ? $bonus[$abr] : "0"); ?>
                </small>
            </div>
        </div>

        <img src="Imagens/Icones/<?= $img ?>.png" width="30px" style="max-width: 100%" class="atributo-icon" />
    </div>
<?php } ?>

<style type="text/css">
    .block-atributo-col .progress {
        height: 20px;
        margin: 10px 0 0 0;
    }

    .block-atributo-col {
        padding: 2px;
    }

    .block-atributo {
        position: relative;
        border: 2px solid #000;
        width: 100%;
    }

    .block-atributo.disabled {
        opacity: 1;
        cursor: auto;
    }

    .block-atributo.btn-default:hover {
        background: #303030;
    }

    .block-atributo .bg-atributo {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(150, 150, 150, 0.4);
        z-index: 0;
    }

    .block-atributo .block-atributo-text {
        position: relative;
        z-index: 1;
    }
</style>

<script type="text/javascript">
    function add_atributo(atributo, cod, nome) {
        var atributoqnt = document.getElementById("pts_" + cod).innerHTML;
        var atributo_qnt = parseFloat(atributoqnt);

        if (atributo_qnt > 0) {
            bootbox.prompt({
                title: 'Você tem ' + atributo_qnt + ' pontos para distribuir entre os 8 diferentes atributos. ' +
                    'Quantos pontos você gostaria de aplicar em ' + nome + '?',
                inputType: 'number',
                callback: function (quant) {
                    quant = parseFloat(quant);
                    if (!quant || isNaN(quant) || quant > atributo_qnt || quant <= 0) {
                        bootbox.alert('Quantidade inválida.');
                        return;
                    }
                    sendGet('Personagem/adiciona_atributo.php?atributo=' + atributo + "&cod=" + cod + "&quant=" + quant)
                }
            });

        }
        else if (atributo_qnt <= 0) {
            var theClass = ".add_" + cod;
            $(theClass).css("display", "none")
        }
    }
</script>

<?php $bonus = calc_bonus($pers); ?>
<div class="row pt1">
    <div class="col-xs-5">
        <div>
            <?= big_pers_skin($pers["img"], $pers["skin_c"], $pers["borda"], "", 'width="60%"') ?>
            <?php if ($pers["xp"] >= $pers["xp_max"] && $pers["lvl"] < 50) : ?>
                <button id="status_evoluir" href="link_Personagem/personagem_evoluir.php?cod=<?= $pers["cod"] ?>"
                    class="link_send btn btn-info">
                    Evoluir <i class="fa fa-arrow-up"></i>
                </button>
            <?php endif; ?>
            <br />
        </div>
    </div>

    <div class="col-xs-3">
        <div class="row mx0">
            <?php for ($i = 1; $i <= 8; $i++) : ?>
                <?php row_atributo(
                    nome_atributo_tabela($i),
                    nome_atributo($i),
                    nome_atributo_img($i),
                    descricao_atributo($i),
                    $pers, $bonus); ?>
            <?php endfor; ?>
        </div>
        <?php render_personagem_status_bars($pers); ?>
    </div>
    <div class="col-xs-4">
        <!--- Menu de modo de criacao de atributos -->
        <ul class="nav nav-pills nav-justified mb">
            <li class="<?= ! isset($_GET["buildtype"]) || $_GET["buildtype"] == "simples" ? "active" : "" ?>">
                <a data-toggle="tab" onclick="setQueryParam('buildtype','simples');"
                    href="#atributos-simples-<?= $pers["cod"] ?>">Simples</a>
            </li>
            <li class="<?= $_GET["buildtype"] == "intermediaria" ? "active" : "" ?>">
                <a data-toggle="tab" onclick="setQueryParam('buildtype','intermediaria');"
                    href="#atributos-intermediarios-<?= $pers["cod"] ?>">Intermediário</a>
            </li>
            <li class="<?= $_GET["buildtype"] == "avancada" ? "active" : "" ?>">
                <a data-toggle="tab" onclick="setQueryParam('buildtype','avancada');"
                    href="#atributos-avancados-<?= $pers["cod"] ?>">Avançado</a>
            </li>
        </ul>
        <?php if ($pers["pts"]) : ?>
            <div>
                Pontos a distribuir:
                <b id="pts_<?= $pers["cod"] ?>">
                    <?= $pers["pts"] ?>
                </b>
                <?php $userDetails->render_alert("trip_sem_distribuir_atributo." . $pers["cod"]) ?>
            </div>
        <?php endif; ?>

        <div class="overflow-auto h-42vh">
            <div class="tab-content">
                <!--- Simples -->
                <div class="tab-pane <?= ! isset($_GET["buildtype"]) || $_GET["buildtype"] == "simples" ? "active" : "" ?>"
                    id="atributos-simples-<?= $pers["cod"] ?>">
                    <p class="text-left">
                        No modo simples você pode escolher uma das seguintes builds:
                    </p>
                    <?php for ($i = 1; $i <= 8; $i++) : ?>
                        <div class="text-left" style="margin: 0.5rem 0; font-size: 1rem;">
                            <a class="link_send"
                                href="link_Personagem/atributo_build_simples.php?cod=<?= $pers["cod"] ?>&atr=<?= $i ?>"
                                v-on:click="buildAutomatica(atrKey)">
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png" width="25vw"
                                    style="max-width: 100%;" class="atributo-icon" />
                                Usar build baseada em
                                <?= nome_atributo($i) ?>
                            </a>
                        </div>
                    <?php endfor; ?>
                </div>

                <!--- Intermediário -->
                <div class="tab-pane <?= $_GET["buildtype"] == "intermediaria" ? "active" : "" ?>"
                    id="atributos-intermediarios-<?= $pers["cod"] ?>">
                    <p class="text-left">
                        No modo intermediário você pode escolher seus três atributos
                        preferidos e a build será criada automaticamente:
                    </p>
                    <p class="text-left">
                        Selecione o atributo primário:
                    </p>
                    <div class="mb">
                        <?php for ($i = 1; $i <= 8; $i++) : ?>
                            <div class="build-intermediaria-atr atr-1" data-atr="<?= $i ?>"
                                onclick="$('.build-intermediaria-atr.atr-1').removeClass('active');$(this).addClass('active');">
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png" width="20vw"
                                    class="atributo-icon" />
                            </div>
                        <?php endfor; ?>
                    </div>
                    <p class="text-left">
                        Selecione o atributo secundário:
                    </p>
                    <div class="mb">
                        <?php for ($i = 1; $i <= 8; $i++) : ?>
                            <div class="build-intermediaria-atr atr-2" data-atr="<?= $i ?>"
                                onclick="$('.build-intermediaria-atr.atr-2').removeClass('active');$(this).addClass('active');">
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png" width="20vw"
                                    class="atributo-icon" />
                            </div>
                        <?php endfor; ?>
                    </div>
                    <p class="text-left">
                        Selecione o atributo terciário:
                    </p>
                    <div class="mb">
                        <?php for ($i = 1; $i <= 8; $i++) : ?>
                            <div class="build-intermediaria-atr atr-3" data-atr="<?= $i ?>"
                                onclick="$('.build-intermediaria-atr.atr-3').removeClass('active');$(this).addClass('active');">
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png" width="20vw"
                                    class="atributo-icon" />
                            </div>
                        <?php endfor; ?>
                    </div>

                    <button class="btn btn-success" data-cod="<?= $pers["cod"] ?>"
                        onclick="gerarBuildIntermediaria(this)">
                        Gerar Build
                    </button>
                </div>

                <!--- Avançado -->
                <div class="tab-pane <?= $_GET["buildtype"] == "avancada" ? "active" : "" ?>"
                    id="atributos-avancados-<?= $pers["cod"] ?>">
                    <p class="text-left">
                        No modo avançado você tem liberdade total para escolher seus
                        atributos:
                    </p>

                    <input id="atributos-total-<?= $pers["cod"] ?>" type="hidden"
                        value="<?= get_total_atributos($pers) ?>" />
                    <?php for ($i = 1; $i <= 8; $i++) : ?>
                        <div class="text-left">
                            <div style="display: flex; align-items: center; margin-bottom: 0.8em;">
                                <img src="Imagens/Icones/<?= nome_atributo_img($i) ?>.png" width="25vw" height="25vw"
                                    class="atributo-icon" style="margin-right: 1em;" />
                                <div style="width: 100%">
                                    <input data-atr="<?= $i ?>" data-cod="<?= $pers["cod"] ?>" class="atr-input atributo"
                                        type="range" step="1" min="1" value="<?= $pers[nome_atributo_tabela($i)] ?>"
                                        oninput="$(this.nextElementSibling).html($(this).val())"
                                        max="<?= $pers[nome_atributo_tabela($i)] + $pers["pts"] ?>">
                                </div>
                                <span style="font-size: 1.1rem; margin-left: 0.5em;">
                                    <?= $pers[nome_atributo_tabela($i)] ?>
                                </span>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
        <div>
            <?php if ($pers["lvl"] < $userDetails->capitao["lvl"] && $pers["xp"] < $pers["xp_max"]) : ?>
                <p>
                    <button href="Vip/personagem_xp_para_evoluir.php?cod=<?= $pers["cod"] ?>&tipo=gold"
                        data-question="Deseja adquirir a experiência restante para que este tripulante evolua um nível?"
                        class="link_confirm btn btn-info" <?= $userDetails->conta["gold"] < PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO ? "disabled" : "" ?>>
                        <?= PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO ?>
                        <img src="Imagens/Icones/Gold.png"> <br />
                        Evoluir um nível
                    </button>
                </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    function gerarBuildIntermediaria(btn) {
        const atr1 = $('.build-intermediaria-atr.atr-1.active').data('atr');
        const atr2 = $('.build-intermediaria-atr.atr-2.active').data('atr');
        const atr3 = $('.build-intermediaria-atr.atr-3.active').data('atr');
        const cod = $(btn).data('cod');

        sendGet('Personagem/atributo_build_intermediaria.php?cod=' + cod + '&atr1=' + atr1 + '&atr2=' + atr2 + '&atr3=' + atr3);
    }

    $('.atr-input.atributo').on('change', function () {
        const atr = $(this).data('atr');
        const cod = $(this).data('cod');
        const quant = $(this).val();

        sendGet('Personagem/atributo_build_avancada.php?cod=' + cod + '&atr=' + atr + '&quant=' + quant);
    });
</script>
