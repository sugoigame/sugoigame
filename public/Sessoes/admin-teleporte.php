<div class="panel-heading">
    Teleportar tripulação
</div>

<div class="panel-body">
    <?php
    // Consulta SQL para obter as tripulações
    $acc = $connection->run("SELECT * FROM tb_usuarios ORDER BY id DESC")->fetch_all_array();
    ?>
    <form class="form-inline ajax_form" action="Adm/teleporte" method="post">
        <label>ID da tripulação</label>
        <select name="id_trip" id="id_trip">
            <?php foreach ($acc as $tripulacao): ?>
                <option value="<?php echo $tripulacao['id']; ?>"><?php echo $tripulacao['tripulacao']; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Cordenada X</label>
        <input type="text" name="x" class="form-control">
        <label>Cordenada Y</label>
        <input type="text" name="y" class="form-control">
        <button type="submit" class="btn btn-success">Enviar</button>
    </form>
</div>
