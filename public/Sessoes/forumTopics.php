<?php

$categoria_id = $protector->get_number_or_exit("categoria");

$categoria = $connection->run("SELECT * FROM tb_forum_categoria WHERE id = ?", "i", array($categoria_id));
if ($categoria->count()) {
    $categoria = $categoria->fetch_array();
} else {
    $protector->exit_error("Categoria inválida");
}

$pagina = 1;
$topicos_por_pagina = 30;

if (isset($_GET["pagina"]) && validate_number($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

?>
<div class="panel-heading">
    <h4><?= $categoria["nome"] ?></h4>
</div>

<div class="panel-body">
    <h5><?= $categoria["descricao"] ?></h5>

    <div class="text-right">
        <!--<form class="form-inline ajax_form">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar por">
                <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
            </div>
        </form>
        <br/>-->
        <p>
            <button class="btn btn-info link_content" href="./?ses=forum">
                <i class="fa fa-arrow-left"></i> Voltar aos fóruns
            </button>
            <button class="btn btn-success link_content" href="./?ses=forumNewTopic&categoria=<?= $categoria_id ?>">
                <i class="fa fa-plus"></i> Novo Tópico
            </button>
        </p>
    </div>

    <?php $topicos = $connection->run(
        "SELECT t.*, u.*, (SELECT count(*) FROM tb_forum_post p WHERE p.topico_id = t.id) AS comments, l.data_leitura
         FROM tb_forum_topico t
         LEFT JOIN tb_usuarios u ON t.criador_id = u.id
         LEFT JOIN tb_forum_topico_lido l ON l.topico_id = t.id AND l.tripulacao_id = ?
         WHERE t.categoria_id = ?
         ORDER BY u.adm DESC, t.data_criacao DESC 
         LIMIT ?, ?",
        "iiii", array($userDetails->tripulacao["id"], $categoria_id, ($pagina - 1) * $topicos_por_pagina, $topicos_por_pagina)
    ); ?>
    <?php while ($topico = $topicos->fetch_array()): ?>
        <div class="list-group-item text-left">
            <div class="row">
                <div class="col-md-1" data-toggle="tooltip"
                     title="<?= $topico["adm"] ? "Tópico da Taticus" : "" ?>">
                    <h4>
                        <i class="<?= $topico["adm"] ? "glyphicon glyphicon-tower text-info" : "fa fa-file" ?>"></i>
                    </h4>
                </div>
                <div class="col-md-5">
                    <h4>
                        <a class="link_content <?= $topico["data_leitura"] ? "" : "text-warning" ?>"
                           href="./?ses=forumPosts&topico=<?= $topico["id"] ?>">
                            <?= $topico["nome"] ?>
                        </a>
                        <i class="<?= $topico["bloqueado"] ? "fa fa-lock" : "" ?>" data-toggle="tooltip"
                           title="Bloqueado"></i>
                        <i class="<?= $topico["resolvido"] ? "fa fa-check text-success" : "" ?>" data-toggle="tooltip"
                           title="Resolvido"></i>
                    </h4>
                </div>
                <div class="col-md-1">
                    <p class="<?= $topico["comments"] > 1 ? "" : "text-muted" ?>">
                        <i class="fa fa-comment-o"></i>
                        <?= $topico["comments"] - 1 ?>
                    </p>
                </div>
                <div class="col-md-3">
                    <h4><?= $topico["tripulacao"] ?></h4>
                </div>
                <div class="col-md-2">
                    <?= date("d/m/Y H:m", strtotime($topico["data_criacao"])) ?>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    <br/>

    <?php if ($pagina > 1): ?>
        <button class="btn btn-info link_content"
                href="./?ses=forumTopics&categoria=<?= $categoria_id ?>&pagina=<?= $pagina - 1 ?>">
            <i class="fa fa-chevron-left"></i> Anterior
        </button>
    <?php endif; ?>

    Página <?= $pagina ?>

    <button class="btn btn-info link_content"
            href="./?ses=forumTopics&categoria=<?= $categoria_id ?>&pagina=<?= $pagina + 1 ?>">
        Próxima <i class="fa fa-chevron-right"></i>
    </button>
</div>