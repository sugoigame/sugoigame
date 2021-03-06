<div class="panel-heading">
    Restaurante
</div>

<script type="text/javascript">
    $(function () {
        $(".comprar_material").click(function () {
            var url = "Mercado/restaurante_comprar.php?item=" + $(this).attr("id");
            url += "&quant=" + $("#" + $(this).attr("id") + "_quant").val();
            sendGet(url);
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Restaurante", "Aqui você compra comida para sua tripulação.") ?>

    <?php $mods = $connection->run("SELECT * FROM tb_ilha_mod WHERE ilha = ?", "i", $userDetails->ilha["ilha"])->fetch_array(); ?>

    <?php $items = get_result_joined_mapped_by_type("tb_ilha_itens", "cod_item", "tipo_item", "tb_item_comida", "cod_comida", TIPO_ITEM_COMIDA,
        "WHERE ilha = ?", "i", $userDetails->ilha["ilha"]); ?>

    <h3>Itens a venda:</h3>

    <?php if (count($items)): ?>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <?php $preco = ($item["hp_recuperado"] + $item["mp_recuperado"]) * 60 * $mods["mod"]; ?>
                <div class="list-group-item col-md-2">
                    <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                    <div>
                        <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                    </div>
                    <?php if ($userDetails->tripulacao["berries"] >= $preco) : ?>
                        <p>
                        <div>
                            <div class="form-group">
                                <input placeholder="Insira a quantidade desejada" class="form-control" size="4"
                                       min="1" type="number"
                                       max="<?= floor($userDetails->tripulacao["berries"] / $preco) ?>"
                                       id="<?= $item["cod_item"]; ?>_quant" value="1">
                                <button id="<?= $item["cod_item"]; ?>" class="comprar_material btn btn-success">
                                    Comprar
                                </button>
                            </div>
                        </div>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Essa ilha não tem nenhum item a venda</p>
    <?php endif; ?>
</div>
