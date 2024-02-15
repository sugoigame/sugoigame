<?php
include '/Funcoes/getimage.php'
?>

<div class="panel-heading">
    Reagentes
</div>

<div class="panel-body">
    <?php 
    // Carregar os dados dos reagentes
    $materiais_bd = $result = MapLoader::load("reagents"); 

    // Inicializar o array de reagentes
    $materiais = array();
    
    // Preencher o array de reagentes
    foreach ($materiais_bd as $material) {
        if (is_array($material) && isset($material["cod_reagent"])) {
            $materiais[$material["cod_reagent"]] = $material;
        }
    }
    ?>

    <table class="table table-stripped">
        <thead>
            <tr>
                <th>cod_reagent</th>
                <th>img</th>
                <th>nome</th>
                <th>descricao</th>
                <th>mergulho</th>
                <th>zona</th>
                <th>mining</th>
                <th>madeira</th>
                <th>preco</th>
                <th>method</th>
                <th>img_format</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materiais as $material): ?>
                <tr>
                    <td><?= $material["cod_reagent"] ?></td>
                    <td><?php get_image($material); ?></td>
                    <td><?= $material["nome"] ?></td>
                    <td><?= $material["descricao"] ?></td>
                    <td><?= $material["mergulho"] ?></td>
                    <td><?= $material["zona"] ?></td>
                    <td><?= $material["mining"] ?></td>
                    <td><?= $material["madeira"] ?></td>
                    <td><?= $material["preco"] ?></td>
                    <td><?= $material["method"] ?></td>
                    <td><?= $material["img_format"] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
