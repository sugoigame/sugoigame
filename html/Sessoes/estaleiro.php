<div class="panel-heading">
    Estaleiro
</div>

<div class="panel-body">
    <?= ajuda("Estaleiro", "O estaleiro vende e concerta embarcações, além de vender partes importantes para aprimorar seu navio.") ?>
    <script type="text/javascript">
        $(function () {
            $(".bt_comprar_barco").click(function (e) {
                e.preventDefault();
                var locale = $(this).attr("href");
                bootbox.confirm({
                    title: 'Tem certeza que deseja comprar uma nova embarcação?',
                    message: "Ao comprar uma nova embarcação sua embarcação atual é trocada pela nova perdendo todos os itens equipados com ela",
                    buttons: {
                        confirm: {
                            label: 'Sim',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'Não',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            sendGet(locale);
                        }
                    }
                });
            });
            timeOuts["atualizaTempoReparo"] = setTimeout("atualizaTempoReparo()", 1000);

            $(".comprar_material").click(function () {
                var url = "Mercado/marcenaria_comprar.php?item=1&tipo=13";
                url += "&quant=" + $("#" + $(this).attr("id") + "_quant").val();
                sendGet(url);
            });
        });
        var cont = 0;

        function atualizaTempoReparo() {
            if (document.getElementById("tempo_reparo") != null) {
                timeOuts["atualizaTempoReparo"] = setTimeout("atualizaTempoReparo()", 1000);
                var input = document.getElementById("tempo_reparo").innerHTML - cont;
                document.getElementById("tempo_reparo_min").innerHTML = transforma_tempo(input);
                cont += 1;
                if (input < 0) {
                    loadPagina(pagina_atual);
                }
            }
        }

    </script>

    <?php if ($userDetails->navio) : ?>
        <div>
            <?php render_navio_icon(); ?>
        </div>

        <div>
            <?php render_navio_hp_bar() ?>
        </div>
        <br/>
        <br/>
    <?php endif; ?>

    <?php $mods = $connection->run("SELECT * FROM tb_ilha_mod WHERE ilha = ?", "i", $userDetails->ilha["ilha"])->fetch_array(); ?>

    <?php $items = get_many_results_joined_mapped_by_type("tb_ilha_itens", "cod_item", "tipo_item", array(
        array("nome" => "tb_navio", "coluna" => "cod_navio", "tipo" => 11),
        array("nome" => "tb_item_navio_casco", "coluna" => "cod_casco", "tipo" => 3),
        array("nome" => "tb_item_navio_leme", "coluna" => "cod_leme", "tipo" => 4),
        array("nome" => "tb_item_navio_velas", "coluna" => "cod_velas", "tipo" => 5),
        array("nome" => "tb_item_navio_canhao", "coluna" => "cod_canhao", "tipo" => 12),
    ), "WHERE ilha = ?", "i", $userDetails->ilha["ilha"]); ?>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <?php $preco = $item["preco"] * $mods["mod"]; ?>
            <div class="list-group-item col-md-4">
                <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                <div>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                </div>
                <?php if ($userDetails->tripulacao["berries"] >= $preco): ?>
                    <p>
                        <?php if ($item["tipo_item"] == 11 AND $item["limite"] >= count($userDetails->personagens)) : ?>
                            <button href='Mercado/marcenaria_comprar.php?item=<?= $item["cod_item"]; ?>&tipo=<?= $item["tipo_item"]; ?>'
                                    class="bt_comprar_barco btn btn-success">
                                Comprar
                            </button>
                        <?php elseif ($item["tipo_item"] != 11): ?>
                            <button href='link_Mercado/marcenaria_comprar.php?item=<?= $item["cod_item"]; ?>&tipo=<?= $item["tipo_item"]; ?>'
                                    class="link_send btn btn-success">
                                Comprar
                            </button>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <div class="list-group-item col-md-4">
            <h5><img src="Imagens/Itens/168.png"/><br>Bala de Canhão</h5>
            <div>
                Usada no canhão do navio para batalhas em alto mar.
            </div>
            <div>
                <?php $preco = 10000 * $mods["mod"]; ?>
                <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
            </div>
            <?php if ($userDetails->tripulacao["berries"] >= $preco): ?>
                <p>
                <div class="form-inline">
                    <div class="form-group">
                        <input class="form-control" size="5" id="13_quant" value="1"><br/>
                    </div>
                    <button id="13" class="comprar_material btn btn-success">Comprar</button>
                </div>
                </p>
            <?php endif; ?>
        </div>
    </div>

</div>