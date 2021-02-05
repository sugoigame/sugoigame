<?php
include "../../../Includes/conectdb.php";
$protector->need_tripulacao();
$pers_cod = $protector->get_number_or_exit("cod");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}
?>
<?php function get_atributo_max($pers, $bonus) {
    $max = 1;
    for ($x = 1; $x <= 8; $x++) {
        $abr = nome_atributo_tabela($x);
        $total = round($pers[$abr] + $bonus[$abr]);
        if ($total > $max) {
            $max = $total;
        }
    }

    return $max;
} ?>

<?php function row_atributo($abr, $nome, $img, $desc, $pers, $bonus) { ?>
    <?php $total = round($pers[$abr] + $bonus[$abr]); ?>
    <?php $max = get_atributo_max($pers, $bonus); ?>
    <div class="block-atributo-col col-md-3 col-xs-6" data-toggle="tooltip" data-html="true"
         data-placement="bottom" title="<strong><?= $nome ?></strong><br/> <?= $desc ?>">
        <div id="<?= $abr ?>_block_<?= $pers["cod"]; ?>"
             class="block-atributo btn btn-default <?= $pers["pts"] <= 0 ? "disabled" : "" ?>"
            <?= $pers["pts"] > 0 ? "onclick=\"add_atributo('" . $abr . "', '" . $pers["cod"] . "', '" . $nome . "')\"" : "" ?>>
            <div class="bg-atributo" style="height: <?= $total / $max * 100 ?>%;">

            </div>
            <div class="block-atributo-text">
                <h3 id="<?= $abr ?>_total_<?= $pers["cod"]; ?>">
                    <?= $total; ?>
                </h3>
                <p>
                <span id="<?= $abr . '_' . $pers["cod"]; ?>">
                    <?= $pers[$abr]; ?>
                </span>
                    <span class="text-center">
                    <?= "+" . $bonus[$abr]; ?>
                </span>
                </p>
                <div>
                    <img width="45px" src="Imagens/Icones/<?= $img ?>.png">
                </div>
                <div class="visible-xs visible-sm">
                    <?= $nome ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?php function render_bonus_excelencia($bonus) { ?>
    <?php foreach ($bonus as $atr => $quant): ?>
        <div class="<?= $quant ? "text-success" : "" ?>">
            <?php if ($atr == "hp_max"): ?>
                Vida máxima
            <?php elseif ($atr == "mp_max"): ?>
                Energia máxima
            <?php else: ?>
                <img src="Imagens/Icones/<?= nome_atributo_img(cod_atributo_tabela($atr)) ?>.png"
                     width="25px"
                     data-toggle="tooltip"
                     data-placement="bottom"
                     title="<?= nome_atributo(cod_atributo_tabela($atr)) ?>">
            <?php endif; ?>
            + <?= $quant ?>
        </div>
    <?php endforeach; ?>
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
        background: rgba(100, 100, 100, 0.4);
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

<div class="row">
    <div class="col-sm-2 hidden-xs">
        <?= big_pers_skin($pers["img"], $pers["skin_c"], $pers["borda"], "", 'width="100%"') ?>
    </div>
    <div class="col-sm-10">
        <div class="panel panel-default">
            <div class="panel-body">
                <?php render_personagem_status_bars($pers); ?>

                <?php if ($pers["xp"] >= $pers["xp_max"] AND $pers["lvl"] < 50) : ?>
                    <p>
                        <button id="status_evoluir"
                                href="link_Personagem/personagem_evoluir.php?cod=<?= $pers["cod"] ?>"
                                class="link_send btn btn-info">
                            Evoluir <i class="fa fa-arrow-up"></i>
                        </button>
                    </p>
                <?php endif; ?>

                <?php if ($pers["lvl"] < $userDetails->capitao["lvl"] && $pers["xp"] < $pers["xp_max"]) : ?>
                    <p>
                        <button href="Vip/personagem_xp_para_evoluir.php?cod=<?= $pers["cod"] ?>&tipo=gold"
                                data-question="Deseja adquirir a experiência restante para que este tripulante evolua um nível?"
                                class="link_confirm btn btn-info"
                            <?= $userDetails->conta["gold"] < PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO ? "disabled" : "" ?>>
                            <?= PRECO_MODIFICADOR_RECRUTAR_LVL_ALTO ?>
                            <img src="Imagens/Icones/Gold.png"> <br/>
                            Adquirir XP para evoluir um nível
                        </button>
                        <button href="Vip/personagem_xp_para_evoluir.php?cod=<?= $pers["cod"] ?>&tipo=dobrao"
                                data-question="Deseja adquirir a experiência restante para que este tripulante evolua um nível?"
                                class="link_confirm btn btn-info"
                            <?= $userDetails->conta["dobroes"] < ceil(PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO) ? "disabled" : "" ?>>
                            <?= ceil(PRECO_MODIFICADOR_DOBRAO_RECRUTAR_LVL_ALTO) ?>
                            <img src="Imagens/Icones/Dobrao.png"> <br/>
                            Adquirir XP para evoluir um nível
                        </button>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Atributtos
            </div>
            <div class="panel-body">
                <?php $bonus = calc_bonus($pers); ?>
                <?php if ($pers["pts"]) : ?>
                    <h4>
                        Pontos a distribuir:
                        <b id="pts_<?= $pers["cod"] ?>"><?= $pers["pts"] ?></b>
                        <?php $userDetails->render_alert("trip_sem_distribuir_atributo." . $pers["cod"]) ?>
                    </h4>
                <?php endif; ?>
                <div class="row">
                    <?php row_atributo(
                        "atk",
                        "Ataque",
                        "Ataque",
                        "Cada ponto aumenta o dano causado pelo personagem em 10.",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "def",
                        "Defesa",
                        "Defesa",
                        "Cada ponto diminui o dano sofrido pelo personagem em 10.<br><b>obs: A defesa só absorve dano causado por pontos de ataque, dano de habilidade passam despercebidos pela defesa</b>",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "pre",
                        "Precisão",
                        "Precisao",
                        "Cada ponto reduz a chance do inimigo se esquivar ou bloquear seu ataque em 1%",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "agl",
                        "Agilidade",
                        "Agilidade",
                        "Cada ponto aumenta sua chance de se esquivar do ataque inimigo em 1%.<br><b>obs: A porcentagem de chance máxima de se esquivar é de 50%;</b>",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "res",
                        "Resistência",
                        "Resistencia",
                        "Cada ponto aumenta sua chance de bloquear o ataque inimgo em 1% e a quantidade de dano absorvido em 1%.<br><b>obs:A porcentagem de chance máxima de bloqueio é de 50%, e a porcentagem máxima de dano absorvido é de 90%.</b>",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "con",
                        "Percepção",
                        "Conviccao",
                        "Cada ponto reduz a chance do inimigo te acertar um ataque crítico em 1% e o dano causado por ataques críticos em 1%.",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "dex",
                        "Destreza",
                        "Dextreza",
                        "Cada ponto aumenta sua chance de acertar um ataque crítico em 1% e o dano causado por ataques críticos em 1%.<br><b>obs: A porcentagem de chance máxima de acertar um ataque crítico é de 50%, e o dano máximo causado por ataque crítico é de 90%.</b>",
                        $pers, $bonus); ?>
                    <?php row_atributo(
                        "vit",
                        "Vitalidade",
                        "Vitalidade",
                        "Cada ponto aumenta seu HP em 30 pontos e sua Energia em 7 pontos.<br><b>obs: O bonus de HP e Energia ganho por acréximo de vitalidade por meio de itens ou habilidades só é calculado durante combates.</b>",
                        $pers, $bonus); ?>
                </div>
                <br/>
                <p>
                    <button class="btn btn-info link_confirm"
                            data-question="Resetar os atributos desse personagem?<br>obs: Todas habilidades de classe (exceto soco) serão removidas"
                            href="Vip/reset_atributos.php?cod=<?= $pers["cod"]; ?>&tipo=gold"
                        <?= $userDetails->conta["gold"] < PRECO_GOLD_RESET_ATRIBUTOS ? "disabled" : "" ?>>
                        <?= PRECO_GOLD_RESET_ATRIBUTOS ?> <img src="Imagens/Icones/Gold.png"> <br/>
                        Resetar Atributos
                    </button>
                    <button class="btn btn-info link_confirm"
                            data-question="Resetar os atributos desse personagem?<br>obs: Todas habilidades de classe (exceto soco) serão removidas"
                            href="Vip/reset_atributos.php?cod=<?= $pers["cod"]; ?>&tipo=dobrao"
                        <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_RESET_ATRIBUTOS ? "disabled" : "" ?>>
                        <?= PRECO_DOBRAO_RESET_ATRIBUTOS ?> <img src="Imagens/Icones/Dobrao.png">
                        <br/>
                        Resetar Atributos
                    </button>
                </p>
                <?php if ($userDetails->tripulacao["free_reset_atributos"]): ?>
                    <p>
                        <?= $userDetails->tripulacao["free_reset_atributos"] ?> Reset(s) Gratuito(s)<br/>
                        <button class="btn btn-info link_confirm"
                                data-question="Resetar os atributos desse personagem?<br>obs: Todas habilidades de classe (exceto soco) serão removidas"
                                href="Vip/reset_atributos.php?cod=<?= $pers["cod"]; ?>&tipo=free">
                            Resetar Atributos
                        </button>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Haki
            </div>
            <div class="panel-body">
                <h5>
                    Haki disponível:
                    <?= mascara_numeros_grandes($userDetails->tripulacao["haki_xp"]) ?>
                </h5>
                <h4>Nível de Haki <?= $pers["haki_lvl"] ?>/<?= HAKI_LVL_MAX ?></h4>

                <div class="progress">
                    <div class="progress-bar"
                         style="width: <?= $pers["haki_xp"] / $pers["haki_xp_max"] * 100 ?>%">
                        Pontos:<?= $pers["haki_xp"] . "/" . $pers["haki_xp_max"] ?>
                    </div>
                </div>
                <?php if ($userDetails->tripulacao["haki_xp"] && $pers["haki_lvl"] < HAKI_LVL_MAX): ?>
                    <form class="ajax_form form-inline" action="Haki/haki_treinar"
                          data-question="Deseja aplicar estes pontos de Haki para esse tripulante?"
                          method="post">
                        <input type="hidden" name="pers" value="<?= $pers["cod"] ?>">
                        <div class="form-group">
                            <label>Aplicar Haki:</label>
                            <input class="form-control" name="quant" type="number"
                                   max="<?= min($pers["haki_xp_max"] - $pers["haki_xp"], $userDetails->tripulacao["haki_xp"]) ?>"
                                   min="1"
                                   value="<?= min($pers["haki_xp_max"] - $pers["haki_xp"], $userDetails->tripulacao["haki_xp"]) ?>">
                        </div>
                        <button class="btn btn-success">
                            Aplicar
                        </button>
                    </form>
                <?php endif; ?>
                <br/>
                <?php if ($pers["haki_pts"]): ?>
                    <h4>
                        Pontos a distribuir: <?= $pers["haki_pts"] ?>
                        <?php $userDetails->render_alert("trip_sem_distribuir_haki." . $pers["cod"]) ?>
                    </h4>
                <?php endif; ?>
                <div class="block-atributo-col col-md-<?= $pers["cod"] == $userDetails->capitao["cod"] ? 4 : 6 ?>"
                     data-toggle="tooltip" data-html="true"
                     data-placement="bottom"
                     title="<strong>Mantra</strong><br/> Cada ponto em Mantra aumenta sua chance de esquiva em 1%">
                    <div class="block-atributo btn btn-default <?= $pers["haki_pts"] > 0 && $pers["haki_esq"] < MAX_POINTS_MANTRA ? "link_send" : "disabled" ?>"
                        <?= $pers["haki_pts"] > 0 && $pers["haki_esq"] < MAX_POINTS_MANTRA ? "href=\"link_Haki/haki_distribuir.php?pers=" . $pers["cod"] . "&tipo=1\"" : "" ?>>
                        <div>
                            <h3>
                                <?= $pers["haki_esq"]; ?>
                            </h3>
                            <div>
                                Mantra
                            </div>
                            <?php render_personagem_mantra_bar($pers, false); ?>
                        </div>
                    </div>
                </div>
                <div class="block-atributo-col col-md-<?= $pers["cod"] == $userDetails->capitao["cod"] ? 4 : 6 ?>"
                     data-toggle="tooltip" data-html="true"
                     data-placement="bottom"
                     title="<strong>Armamento</strong><br/> Cada ponto em Armamento aumenta sua chance de bloqueio em 1% e também sua chance de acerto crítico em 1%.">
                    <div class="block-atributo btn btn-default <?= $pers["haki_pts"] > 0 && $pers["haki_cri"] < MAX_POINTS_ARMAMENTO ? "link_send" : "disabled" ?>"
                        <?= $pers["haki_pts"] > 0 && $pers["haki_cri"] < MAX_POINTS_ARMAMENTO ? "href=\"link_Haki/haki_distribuir.php?pers=" . $pers["cod"] . "&tipo=2\"" : "" ?>>
                        <div>
                            <h3>
                                <?= $pers["haki_cri"]; ?>
                            </h3>
                            <div>
                                Armamento
                            </div>
                            <?php render_personagem_armamento_bar($pers, false); ?>
                        </div>
                    </div>
                </div>
                <?php if ($pers["cod"] == $userDetails->capitao["cod"]): ?>
                    <div class="block-atributo-col col-md-<?= $pers["cod"] == $userDetails->capitao["cod"] ? 4 : 6 ?>"
                         data-toggle="tooltip" data-html="true"
                         data-placement="bottom"
                         title="<strong>Haoshoku</strong><br/> O Haoshoku (ou Haki do Rei) é uma habilidade exclusiva do seu capitão que fica mais forte a cada nível evoluído.">
                        <div class="block-atributo btn btn-default <?= $pers["haki_pts"] > 0 && $pers["haki_hdr"] < MAX_POINTS_HDR ? "link_send" : "disabled" ?>"
                            <?= $pers["haki_pts"] > 0 && $pers["haki_hdr"] < MAX_POINTS_HDR ? "href=\"link_Haki/haki_distribuir.php?pers=" . $pers["cod"] . "&tipo=3\"" : "" ?>>
                            <div>
                                <h3>
                                    <?= $pers["haki_hdr"]; ?>
                                </h3>
                                <div>
                                    Haki do Rei
                                </div>
                                <?php render_personagem_hdr_bar($pers, false); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <p>
                    <button href="Vip/reset_haki.php?cod=<?= $pers["cod"]; ?>&tipo=gold"
                        <?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_HAKI ? "" : "disabled" ?>
                            data-question="Resetar pontos de haki desse personagem?"
                            class="bt_reset_haki link_confirm btn btn-info">
                        <?= PRECO_GOLD_RESET_HAKI ?>
                        <img src="Imagens/Icones/Gold.png" height="15px"/>
                        Redistribuir os Pontos
                    </button>
                    <button href="Vip/reset_haki.php?cod=<?= $pers["cod"]; ?>&tipo=dobrao"
                        <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_HAKI ? "" : "disabled" ?>
                            data-question="Resetar pontos de haki desse personagem?"
                            class="bt_reset_haki link_confirm btn btn-info">
                        <?= PRECO_DOBRAO_RESET_HAKI ?>
                        <img src="Imagens/Icones/Dobrao.png" height="15px"/>
                        Redistribuir os Pontos
                    </button>
                </p>

                <a class="link_content" href="./?ses=haki&cod=<?= $pers["cod"] ?>">
                    Ver todos os detalhes sobre o Haki
                </a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Classe
            </div>
            <div class="panel-body">
                <?php if (!$pers["classe"]): ?>
                    <p>
                        Este tripulante ainda não tem uma Classe de Combate
                    </p>
                    <div>
                        <h4>Espadachim</h4>
                        <p>Atributo principal: Ataque</p>
                        <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=1'
                                class="btn btn-success link_confirm"
                                data-question="Deseja mesmo se tornar um Espadachim?">
                            Se tornar um Espadachim
                        </button>
                    </div>
                    <div>
                        <h4>Lutador</h4>
                        <p>Atributo principal: Defesa</p>
                        <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=2'
                                class="btn btn-success link_confirm"
                                data-question="Deseja mesmo se tornar um Lutador?">
                            Se tornar um Lutador
                        </button>
                    </div>
                    <div>
                        <h4>Atirador</h4>
                        <p>Atributo principal: Precisão</p>
                        <button href='Academia/academia_aprender.php?cod=<?= $pers["cod"]; ?>&class=3'
                                class="btn btn-success link_confirm"
                                data-question="Deseja mesmo se tornar um Atirador?">
                            Se tornar um Atirador
                        </button>
                    </div>
                <?php else : ?>
                    <h4><?= nome_classe($pers["classe"]) ?></h4>
                    <h3>
                        <b>Score:</b> <?= $pers["classe_score"]; ?>
                    </h3>
                    <p>
                        <button class="link_confirm btn btn-info" <?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_CLASSE ? "" : "disabled" ?>
                                data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
                                href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=gold">
                            <?= PRECO_GOLD_RESET_CLASSE ?> <img src="Imagens/Icones/Gold.png"/>
                            Resetar Classe
                        </button>
                        <button class="link_confirm btn btn-info" <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_CLASSE ? "" : "disabled" ?>
                                data-question="Resetar a classe desse personagem permitirá que ele aprenda uma nova. Deseja continuar?"
                                href="Vip/reset_classe.php?cod=<?= $pers["cod"] ?>&tipo=dobrao">
                            <?= PRECO_DOBRAO_RESET_CLASSE ?> <img src="Imagens/Icones/Dobrao.png"/>
                            Resetar Classe
                        </button>
                    </p>
                <?php endif; ?>
                <?php if ($userDetails->in_ilha): ?>
                    <a class="link_content" href="./?ses=academia&cod=<?= $pers["cod"] ?>">
                        Ir para a Academia
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                Profissão
            </div>
            <div class="panel-body">
                <?php $userDetails->render_alert("trip_sem_profissao." . $pers["cod"]) ?>
                <?php render_painel_profissao($pers); ?>
                <?php if ($pers["profissao"]): ?>
                    <a class="link_content" href="./?ses=status&nav=profissao&cod=<?= $pers["cod"] ?>">
                        Ver o painel completo da profissão
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        <h4>Selos de experiência: <?= $pers["selos_xp"] ?> <img src="Imagens/Icones/seloexp.png"/></h4>
    </div>
    <div class="panel-body">
        <p>
            Os Selos de Experiência permitem que você modifique algumas características do tripulante, como as
            habilidades de classe escolhidas sem precisar gastar Ouro.
        </p>
        <h4>
            Você pode trocar <?= mascara_numeros_grandes(preco_selo_exp($pers)) ?> Pontos de Experiência por 1
            Selo de Experiência.
        </h4>

        <p>
            <button class="btn btn-success link_confirm" <?= $pers["xp"] < preco_selo_exp($pers) ? "disabled" : "" ?>
                    href="Personagem/selo_xp_comprar.php?cod=<?= $pers["cod"] ?>"
                    data-question="Você deseja trocar <?= mascara_numeros_grandes(preco_selo_exp($pers)) ?> Pontos de Experiência por 1 Selo de Experiência?">
                Trocar <?= mascara_numeros_grandes(preco_selo_exp($pers)) ?> Pontos de Experiência por 1
                <img src="Imagens/Icones/seloexp.png"/>
            </button>

            <button class="btn btn-info link_confirm" href="Vip/comprar_selo_xp.php?cod=<?= $pers["cod"] ?>"
                <?= $userDetails->conta["gold"] < PRECO_GOLD_SELO_EXP ? "disabled" : ""; ?>
                    data-question="Deseja comprar Selos de Experiência usando suas Moedas de Ouro?">
                Comprar 1 <img src="Imagens/Icones/seloexp.png"/> por <?= PRECO_GOLD_SELO_EXP ?>
                <img src="Imagens/Icones/Gold.png"/>
            </button>
        </p>
        <p class="text-warning">
            Atenção: Os selos de experiência são individuais por tripulante.
        </p>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        Excelência de Classe
    </div>
    <div class="panel-body">
        <?php if ($pers["lvl"] < 50): ?>
            <p>Este recurso será desbloqueado quando este tripulante alcançar o nível 50.</p>
        <?php elseif (!$pers["classe"]): ?>
            <p>Este tripulante precisa de uma classe de combate para treinar a Excelência</p>
        <?php else: ?>
            <div>
                <p>
                    Seus tripulantes podem usar os pontos de experiência que adquirem no nível 50
                    para evoluir sua e excelência de classe e ficarem mais fortes a cada nível.
                </p>
                <p>Nível atual:</p>
                <h1 class="<?=
                $pers["excelencia_lvl"] < 10 ? "" :
                    ($pers["excelencia_lvl"] < 25 ? "text-info" :
                        ($pers["excelencia_lvl"] < 40 ? "text-success" :
                            ($pers["excelencia_lvl"] < 50 ? "text-warning" :
                                "text-danger")))
                ?>">
                    <?= $pers["excelencia_lvl"] ?>
                </h1>
                <div class="row">
                    <div class="col-md-<?= ($pers["excelencia_lvl"] < EXCELENCIA_LVL_MAX) ? 6 : 12 ?>">
                        <h4>Bônus recebidos:</h4>
                        <?php render_bonus_excelencia(get_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"])); ?>
                    </div>
                    <?php if ($pers["excelencia_lvl"] < EXCELENCIA_LVL_MAX): ?>
                        <div class="col-xs-12 col-md-6">
                            <h4>Bônus no próximo nível:</h4>
                            <?php render_bonus_excelencia(get_next_bonus_excelencia($pers["classe"], $pers["excelencia_lvl"])); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <br/>

                <?php if ($pers["excelencia_lvl"] < EXCELENCIA_LVL_MAX): ?>
                    <div class="progress">
                        <div class="progress-bar progress-bar-primary"
                             style="width: <?= $pers["excelencia_xp"] / $pers["excelencia_xp_max"] * 100 ?>%">
                            <a>
                                XP: <?= $pers["excelencia_xp"] . "/" . $pers["excelencia_xp_max"] ?>
                            </a>
                        </div>
                    </div>
                    <?php $xp_necessaria_up = $pers["excelencia_xp_max"] - $pers["excelencia_xp"]; ?>
                    <?php if ($pers["xp"] && $xp_necessaria_up): ?>
                        <form class="ajax_form form-inline" method="post"
                              action="Personagem/excelencia_aplicar_xp"
                              data-question="Deseja aplicar estes pontos de experiência para evoluir a Excelência desse tripulante?">
                            <input type="hidden" name="pers" value="<?= $pers["cod"] ?>">
                            <div class="form-group">
                                <label>Experiência para aplicar: </label>
                                <?php $maximo = $xp_necessaria_up < $pers["xp"] ? $xp_necessaria_up : $pers["xp"]; ?>
                                <input class="form-control" type="number" min="1"
                                       value="<?= $maximo ?>"
                                       max="<?= $maximo ?>"
                                       name="quant">
                            </div>
                            <button class="btn btn-success">
                                Aplicar
                            </button>
                        </form>
                    <?php endif; ?>
                    <?php if (!$xp_necessaria_up): ?>
                        <p>
                            <button class="btn btn-success link_confirm"
                                    data-question="Deseja evoluir a excelência deste tripulante?"
                                    href="Personagem/excelencia_evoluir.php?pers=<?= $pers["cod"] ?>"
                                <?= $userDetails->tripulacao["berries"] < PRECO_BERRIES_EVOLUIR_EXCELENCIA ? "disabled" : ""; ?>>
                                Evoluir
                                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries(PRECO_BERRIES_EVOLUIR_EXCELENCIA); ?>
                            </button>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>