<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();

$pers = $protector->get_tripulante_or_exit("cod");
?>
<div class="row pt1">
    <div class="col-xs-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                Nível de Haki
                <?= $pers["haki_lvl"] ?> /
                <?= HAKI_LVL_MAX ?>
                <?= ajuda_tooltip("Os tripulantes podem usar os pontos de experiência para evoluir seu Haki."); ?>
            </div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar progress-bar-info"
                        style="width: <?= $pers["haki_xp"] / $pers["haki_xp_max"] * 100 ?>%">
                        <span>
                            Treino de Haki:
                            <?= mascara_numeros_grandes($pers["haki_xp"]) . "/" . mascara_numeros_grandes($pers["haki_xp_max"]) ?>
                        </span>
                    </div>
                </div>
                <?php render_personagem_xp_bar($pers) ?>
                <?php if ($pers['xp'] > 0 && $pers["haki_lvl"] < HAKI_LVL_MAX) : ?>
                    <?php $max = min($pers['xp'], $pers["haki_xp_max"] - $pers["haki_xp"]); ?>
                    <form class="ajax_form form-inline" action="Haki/trocar_xp" method="post"
                        data-question="Deseja Trocar XP?">
                        <input type="hidden" name="cod" value="<?= $pers['cod'] ?>">
                        <label>Utilize sua experiência para evoluir o Haki:</label>
                        <input id="select-quant-treino" class="" name="quant"
                            oninput="$('#haki-treino-<?= $pers['cod'] ?>').html(mascaraBerries($(this).val()))" type="range"
                            max="<?= $max ?>" min="1" value="<?= $max ?>" required />

                        <div>
                            <button class="btn btn-success">
                                Treinar <span id="haki-treino-<?= $pers["cod"] ?>">
                                    <?= mascara_numeros_grandes($max) ?>
                                </span>
                            </button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($pers["cod"] == $userDetails->capitao["cod"] && isset($COD_HAOSHOKU_LVL[$pers["haki_hdr"] + 1])) : ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php $hdr = $connection->run("SELECT * FROM tb_skil_atk WHERE cod_skil = ?",
                        "i", array($COD_HAOSHOKU_LVL[$pers["haki_hdr"] + 1])); ?>
                    <p>
                        O seu capitão pode optar por evoluir um tipo especial de Haki: o Haki do Rei.<br />
                        Esse Haki concede uma habilidade que se torna mais forte a cada ponto aplicado.
                    </p>
                    <div class="text-left">
                        <div>No próximo nível, o Haoshoku será uma habilidade com os seguintes efeitos:</div>
                        <?php render_skill_efeitos($hdr->fetch_array()); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col-xs-8">
        <div class="panel panel-default">
            <div class="panel-heading">
                Pontos a distribuir:
                <strong>
                    <?= $pers["haki_pts"] ?>
                </strong>
                <?= ajuda_tooltip("A cada nível evoluído, o tripulante pode distribuir 1 ponto no Haki de sua escolha."); ?>
            </div>
            <div class="panel-body">
                <div>
                    Haki da Observação:
                    <?= ajuda_tooltip("Cada ponto aumenta em 1% de chance do tripulante se esquivar de um ataque."); ?>
                </div>
                <?php render_personagem_mantra_bar($pers, true, "m0"); ?>
                <p>
                    <input data-haki="haki_esq" data-cod="<?= $pers["cod"] ?>" class="atr-input haki input-success"
                        type="range" step="1" min="0" value="<?= $pers["haki_esq"] ?>"
                        oninput="$(this.nextElementSibling).html($(this).val())"
                        max="<?= min(MAX_POINTS_MANTRA, $pers["haki_esq"] + $pers["haki_pts"]) ?>">
                    <span>
                        <?= $pers["haki_esq"] ?>
                    </span>
                </p>

                <div>
                    Haki do Armamento:
                    <?= ajuda_tooltip("Cada ponto aumenta em 1% a chance do tripulante bloquear um ataque e em 1% a chance de acertar um ataque crítico."); ?>
                </div>
                <?php render_personagem_armamento_bar($pers, true, "m0"); ?>
                <p>
                    <input data-haki="haki_cri" data-cod="<?= $pers["cod"] ?>" class="atr-input haki input-danger"
                        type="range" step="1" min="0" value="<?= $pers["haki_cri"] ?>"
                        oninput="$(this.nextElementSibling).html($(this).val())"
                        max="<?= min(MAX_POINTS_ARMAMENTO, $pers["haki_cri"] + $pers["haki_pts"]) ?>">
                    <span>
                        <?= $pers["haki_cri"] ?>
                    </span>
                </p>

                <div>
                    Haki Avançado:
                    <?= ajuda_tooltip("Cada ponto aumenta o Ataque do tripulante em 2."); ?>
                </div>
                <?php render_personagem_haki_avancado_bar($pers, true, "m0"); ?>
                <p>
                    <input data-haki="haki_blo" data-cod="<?= $pers["cod"] ?>" class="atr-input haki input-warning"
                        type="range" step="1" min="0" value="<?= $pers["haki_blo"] ?>"
                        oninput="$(this.nextElementSibling).html($(this).val())"
                        max="<?= min(MAX_POINTS_HAKI_AVANCADO, $pers["haki_blo"] + $pers["haki_pts"]) ?>">
                    <span>
                        <?= $pers["haki_blo"] ?>
                    </span>
                </p>

                <?php if ($pers["cod"] == $userDetails->capitao["cod"]) : ?>
                    <div>
                        Haki do Rei:
                        <?= ajuda_tooltip("Cada ponto fortalece a habilidade do Haki do Rei."); ?>
                    </div>
                    <?php render_personagem_hdr_bar($pers, true, "m0"); ?>
                    <div>
                        <input data-haki="haki_hdr" data-cod="<?= $pers["cod"] ?>" class="atr-input haki" type="range"
                            step="1" min="0" value="<?= $pers["haki_hdr"] ?>"
                            oninput="$(this.nextElementSibling).html($(this).val())"
                            max="<?= min(MAX_POINTS_HDR, $pers["haki_hdr"] + $pers["haki_pts"]) ?>">
                        <span>
                            <?= $pers["haki_hdr"] ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.atr-input.haki').on('change', function () {
        const tipo = $(this).data('haki');
        const cod = $(this).data('cod');
        const quant = $(this).val();

        sendGet('Haki/haki_distribuir.php?cod=' + cod + '&tipo=' + tipo + '&quant=' + quant);
    });
</script>
