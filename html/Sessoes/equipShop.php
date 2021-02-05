<div class="panel-heading">
    Mercado
</div>

<div class="panel-body">
    <?= ajuda("Mercado", "No mercado você pode comprar e vender itens.") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li>
                <a href="./?ses=mercado" class="link_content">Acessórios</a>
            </li>
            <li class="active">
                <a href="./?ses=equipShop" class="link_content">Equipamentos</a>
            </li>
            <li>
                <a href="./?ses=materiais" class="link_content">Materiais</a>
            </li>
        </ul>
    </div>

    <?php $mods = $connection->run("SELECT * FROM tb_ilha_mod WHERE ilha = ?", "i", $userDetails->ilha["ilha"])->fetch_array(); ?>

    <?php $items = get_result_joined_mapped_by_type("tb_ilha_itens", "cod_item", "tipo_item", "tb_equipamentos", "item", TIPO_ITEM_EQUIPAMENTO, "WHERE ilha = ?", "i", $userDetails->ilha["ilha"]); ?>

    <h3>Equipamentos a venda:</h3>

    <?php if (count($items)): ?>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <?php $item["upgrade"] = 0; ?>
                <?php $preco = preco_compra_equipamento($item); ?>
                <div class="list-group-item col-md-2">
                    <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                    <div>
                        <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                    </div>
                    <?php if ($userDetails->tripulacao["berries"] >= $preco) : ?>
                        <p>
                            <button href="link_Mercado/equip_comprar.php?item=<?= $item["cod_item"]; ?>"
                                    class="link_send btn btn-success">
                                Comprar
                            </button>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Essa ilha não tem nenhum equipamento a venda</p>
    <?php endif; ?>

    <h3>Itens no seu invetário:</h3>

    <?php $items = get_result_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", "tb_item_equipamentos", "cod_equipamento", TIPO_ITEM_EQUIPAMENTO,
        "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]); ?>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <?php $preco = preco_venda_equipamento($item); ?>
            <?php
            if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
                $preco += $aumento * $preco;
                if ($preco >= preco_compra_equipamento($item)) {
                    $preco = preco_compra_equipamento($item) - 1;
                }
            }
            ?>
            <div class="list-group-item col-md-2">
                <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                <div>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                </div>
                <p>
                    <button href="Mercado/equip_vender.php?item=<?= $item["cod_item"] ?>"
                            data-question="Deseja mesmo vender este equipamento?"
                            class="link_confirm btn btn-primary">
                        Vender
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</div>