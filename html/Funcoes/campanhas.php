<?php function format_text_vars($text) {
    global $userDetails;
    return str_replace("%capitao_nome%", $userDetails->capitao["nome"], $text);
} ?>
<?php function render_campanha_etapa($etapa, Campanha $validator, $nome) { ?>
    <h4><?= $etapa["titulo"] ?></h4>
    <div class="etapa-dialogo">
        <?php if (isset_and_true($etapa, "dialogo")): ?>
            <?php render_dialogo($etapa["dialogo"], $nome); ?>
        <?php endif; ?>
    </div>
    <div class="etapa-objetivo">
        <h4>Objetivo:</h4>
        <?php $progresso = $validator->get_current_progress(); ?>
        <div class="progress  progress-lg-sm">
            <div class="progress-bar progress-bar-success"
                 style="width: <?= $progresso["progresso_atual"] / $progresso["progresso_total"] * 100 ?>%;">
                <a style="display: inline-block"><?= $etapa["objetivo"] . " - " . $progresso["progresso_atual"] . "/" . $progresso["progresso_total"] ?></a>
            </div>
        </div>
    </div>
    <div class="etapa-recompensas">
        <h4>Recompensas:</h4>
        <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
        <?php $reagents = get_reagents_for_recompensa(); ?>
        <?php foreach ($etapa["recompensas"] as $recompensa): ?>
            <?php render_recompensa($recompensa, $reagents, $equipamentos); ?>
        <?php endforeach; ?>
    </div>
    <?php if ($progresso["progresso_atual"] >= $progresso["progresso_total"]): ?>
        <button class="btn btn-success link_send" href="link_Campanha/finalizar_etapa_<?= $nome ?>.php">
            Finalizar
        </button>
    <?php endif; ?>
<?php } ?>
<?php function render_dialogo($dialogo, $nome) { ?>
    <div class="tab-content">
        <?php foreach ($dialogo as $index => $fala): ?>
            <div id="dialogo-<?= $index ?>" class="tab-pane <?= ($index == 0 ? "active" : "") ?>">
                <?php if ($fala["formato"] == "pergaminho"): ?>
                    <div class="dialogo-pergaminho">
                        <?= format_text_vars($fala["mensagem"]) ?>
                    </div>
                    <?php render_fala_buttons($fala, $index, $nome); ?>
                <?php elseif ($fala["formato"] == "npc"): ?>
                    <div class="row">
                        <div class="col-md-4 col-sm-8">
                            <h4><?= $fala["npc_nome"] ?></h4>
                            <img src="Imagens/NPC/Corpo/<?= $fala["npc_id"] ?>.png" width="100%"/>
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <div class="dialogo-area">
                                <?= format_text_vars($fala["mensagem"]) ?>
                            </div>
                            <?php render_fala_buttons($fala, $index, $nome); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php } ?>
<?php function render_fala_buttons($fala, $index, $nome) { ?>
    <?php if (isset_and_true($fala, "botao")): ?>
        <ul class="text-left">
            <li>
                <a href="#dialogo-<?= $index + 1 ?>" data-toggle="tab">
                    <?= $fala["botao"] ?>
                </a>
            </li>
        </ul>
    <?php endif; ?>
    <?php if (isset_and_true($fala, "aceitar")): ?>
        <a class="btn btn-success link_send" href="link_Campanha/aceitar_<?= $nome ?>.php">
            Aceitar
        </a>
    <?php endif; ?>
    <?php if (isset_and_true($fala, "atacar")): ?>
        <a class="btn btn-success link_send" href="link_Campanha/atacar_<?= $nome ?>.php">
            Lutar!
        </a>
    <?php endif; ?>
<?php } ?>
<?php function render_campanha_css() { ?>

    <style type="text/css">
        .dialogo-area {
            padding: 20px;
            border-radius: 5px;
            border: 2px solid black;
            background: white;
            color: black;
            position: relative;
            text-align: left;
            margin-bottom: 40px;
        }

        .dialogo-pergaminho {
            background: url('Imagens/Backgrounds/pergaminho.jpg');
            padding: 20px 30px;
            -webkit-box-shadow: inset 0 0 15px #91805c;
            -moz-box-shadow: inset 0 0 15px #91805c;
            box-shadow: inset 0 0 15px #91805c;
            border: 1px solid #392f22;
            -webkit-border-radius: 3px;
            -moz-border-radius: 3px;
            border-radius: 3px;
            font-style: italic;
            font-family: "Times New Roman", serif;
            color: #662700;
            text-align: left;
        }

        @media (min-width: 992px) {
            .dialogo-area:before {
                content: ' ';
                position: absolute;
                width: 0;
                height: 0;
                left: 36px;
                bottom: -34px;
                border: 17px solid;
                border-color: black transparent transparent black;
            }

            .dialogo-area:after {
                content: ' ';
                position: absolute;
                width: 0;
                height: 0;
                left: 38px;
                bottom: -30px;
                border: 15px solid;
                border-color: white transparent transparent white;
            }
        }
    </style>
<?php } ?>