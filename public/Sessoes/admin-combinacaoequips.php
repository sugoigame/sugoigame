<?php
include '/Funcoes/getimage.php'
?>

<div class="panel-heading">
    Equipamentos
</div>

<div class="panel-body">
    <?php 
    // Carregar os dados dos reagentes
    $equipamento_bd = $result = MapLoader::load("equipamentos"); 

    // Inicializar o array de reagentes
    $equipamentos = array();
    
    // Preencher o array de reagentes
    foreach ($equipamento_bd as $equip) {
        if (is_array($equip) && isset($equip["item"])) {
            $equipamentos[$equip["item"]] = $equip;
        }
    }
    ?>

    <table class="table table-stripped">
        <thead>
            <tr>
                <th>id</th>
                <th>img</th>
                <th>nome</th>
                <th>descrição</th>
               
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipamentos as $equip): ?>
                <tr>
                    <td><?= $equip["item"] ?></td>
                    <td><?php get_image($equip); ?></td>
                    <td><?= $equip["nome"] ?></td>
                    <td><?= $equip["descricao"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
