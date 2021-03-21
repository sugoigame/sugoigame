<style type="text/css">
    .equipamentos_casse_1 {
        color: white;
    }
</style>
<div class="panel-heading">
    Combinações
</div>

<div class="panel-body">
    <?php $equipamentos = $connection->run("SELECT * FROM tb_equipamentos ORDER BY categoria ASC, lvl ASC")->fetch_all_array(); ?>
    <table class="table table-striped table-hover">
        <thead>
        <tr>
            <th width="50" class="text-center">Id</th>
            <th colspan="2">Nome</th>
            <th width="100" class="text-center">Nível</th>
            <th width="150" class="text-center">Classe</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $equipamentos as $equipamento): ?>
            <tr>
                <td style="vertical-align: middle;" class="text-center"><?php echo $equipamento['item'] ?></td>
                <td><img src="Imagens/Itens/<?= $equipamento["img"] ?>.png"/></td>
                <td style="vertical-align: middle;">
                    <span class="equipamentos_casse_<?= $equipamento["categoria"] ?>">
                        <?php echo $equipamento['nome'] ?>
                    </span><br />
                    <small><?php echo $equipamento['descricao'] ?></small>
                </td>
                <td style="vertical-align: middle;" class="text-center"><?php echo $equipamento ['lvl'] ?></td>
                <td style="vertical-align: middle;" class="text-center">
                    <?php
                    if ($equipamento['requisito'] == '1')        echo 'Espadachim';
                    elseif ($equipamento['requisito'] == '2')    echo 'Lutador';
                    elseif ($equipamento['requisito'] == '3')    echo 'Atirador';
                    else                                         echo 'Todas';
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>