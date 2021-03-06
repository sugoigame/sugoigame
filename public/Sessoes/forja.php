<div class="panel-heading" id="heading">
    Forja
</div>

<style type="text/css">

    #slots table {
        margin: auto;
    }

    #slots td {
        background: transparent;
        border: 1px solid #000000;
        border-radius: 5px;
        width: 40px;
        height: 40px;
        padding: 3px;
    }

    .quant {
        position: absolute;
        padding: 2px;
        font-size: 12px;
        text-align: center;
        background: #222222;
        border: 1px solid #cccccc;
        border-radius: 5px;
        color: #cccccc;
    }
</style>

<script type="text/javascript">
    $(function () {
        appendForja();

        $("#limpar").click(function () {
            $(".slot_oc").attr("class", "slot");
            $(".slot").css("background", "transparent").css("opacity", "1").unbind("click").html("");
            $(".slot_inp").attr("value", "");
            $(".item_forja").unbind("click").css("opacity", "1");
            $('#slot_quant').val(1);
            appendForja();
        });
        $("#forjar").click(function () {
            var pagina = "Profissao/ferreiro_forjar";

            var personagem = $('input[name=ferreiro-select]:checked').val();
            var obj = {
                pers: personagem,
                quant: $('#slot_quant').val()
            };

            for (var i = 1; i < 9; i++) {
                obj['slot_' + i] = $("#slot_" + i + "_inp").val();
            }
            sendForm(pagina, obj);
        });
    });
    function appendForja() {
        $(".item_forja").click(function () {
            var infos = $(this).attr('id').split("|");
            var quant = parseInt(infos[0], 10);
            var cod = infos[1];
            var tipo = infos[2];
            if (quant > 1) {
                quant = $('#' + cod + '_' + tipo + '_quant').val();
            }

            var src = $(this).data("src");
            $(".slot").css("background", "#ff0000").css("opacity", "0.4").click(function () {
                var id = "#" + $(this).attr('id') + "_inp";

                var html = (quant > 1) ? '<span class="quant">' + quant + '</span>' : '';
                html += '<img src="' + src + '" />';
                $(this).html(html);

                $(id).val(cod + "_" + tipo + "_" + quant);
                $(".slot").css("background", "transparent").css("opacity", "1").unbind("click");

                $(this).attr("class", "slot_oc");
            });

            $('html, body').animate({
                scrollTop: $("#heading").offset().top
            }, 100);
        });
    }

</script>
<div class="panel-body">
    <?= ajuda("Forja", "Aqui seu ferreiro pode criar armas e equipamentos.<br>Para forjar algum item, é preciso antes 
        conhecer a receita daquele item, então coloque os ingredientes certos a ordem certa (lembrando que o lugar 
        onde você coloca o item na bigorna e a quantidade de itens fazem diferença), e clique em 
        \"Forjar\" para ver o resultado.") ?>

    <div class="clearfix">
        <h5>Escolha seu ferreiro:</h5>
        <div class="row">
            <?php if ($userDetails->ferreiros): ?>
                <?php foreach ($userDetails->ferreiros as $ferreiro): ?>
                    <div class="col-md-3">
                        <label>
                            <img src="Imagens/Personagens/Icons/<?= get_img($ferreiro, "r") ?>.jpg">
                            <br/>
                            <?= $ferreiro["nome"] ?>
                            <br/>
                            <input type="radio" name="ferreiro-select" value="<?= $ferreiro["cod"]; ?>"
                                   checked="checked">
                        </label>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <div class="col-md-3">
                <label>
                    Ferreiro VIP: <?= PRECO_GOLD_FERREIRO_VIP ?> <img src="Imagens/Icones/Gold.png"/>
                    <input type="radio" name="ferreiro-select" value="ferreiro">
                </label>
            </div>
            <div class="col-md-3">
                <label>
                    Ferreiro VIP: <?= PRECO_DOBRAO_FERREIRO_VIP ?> <img src="Imagens/Icones/Dobrao.png"/>
                    <input type="radio" name="ferreiro-select" value="ferreiro_dobrao">
                </label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <h3>Área de trabalho</h3>
            <div id="bigorna">
                <div id="slots">
                    <table>
                        <tr>
                            <td id="slot_1" class="slot"></td>
                            <td id="slot_2" class="slot"></td>
                            <td id="slot_3" class="slot"></td>
                            <td id="slot_4" class="slot"></td>
                        </tr>
                        <tr>
                            <td id="slot_5" class="slot"></td>
                            <td id="slot_6" class="slot"></td>
                            <td id="slot_7" class="slot"></td>
                            <td id="slot_8" class="slot"></td>
                        </tr>
                    </table>
                    <br/>
                    <div class="text-center">
                        <label>Deseja repetir a receita quantas vezes?</label>
                        <input class="form-control" type="number" id="slot_quant" value="1"
                               style="width: 200px; margin: auto;">
                    </div>
                    <br/>
                    <p>
                        <button id="limpar" class="btn btn-primary">Limpar</button>
                        <button id="forjar" class="btn btn-success">Forjar</button>
                    </p>
                    <input type="hidden" id="slot_1_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_2_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_3_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_4_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_5_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_6_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_7_inp" class="slot_inp"/>
                    <input type="hidden" id="slot_8_inp" class="slot_inp"/>
                </div>
            </div>
        </div>

        <?php $items = get_many_results_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", array(
            array("nome" => "tb_item_acessorio", "coluna" => "cod_acessorio", "tipo" => TIPO_ITEM_ACESSORIO),
            array("nome" => "tb_item_equipamentos", "coluna" => "cod_equipamento", "tipo" => TIPO_ITEM_EQUIPAMENTO),
            array("nome" => "tb_item_reagents", "coluna" => "cod_reagent", "tipo" => TIPO_ITEM_REAGENT)
        ), "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]); ?>

        <div class="col-md-9">
            <div class="row">
                <?php foreach ($items as $item) : ?>
                    <div class="list-group-item col-md-2">
                        <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                        <p>
                            Quantidade no inventário <?= $item["quant"] ?>
                        </p>
                        <p>
                        <div>
                            <div class="form-group">
                                <?php if ($item["tipo_item"] == TIPO_ITEM_EQUIPAMENTO): ?>
                                    <button class="btn btn-info link_confirm"
                                            data-question="Equipamamentos Brancos de nível 46 ou superior são transformados em Essências Brancas. Equipamentos Verdes acima de nível 46 ou superior são transformados em Fragmentos de Essência Azul. Equipamentos Azuis, Negros e Dourados são transformados em Essência Azul. Os demais equipamentos são transformados em materiais aleatórios. Cada equipamento fornece 1 Estilhaço de Essência Branca por nível de Aprimoramento. Deseja destruir este equipamento?"
                                            href="Profissao/ferreiro_destruir.php?cod=<?= $item["cod_item"] ?>">
                                        Destruir
                                    </button>
                                <?php else: ?>
                                    <?php if ($item["quant"] > 1) : ?>
                                        <input placeholder="Insira a quantidade desejada" class="form-control"
                                               min="1" max="<?= $item["quant"] ?>"
                                               id="<?= $item["cod_item"]; ?>_<?= $item["tipo_item"] ?>_quant" value="1"
                                               type="number">
                                    <?php endif; ?>
                                    <button id="<?= $item["quant"] . "|" . $item["cod_item"] . "|" . $item["tipo_item"] ?>"
                                            data-src="<?= get_img_item_src($item) ?>"
                                            class="item_forja btn btn-primary">
                                        Usar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>