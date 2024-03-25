<div class="panel-heading">
    Adicionar ou remover beta
</div>

<div class="panel-body">
    <?php
    // Consulta SQL para obter as tripulações
    $trip = $connection->run("SELECT * FROM tb_conta ")->fetch_all_array();
    ?>
    <form class="form-inline ajax_form" action="Adm/add-beta" method="post">
        <label>ID da conta</label>
        <select name="id_conta" id="id_conta">
            <?php foreach ($trip as $tripulacao): ?>
                <option value="<?php echo $tripulacao['conta_id']; ?>"><?php echo $tripulacao['email']; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Beta (1 sim 0 nao)</label>
        <input type="number" name="beta" class="form-control" min="0" max="1">
        <button type="submit" class="btn btn-success">Enviar</button>
    </form>
</div>
