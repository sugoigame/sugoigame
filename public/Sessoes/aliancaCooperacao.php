<div class="panel-heading">
    Cooperação
</div>

<div class="panel-body">
    <?= ajuda("Cooperação", "Ao ajudar sua Frota/Aliança a vencer guerras, você ganha pontos de cooperação que podem
    ser trocados por ítens exclusivos aqui.") ?>

    <?php $items = get_many_results_joined_mapped_by_type("tb_alianca_shop", "cod", "tipo", array(
        array("nome" => "tb_item_acessorio", "coluna" => "cod_acessorio", "tipo" => TIPO_ITEM_ACESSORIO),
    ), " WHERE origem.faccao = ? OR origem.faccao = 3", "i", array($userDetails->tripulacao["faccao"])); ?>

    <?php foreach ($items as $key => $item) {
        if ($item["tipo"] == TIPO_ITEM_REMEDIO) {
            $items[$key] = array_merge($item, MapLoader::find("remedios", ["cod_remedio" => $item["cod_item"]]));
        } elseif ($item["tipo"] == TIPO_ITEM_COMIDA) {
            $items[$key] = array_merge($item, MapLoader::find("comidas", ["cod_comida" => $item["cod_item"]]));
        }
    } ?>

    <h4>Você tem
        <?= $userDetails->ally["cooperacao"] ?> pt(s) de Cooperação
    </h4>

    <div class="row">
        <?php foreach ($items as $item) : ?>
            <div class="list-group-item col-md-4">
                <h5>
                    <?= get_img_item($item) ?><br>
                    <?= $item["nome"] ?>
                </h5>
                <p>
                    Requer
                    <?= ($userDetails->tripulacao["faccao"] == 0) ? "Frota" : "Aliança"; ?>
                    nível
                    <?= $item["lvl"]; ?>
                </p>
                <p>Custa
                    <?= $item["preco"]; ?> pt(s) de Cooperação
                </p>
                <?php if ($userDetails->ally["lvl"] >= $item["lvl"] and $userDetails->ally["cooperacao"] >= $item["preco"]) : ?>
                    <p>
                        <button href="link_Alianca/alianca_shop_comprar.php?item=<?= $item["cod"] ?>&tipo=<?= $item["tipo"] ?>"
                            class="link_send btn btn-success">
                            Comprar
                        </button>
                    </p>
                <? endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
