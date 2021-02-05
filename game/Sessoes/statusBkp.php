<?php function row_atributo($abr, $nome, $img, $desc, $pers, $bonus) { ?>
    <div class="form-inline">
        <div class="text-left ">
            <img width="35px" src="Imagens/Icones/<?= $img ?>.png" data-toggle="tooltip" data-html="true"
                 data-placement="bottom"
                 title="<strong><?= $nome ?></strong><br/> <?= $desc ?>">
            <div id="<?= $abr . '_' . $pers["cod"]; ?>" class="text-center" style="display: inline-block; width:30px">
                <?= $pers[$abr]; ?>
            </div>
            <div class="text-center" style="display: inline-block; width:45px">
                <?= "+" . $bonus[$abr]; ?>
            </div>
            <div style="display: inline-block;">
                =
            </div>
            <div id="<?= $abr ?>_total_<?= $pers["cod"]; ?>" class="text-center"
                 style="display: inline-block; width:50px">
                <?= $pers[$abr] + $bonus[$abr]; ?>
            </div>
            <?php if ($pers["pts"] > 0) : ?>
                <button class="btn btn-success btn-add-atributo" data-cod="<?= $pers["cod"] ?>"
                        onclick="add_atributo('<?= $abr ?>', '<?= $pers["cod"]; ?>', '<?= $nome ?>')">
                    <i class="fa fa-plus"></i>
                </button>
            <? endif; ?>
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

<div class="panel-heading">
    Tripulação
</div>

<style type="text/css">
    .sel-personagem {
        max-height: 300px;
        overflow: auto;
        margin-bottom: 50px;
    }
</style>
<script type="text/javascript">
    $(function () {
        $(".personagem_reset_atributos").click(function () {
            var data = $(this).attr("href");
            bootbox.confirm('Resetar os atributos desse personagem?<br>obs: Todas habilidades de classe (exceto soco) serão removidas', function (result) {
                if (result) {
                    sendGet('Vip/reset_atributos.php?' + data);
                }
            });
        });
        $(".personagem_reset_atributos_dobrao").click(function () {
            var data = $(this).attr("href");
            bootbox.confirm('Resetar os atributos desse personagem?<br>obs: Todas habilidades de classe (exceto soco) serão removidas', function (result) {
                if (result) {
                    sendGet('VipDobroes/reset_atributos.php?' + data);
                }
            });
        });

        $(".muda_alcunha").change(function () {
            var data = "?cod=" + $(this).attr("data") + "&alc=" + $(this).val();
            sendGet('Personagem/muda_alcunha.php' + data);
        });

        $('.reset-nome').click(function () {
            var cod = $(this).data('pers');
            bootbox.prompt('Escreva um novo nome para esse personagem:', function (input) {
                if (input) {
                    sendGet('Vip/reset_nome.php?nome=' + input + '&cod=' + cod);
                }
            });
        });
        $('.reset-nome-dobroes').click(function () {
            var cod = $(this).data('pers');
            bootbox.prompt('Escreva um novo nome para esse personagem:', function (input) {
                if (input) {
                    sendGet('VipDobroes/reset_nome.php?nome=' + input + '&cod=' + cod);
                }
            });
        });

        $('.trocar-personagem').click(function () {
            var pers = $(this).data('pers');
            var tipo = $(this).data('tipo');
            $('#pers-trocar-personagem').val(pers);
            $('#tipo-trocar-personagem').val(tipo);

            $('#modal-trocar-personagem').modal('show');
        });

        $('.capitao-selectable-img').click(function () {
            var img = $(this).data("img");
            $('.capitao-selectable-img').css('border', 'none');
            $(this).css('border', '4px solid #870000');
            $("#img_capitao").attr("src", "Imagens/Personagens/Big/" + img + "(0).jpg");
            $("#img-trocar-personagem").val(img);
        });

        $(".trocar-personagem-confirm").click(function () {
            $('#modal-trocar-personagem').modal('hide');
            var pers = $('#pers-trocar-personagem').val();
            var tipo = $('#tipo-trocar-personagem').val();
            var img = $('#img-trocar-personagem').val();
            sendGet('Vip/trocar_personagem.php?pers=' + pers + '&img=' + img + '&tipo=' + tipo);
        });
    });

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
                    $.ajax({
                        type: 'get',
                        url: "Scripts/Personagem/adiciona_atributo.php",
                        data: "atributo=" + atributo + "&cod=" + cod + "&quant=" + quant,
                        cache: false,
                        success: function (retorno) {
                            if (retorno.substr(0, 1) == "#") {
                                bootbox.alert(retorno.substr(1, retorno.length));
                            } else {
                                if (retorno != "!") {
                                    var id = atributo + "_" + cod;
                                    document.getElementById(id).innerHTML = retorno;
                                    var divpt = "pts_" + cod;
                                    var ptstotal = document.getElementById(divpt).innerHTML;
                                    var pts = parseFloat(ptstotal);
                                    pts -= quant;
                                    var idtotal = atributo + "_total_" + cod;
                                    var atrtotal = document.getElementById(idtotal).innerHTML;
                                    var total = parseFloat(atrtotal);
                                    total += quant;
                                    document.getElementById(idtotal).innerHTML = total;
                                    document.getElementById(divpt).innerHTML = pts;

                                    if (pts <= 0) {
                                        $('.btn-add-atributo[data-cod="' + cod + '"]').css("display", "none")
                                    }
                                }
                            }
                        }
                    });
                }
            });

        }
        else if (atributo_qnt <= 0) {
            var theClass = ".add_" + cod;
            $(theClass).css("display", "none")
        }
    }
</script>

<div class="panel-body">

    <?= ajuda("Visão Geral - Tripulação", "Aqui você visualiza as principais informações dos seus personagens e da sua 
    tripulação, como atributos,  recompensas, posições no Ranking, etc.") ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="link_bandeira" class="link_content" id="status_bandeira" data-toggle="tooltip"
               data-placement="right" title="Clique para personalizar sua bandeira"><img
                        src="Imagens/Bandeiras/img.php?cod=<?= $userDetails->tripulacao["bandeira"]; ?>&f=<?= $userDetails->tripulacao["faccao"]; ?>"></a>
            <br>
            <h3><?= $userDetails->tripulacao["tripulacao"]; ?></h3>
        </div>
        <div class="panel-body">
            <div class="row text-left">
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
                        <li>Ranking dessa era: <?= $trimestral["posicao"]; ?>º</li>
                        <li>Ranking desse mês: <?= $mensal["posicao"]; ?>º</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div>
        <?php render_personagens_pills() ?>
    </div>

    <div class="tab-content">
        <?php $titulos_compartilhados = $connection->run(
            "SELECT tit.cod_titulo, tit.nome, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
            INNER JOIN tb_personagens per ON pertit.cod = per.cod
            INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
            WHERE tit.compartilhavel = 1 AND per.id = ?",
            "i", array($userDetails->tripulacao["id"])
        )->fetch_all_array(); ?>

        <?php foreach ($userDetails->personagens as $index => $pers): ?>
            <?php render_personagem_panel_top($pers, $index) ?>
            <div class="row">
                <div class="col-md-3">
                    <img src="Imagens/Personagens/Big/<?= getImg($pers, "c") ?>.jpg" class="hidden-sm hidden-xs">
                    <button class="btn btn-info trocar-personagem"
                            data-pers="<?= $pers["cod"] ?>"
                            data-tipo="gold"
                        <?= $userDetails->conta["gold"] >= PRECO_GOLD_TROCAR_PERSONAGEM ? "" : "disabled" ?>>
                        <?= PRECO_GOLD_TROCAR_PERSONAGEM ?>
                        <img src="Imagens/Icones/Gold.png"/>
                        Trocar Personagem
                    </button>
                    <button class="btn btn-info trocar-personagem"
                            data-pers="<?= $pers["cod"] ?>"
                            data-tipo="dobrao"
                        <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_TROCAR_PERSONAGEM ? "" : "disabled" ?>>
                        <?= PRECO_DOBRAO_TROCAR_PERSONAGEM ?>
                        <img src="Imagens/Icones/Dobrao.png"/>
                        Trocar Personagem
                    </button>
                </div>
                <div class="col-md-9">
                    <div class="panel panel-default">
                        <?php
                        $titulos_bd = $connection->run(
                            "SELECT tit.cod_titulo, tit.nome, tit.cod_titulo AS titulo FROM tb_personagem_titulo pertit 
                INNER JOIN tb_titulos tit ON pertit.titulo = tit.cod_titulo
                WHERE pertit.cod = ?", "i", $pers["cod"]
                        )->fetch_all_array();
                        $titulos = array();
                        foreach ($titulos_bd as $titulo) {
                            $titulos[$titulo["cod_titulo"]] = $titulo;
                        }
                        foreach ($titulos_compartilhados as $titulo) {
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

                        <div class="panel-heading">
                            <?= $pers["nome"]; ?> <?= ($pers_titulo) ? " - " . $pers_titulo : "" ?>
                        </div>
                        <div class="panel-body">
                            <?php if ($pers["xp"] >= $pers["xp_max"] AND $pers["lvl"] < 50) : ?>
                                <p>
                                    <button id="status_evoluir"
                                            href="link_Personagem/personagem_evoluir.php?cod=<?= $pers["cod"] ?>"
                                            class="link_send btn btn-info">
                                        Evoluir <i class="fa fa-arrow-up"></i>
                                    </button>
                                </p>
                            <?php endif; ?>

                            <?php render_personagem_status_bars($pers); ?>

                            <div class="row">
                                <div class="col-md-6 text-left">
                                    <ul>
                                        <li>Nome: <?= $pers["nome"]; ?></li>
                                        <li>
                                            Alcunha:
                                            <?php if (count($titulos)): ?>
                                                <select data="<?= $pers["cod"]; ?>" class="muda_alcunha">
                                                    <option value="0">Sem alcunha</option>
                                                    <?php foreach ($titulos as $titulo) : ?>
                                                        <option value="<?= $titulo["titulo"] ?>" <?= ($titulo["titulo"] == $pers["titulo"]) ? 'selected="1"' : "" ?>>
                                                            <?= $titulo["nome"] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php else: ?>
                                                Sem alcunha
                                            <?php endif; ?>
                                        </li>
                                        <li>Nível: <?= $pers["lvl"]; ?></li>
                                        <li>Classe: <?= nome_classe($pers["classe"]); ?></li>
                                        <li>Profissão: <?= nome_prof($pers["profissao"]); ?></li>
                                        <li>
                                            <?= $userDetails->tripulacao["faccao"] == 0 ? "Fama" : "Ameaça" ?>:
                                            <?= $pers["fama_ameaca"] ?>
                                        </li>
                                        <li>
                                            <?= $userDetails->tripulacao["faccao"] == 0 ? "Gratificação" : "Recompensa" ?>
                                            :
                                            <?= mascara_berries($pers["fama_ameaca"] * 20000) ?>
                                        </li>
                                    </ul>

                                    <p>
                                        <button class="reset-nome btn btn-info" data-pers="<?= $pers["cod"] ?>"
                                            <?= $userDetails->conta["gold"] >= PRECO_GOLD_RESET_NOME_PERSONAGEM ? "" : "disabled" ?> >
                                            <?= PRECO_GOLD_RESET_NOME_PERSONAGEM ?> <img src="Imagens/Icones/Gold.png"/>
                                            Renomear o personagem
                                        </button>
                                    </p>
                                    <p>
                                        <button class="reset-nome-dobroes btn btn-info" data-pers="<?= $pers["cod"] ?>"
                                            <?= $userDetails->conta["dobroes"] >= PRECO_DOBRAO_RESET_NOME_PERSONAGEM ? "" : "disabled" ?>>
                                            <?= PRECO_DOBRAO_RESET_NOME_PERSONAGEM ?> <img
                                                    src="Imagens/Icones/Dobrao.png"/>
                                            Renomear o personagem
                                        </button>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <?php $bonus = calc_bonus($pers); ?>
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
                                        "Cada ponto diminui a chance do inimigo se esquivar ou bloquear seu ataque em 1%",
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
                                    <div>
                                        <?php if ($pers["pts"]) : ?>
                                            <h4>
                                                Pontos a distribuir:
                                                <b id="pts_<?= $pers["cod"] ?>"><?= $pers["pts"] ?></b>
                                            </h4>
                                        <?php endif; ?>
                                    </div>
                                    <p>
                                        <button class="personagem_reset_atributos btn btn-info"
                                                href="cod=<?= $pers["cod"]; ?>"
                                            <?= $userDetails->conta["gold"] < PRECO_GOLD_RESET_ATRIBUTOS ? "disabled" : "" ?>>
                                            <?= PRECO_GOLD_RESET_ATRIBUTOS ?> <img src="Imagens/Icones/Gold.png"> <br/>
                                            Resetar Atributos
                                        </button>
                                    </p>
                                    <p>
                                        <button class="personagem_reset_atributos_dobrao btn btn-info"
                                                href="cod=<?= $pers["cod"]; ?>"
                                            <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_RESET_ATRIBUTOS ? "disabled" : "" ?>>
                                            <?= PRECO_DOBRAO_RESET_ATRIBUTOS ?> <img src="Imagens/Icones/Dobrao.png">
                                            <br/>
                                            Resetar Atributos
                                        </button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <div class="col-md-6">
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
            <?php render_personagem_panel_bottom() ?>
        <?php endforeach; ?>
    </div>
</div>

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
