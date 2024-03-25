<div class="panel-heading">
    Adicionar ouro
</div>

<div class="panel-body">
    <?php
    // Consulta SQL para obter as tripulações
    $acc = $connection->run("SELECT * FROM tb_conta")->fetch_all_array();
    ?>
    <form class="form-inline ajax_form" action="Adm/inserir-gold" method="post">
        <label>ID da conta</label>
        <select name="id_conta" id="id_conta">
            <?php foreach ($acc as $conta): ?>
                <option value="<?php echo $conta['conta_id']; ?>"><?php echo $conta['email']; ?></option>
            <?php endforeach; ?>
        </select>
        <label>Quantidade de ouro</label>
        <input type="text" name="quantidade_ouro" class="form-control">
        <button type="submit" class="btn btn-success">Enviar</button>
    </form>
</div>
