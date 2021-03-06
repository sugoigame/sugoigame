<div class="panel-heading" id="heading">
    Mestre de Aprimoramentos
</div>

<style type="text/css">
    #up-equipamento {
        width: 80px;
        height: 80px;
        padding: 20px;
        background: url("Imagens/Backgrounds/slot1.png");
    }

    #up-joia {
        width: 80px;
        height: 84px;
        padding: 19px 23px;
        background: url("Imagens/Backgrounds/slot2.png");
    }

    #up-equipamento-nome,
    #up-joia-nome {
        width: 100px;
    }

</style>
<script type="text/javascript">
    $(function () {
        $(".up-select").click(function () {
            $(".up-select").attr('disabled', false);
            $(this).attr('disabled', true);

            $('html, body').animate({
                scrollTop: $("#heading").offset().top
            }, 100);

            var img = $(this).attr("img");
            var nome = $(this).attr("nome");
            var cod = $(this).attr("cod");
            var lvl = $(this).attr("lvl");
            var cat = $(this).attr("cat");

            $("#up-equipamento-inp").val(cod);

            $("#up-equipamento")
                .html('<img src="Imagens/Itens/' + img + '.png" />')
                .data("lvl", lvl)
                .data("cat", cat);
            $("#up-equipamento-nome").html(nome);

            var nomeJoia = '';
            var imgJoia = '';
            if (lvl < 1 && cat < 3) {
                nomeJoia = "Aquamarine Bruta";
                imgJoia = 79;
            }
            else if (lvl < 1) {
                nomeJoia = "Aquamarine Lapidada";
                imgJoia = 86;
            }
            else if (lvl == 1 && cat < 3) {
                nomeJoia = "Ametista  Bruta";
                imgJoia = 81;
            }
            else if (lvl == 1) {
                nomeJoia = "Ametista Lapidada";
                imgJoia = 88;
            }
            else if (lvl == 2 && cat < 3) {
                nomeJoia = "Topázio Bruto";
                imgJoia = 80;
            }
            else if (lvl == 2) {
                nomeJoia = "Topázio Lapidado";
                imgJoia = 87;
            }
            else if (lvl == 3 && cat < 3) {
                nomeJoia = "Esmeralda  Bruta";
                imgJoia = 77;
            }
            else if (lvl == 3) {
                nomeJoia = "Esmeralda Lapidada";
                imgJoia = 84;
            }
            else if (lvl <= 5 && cat < 3) {
                nomeJoia = "Rubi Bruto";
                imgJoia = 82;
            }
            else if (lvl <= 5) {
                nomeJoia = "Rubi Lapidado";
                imgJoia = 89;
            }
            else if (lvl <= 7 && cat < 3) {
                nomeJoia = "Safira Bruta";
                imgJoia = 78;
            }
            else if (lvl <= 7) {
                nomeJoia = "Safira Lapidada";
                imgJoia = 85;
            }
            else if (lvl <= 9 && cat < 3) {
                nomeJoia = "Diamante Bruto";
                imgJoia = 76;
            }
            else {
                nomeJoia = "Diamante Lapidado";
                imgJoia = 83;
            }

            $("#up-joia").html('<img src="Imagens/Itens/' + imgJoia + '.jpg" />');
            $("#up-joia-nome").html(nomeJoia);

            attPreco();
        });
        function attPreco() {
            var lvl = parseInt($("#up-equipamento").data("lvl"), 10);
            var cat = parseInt($("#up-equipamento").data("cat"), 10);

            var precoB = (300000 * cat + 200000) * (lvl + 1);

            $("#up-precoB").html(mascaraBerries(precoB));
        }

        $("#up-evoluir").click(function () {
            var item = $("#up-equipamento-inp").val();

            var locale = "Personagem/equipamento_evoluir.php?item=" + item
            sendGet(locale);
        });
    });
</script>
<div class="panel-body">
    <?= ajuda("Mestre de Aprimoramentos", "O Mestre de Aprimoramentos usa jóias para aprimorar seus equipamentos.") ?>

    <?php

    $items = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_equipamentos", "cod_equipamento", TIPO_ITEM_EQUIPAMENTO,
        "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]);
    ?>
    <input style="display: none" id="up-equipamento-inp"/>
    <div class="row">
        <div id="up-info" class="col-md-6">
            <div class="row">
                <div class="col-xs-4">
                    Equipamento:
                </div>
                <div class="col-xs-4">
                    <div id="up-equipamento"></div>
                </div>
                <div id="up-equipamento-nome" class="col-xs-4"></div>
            </div>
            <div class="row">
                <div class="col-xs-4">Jóia:</div>
                <div class="col-xs-4">
                    <div id="up-joia"></div>
                </div>
                <div class="col-xs-4" id="up-joia-nome"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div>
                <p>
                    Preço: <span id="up-precoB">0</span> <img src="Imagens/Icones/Berries.png"/>
                </p>
                <p>
                    <button id="up-evoluir" class="btn btn-success">Aprimorar</button>
                </p>
            </div>
        </div>
    </div>
    <div>
        <div class="list-group">
            <?php foreach ($items as $item) : ?>
                <div class="list-group-item col-md-4">
                    <h4 width="500px" height="150px">
                        <img src="Imagens/Itens/<?= $item["img"]; ?>.png"/>
                    </h4>
                    <p>
                        <?= info_item($item, $item, FALSE, FALSE, FALSE); ?>
                    </p>
                    <?php if ($item["upgrade"] < 10): ?>
                        <button class="up-select btn btn-success"
                                img="<?= $item["img"]; ?>"
                                nome="<?= $item["nome"]; ?>"
                                cod="<?= $item["cod_item"]; ?>"
                                cat="<?= $item["categoria"]; ?>"
                                lvl="<?= $item["upgrade"]; ?>">
                            Selecionar
                        </button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>