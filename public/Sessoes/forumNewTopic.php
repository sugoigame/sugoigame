<?php

$categoria_id = $protector->get_number_or_exit("categoria");

$categoria = $connection->run("SELECT * FROM tb_forum_categoria WHERE id = ?", "i", array($categoria_id));
if ($categoria->count()) {
    $categoria = $categoria->fetch_array();
} else {
    $protector->exit_error("Categoria inválida");
}

?>
<div class="panel-heading">
    <h4><?= $categoria["nome"] ?></h4>
</div>

<div class="panel-body">
    <h5>Criar um novo tópico</h5>

    <div class="text-right">
        <p>
            <button class="btn btn-info link_content" href="./?ses=forumTopics&categoria=<?= $categoria_id ?>">
                <i class="fa fa-arrow-left"></i> Voltar ao fórum
            </button>
        </p>
    </div>

    <form class="ajax_form" method="POST" action="Forum/new_topic">
        <input type="hidden" name="categoria" value="<?= $categoria_id ?>">

        <div class="form-group">
            <label>Título do tópico:</label>
            <input class="form-control" name="nome" minlength="4" required>
        </div>

        <div class="form-group">
            <label>Mensagem:</label>
            <textarea id="response" name="response"></textarea>
        </div>
        <br/>
        <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> Criar
        </button>
    </form>
    <script type="text/javascript">
        $(function () {
            CKEDITOR.replace('response');
        });
    </script>
</div>