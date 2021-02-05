<div class="panel-heading">
    Mercado
</div>

<script type="text/javascript">
    $(function () {
        $(".comprar_material").click(function () {
            var url = "Mercado/materiais_comprar.php?item=" + $(this).attr("id");
            url += "&quant=" + $("#" + $(this).attr("id") + "_quant").val();
            sendGet(url);
        });
    });
</script>

<div class="panel-body">
    <?= ajuda("Mercado", "No mercado você pode comprar e vender itens.") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li>
                <a href="./?ses=mercado" class="link_content">Acessórios</a>
            </li>
            <li>
                <a href="./?ses=equipShop" class="link_content">Equipamentos</a>
            </li>
            <li class="active">
                <a href="./?ses=materiais" class="link_content">Materiais</a>
            </li>
        </ul>
    </div>

    <?php $mods = $connection->run("SELECT * FROM tb_ilha_mod WHERE ilha = ?", "i", $userDetails->ilha["ilha"])->fetch_array(); ?>

    <?php $items = get_result_joined_mapped_by_type("tb_ilha_itens", "cod_item", "tipo_item", "tb_item_reagents", "cod_reagent", TIPO_ITEM_REAGENT,
        "WHERE ilha = ?", "i", $userDetails->ilha["ilha"]); ?>

    <h3>Materiais a venda:</h3>

    <?php if (count($items)): ?>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <?php $preco = $item["preco"] * $mods["mod"]; ?>
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
                                       id="<?= $item["cod_item"]; ?>_quant" value="1" type="number">
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

    <h3>Itens no seu invetário:</h3>

    <?php $items = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_reagents", "cod_reagent", TIPO_ITEM_REAGENT,
        "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]); ?>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <?php $preco = $item["preco"] * $mods["mod_venda"]; ?>
            <?php
            if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
                $preco += $aumento * $preco;

                if ($preco >= $item["preco"]) {
                    $preco = $item["preco"] - 1;
                }
            }
            ?>
            <div class="list-group-item col-md-2">
                <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                <div>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                </div>
                <p>
                    Quantidade no inventário <?= $item["quant"] ?>
                </p>
                <p>
                    <button href="link_Mercado/materiais_vender.php?item=<?= $item["cod_item"] ?>&tudo=0"
                            class="link_send btn btn-primary">
                        Vender uma unidade
                    </button>
                    <?php if ($item["quant"] > 1): ?>
                        <button href="link_Mercado/materiais_vender.php?item=<?= $item["cod_item"] ?>&tudo=1"
                                class="link_send btn btn-primary">
                            Vender Tudo
                        </button>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>