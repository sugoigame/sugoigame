<div class="panel-heading">
    Combinações
</div>

<div class="panel-body">
    <?php $combinacoes_forja = $connection->run("SELECT * FROM tb_combinacoes_artesao")->fetch_all_array(); ?>
    <?php $materiais_bd = $connection->run("SELECT * FROM tb_item_reagents")->fetch_all_array(); ?>
    <?php $equipamentos_bd = $connection->run("SELECT * FROM tb_equipamentos")->fetch_all_array(); ?>

    <?php
    $materiais = array();
    foreach ($materiais_bd as $material) {
        $materiais[$material["cod_reagent"]] = $material;
    }
    $equipamentos = array();
    foreach ($equipamentos_bd as $equip) {
        $equipamentos[$equip["item"]] = $equip;
    }
    ?>

    <table class="table table-stripped">
        <thead>
        <tr>
            <th>Item 1</th>
            <th>Item 2</th>
            <th>Item 3</th>
            <th>Item 4</th>
            <th>Item 5</th>
            <th>Item 6</th>
            <th>Item 7</th>
            <th>Item 8</th>
            <th>lvl</th>
            <th>Aleatório</th>
            <th>Resultado</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($combinacoes_forja as $combinacao): ?>
            <?php if ($combinacao["aleatorio"]): ?>
                <?php $resultados = $connection->run("SELECT * FROM tb_combinacoes_forja_aleatorio WHERE receita = ?",
                    "i", $combinacao["cod_receita"])->fetch_all_array(); ?>
            <?php else: ?>
                <?php $resultados = array(); ?>
            <?php endif; ?>
            <tr>
                <?php for ($i = 1; $i <= 8; $i++): ?>
                    <td>
                        <?php if ($combinacao[$i]): ?>
                            <?php if ($combinacao[$i . "_t"] != TIPO_ITEM_REAGENT): ?>
                                Tipo <?= $combinacao[$i . "_t"] ?>
                            <?php else: ?>
                                <img src="Imagens/Itens/<?= $materiais[$combinacao[$i]]["img"] ?>.png"/><br/>
                                <?= $combinacao[$i] ?> - <?= $materiais[$combinacao[$i]]["nome"] ?> x<?= $combinacao[$i . "_q"] ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                <?php endfor; ?>
                <td><?= $combinacao["lvl"] ?></td>
                <td><?= $combinacao["aleatorio"] ? "sim" : "nao" ?></td>
                <td class="col-md-1">
                    <?php if ($combinacao["tipo"] == TIPO_ITEM_REAGENT): ?>
                        <img src="Imagens/Itens/<?= $materiais[$combinacao["cod"]]["img"] ?>.png"/><br/>
                        <?= $combinacao["cod"] ?> - <?= $materiais[$combinacao["cod"]]["nome"] ?> x<?= $combinacao["quant"] ?>
                    <?php elseif (!$combinacao["aleatorio"] && $combinacao["tipo"] == TIPO_ITEM_EQUIPAMENTO): ?>
                        <img src="Imagens/Itens/<?= $equipamentos[$combinacao["cod"]]["img"] ?>.png"/><br/>
                        <span class="equipamentos_casse_<?= $equipamentos[$combinacao["cod"]]["categoria"] ?>">
                            <?= $combinacao["cod"] ?> - <?= $equipamentos[$combinacao["cod"]]["nome"] ?>
                            x<?= $combinacao["quant"] ?>
                        </span>
                    <?php elseif ($combinacao["aleatorio"] && $combinacao["tipo"] == TIPO_ITEM_EQUIPAMENTO): ?>
                        <?php foreach ($resultados as $resultado): ?>
                            <img src="Imagens/Itens/<?= $equipamentos[$resultado["cod"]]["img"] ?>.png"/><br/>
                            <span class="equipamentos_casse_<?= $equipamentos[$resultado["cod"]]["categoria"] ?>">
                                <?= $resultado["cod"] ?> - <?= $equipamentos[$resultado["cod"]]["nome"] ?>
                                x<?= $resultado["quant"] ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?= $combinacao["cod"] ?> - <?= $combinacao["tipo"] ?> x<?= $combinacao["quant"] ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>