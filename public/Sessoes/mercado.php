<div class="panel-heading">
    Mercado
</div>

<div class="panel-body">
    <?= ajuda("Mercado", "No mercado você pode comprar e vender itens.") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="active">
                <a href="./?ses=mercado" class="link_content">Acessórios</a>
            </li>
            <li>
                <a href="./?ses=equipShop" class="link_content">Equipamentos</a>
            </li>
            <li>
                <a href="./?ses=materiais" class="link_content">Materiais</a>
            </li>
        </ul>
    </div>

    <?php $mods = $connection->run("SELECT * FROM tb_ilha_mod WHERE ilha = ?", "i", $userDetails->ilha["ilha"])->fetch_array(); ?>

    <?php $items = get_result_joined_mapped_by_type("tb_ilha_itens", "cod_item", "tipo_item", "tb_item_acessorio", "cod_acessorio", TIPO_ITEM_ACESSORIO,
        "WHERE ilha = ?", "i", $userDetails->ilha["ilha"]); ?>

    <h3>Acessórios a venda:</h3>

    <?php if (count($items)): ?>
        <div class="row">
            <?php foreach ($items as $item) : ?>
                <?php $preco = preco_compra_acessorio($item); ?>
                <div class="list-group-item col-md-2">
                    <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                    <div>
                        <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                    </div>
                    <?php if ($userDetails->tripulacao["berries"] >= $preco) : ?>
                        <p>
                            <button href="Mercado/mercado_comprar.php?item=<?= $item["cod_item"]; ?>"
                                    data-question="Deseja comprar esse item?"
                                    class="link_confirm btn btn-success">
                                Comprar
                            </button>
                        </p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Essa ilha não tem nenhum item a venda</p>
    <?php endif; ?>

    <h3>Itens no seu invetário:</h3>

    <?php $items = get_many_results_joined_mapped_by_type("tb_usuario_itens", "cod_item", "tipo_item", array(
        array("nome" => "tb_item_acessorio", "coluna" => "cod_acessorio", "tipo" => TIPO_ITEM_ACESSORIO),
        array("nome" => "tb_item_navio_casco", "coluna" => "cod_casco", "tipo" => TIPO_ITEM_CASCO),
        array("nome" => "tb_item_navio_leme", "coluna" => "cod_leme", "tipo" => TIPO_ITEM_LEME),
        array("nome" => "tb_item_navio_velas", "coluna" => "cod_velas", "tipo" => TIPO_ITEM_VELAS)
    ), "WHERE origem.id = ?", "i", $userDetails->tripulacao["id"]);

    // Logias
    $result = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = 8 ORDER BY cod_item",
        "i", $userDetails->tripulacao["id"]);

    while ($item = $result->fetch_array()) {
        $items[] = array_merge($item, array(
            "nome" => "Akuma no Mi Logia",
            "descricao" => "Permite que o personagem aprenda 5 novas habilidades passivas, 3 novos buffs e 3 novos ataques.",
            "tipo" => "Logia",
            "categoria" => 6,
            "preco" => 6500000,
            "img" => substr($item["cod_item"], -3, 3)
        ));
    }

    // Paramecias
    $result = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = 9 ORDER BY cod_item",
        "i", $userDetails->tripulacao["id"]);

    while ($item = $result->fetch_array()) {
        $items[] = array_merge($item, array(
            "nome" => "Akuma no Mi Paramecia",
            "descricao" => "Permite que o personagem aprenda 4 novas habilidades passivas, 4 novos buffs e 3 novos ataques.",
            "tipo" => "Paramecia",
            "categoria" => 6,
            "preco" => 6500000,
            "img" => substr($item["cod_item"], -3, 3)
        ));
    }

    // Zoan
    $result = $connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = 10 ORDER BY cod_item",
        "i", $userDetails->tripulacao["id"]);

    while ($item = $result->fetch_array()) {
        $items[] = array_merge($item, array(
            "nome" => "Akuma no Mi Zoan",
            "descricao" => "Permite que o personagem aprenda 4 novas habilidades passivas, 5 novos buffs e 2 novos ataques.",
            "tipo" => "Zoan",
            "categoria" => 6,
            "preco" => 6500000,
            "img" => substr($item["cod_item"], -3, 3)
        ));
    }

    ?>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <?php $preco = ($item["tipo_item"] == 0 ? preco_venda_acessorio($item) : $item["preco"] * 0.5); ?>
            <?php
            if ($aumento = $userDetails->buffs->get_efeito("aumento_preco_venda_ilha")) {
                $preco += $aumento * $preco;

                if ($item["tipo_item"] == 0) {
                    if ($preco >= preco_compra_acessorio($item)) {
                        $preco = preco_compra_acessorio($item) - 1;
                    }
                } else {
                    if ($preco >= $item["preco"]) {
                        $preco = $item["preco"] - 1;
                    }
                }
            }
            ?>
            <div class="list-group-item col-md-2">
                <?= info_item_with_img($item, $item, FALSE, FALSE, FALSE) ?>
                <div>
                    <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($preco) ?>
                </div>
                <p>
                    <button href="Mercado/mercado_vender.php?item=<?= $item["cod_item"] ?>&tipo=<?= $item["tipo_item"] ?>"
                            data-question="Deseja vender esse item?"
                            class="link_confirm btn btn-primary">
                        Vender
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>

</div>