<div class="panel-heading">
    Combinações
</div>

<div class="panel-body">
    <?php $equipamentos = $connection->run("SELECT * FROM tb_item_equipamentos GROUP BY item ORDER BY lvl ASC")->fetch_all_array(); ?>
    

    <table class="table table-stripped">
        <thead>
        <tr>
            <th>ID item</th>
            <th colspan="2">Nome</th>
            <th>level</th>
            <th>Classes</th>
            <th>Categoria do equipamento(cor)</th>
            <th>Categoria do dano</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $equipamentos as $equipamento): ?>
           <tr>
            <td><?php echo $equipamento ['item'] ?></td>
            <td><img src="Imagens/Itens/<?= $equipamento["img"] ?>.png"/></td>
            <td><span class="equipamentos_casse_<?= $equipamento["categoria"] ?>">
                <?php echo $equipamento ['nome'] ?></span></td>
            <td><?php echo $equipamento ['lvl'] ?></td>
            <td><?php echo $equipamento ['requisito'] ?></td>
            <td><?php echo $equipamento ['categoria'] ?></td>
            <td><?php echo $equipamento ['cat_dano'] ?></td>
           </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>