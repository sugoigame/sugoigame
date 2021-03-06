<div class="panel-heading">
    Fóruns
</div>

<div class="panel-body">

    <h4>Bem-vindos aos fóruns oficiais do Sugoi Game</h4>

    <h4>Tópicos comentados recentemente:</h4>

    <?php $topicos = $connection->run(
        "SELECT 
          t.*, 
          u.*, 
          (SELECT count(*) FROM tb_forum_post p WHERE p.topico_id = t.id) AS comments,
          (SELECT p.data_criacao FROM tb_forum_post p WHERE p.topico_id = t.id ORDER BY p.data_criacao DESC LIMIT 1) AS last_comment, 
          l.data_leitura
         FROM tb_forum_topico t
         LEFT JOIN tb_usuarios u ON t.criador_id = u.id
         LEFT JOIN tb_forum_topico_lido l ON l.topico_id = t.id AND l.tripulacao_id = ?
         ORDER BY last_comment DESC 
         LIMIT 8",
        "i", array($userDetails->tripulacao["id"])
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

    <!--<form class="form-inline text-right ajax_form">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Buscar em todos os fóruns">
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">
                    <i class="fa fa-search"></i>
                </button>
            </span>
        </div>
    </form>-->
    <?php $agrupamentos = array(
        1 => "SUPORTE",
        2 => "COMUNIDADE",
        3 => "JOGABILIDADE E GUIAS",
        4 => "PvP",
        5 => "CLASSES"
    ); ?>

    <?php foreach ($agrupamentos as $agrupamento_id => $agrupamento): ?>
        <h4 class="text-left"><?= $agrupamento ?></h4>
        <?php $categorias = $connection->run(
            "SELECT *, 
              (SELECT count(*) FROM tb_forum_topico p WHERE p.categoria_id = c.id) AS topics,
              (SELECT count(*) FROM tb_forum_topico p INNER JOIN tb_forum_topico_lido l ON p.id = l.topico_id AND l.tripulacao_id = ? WHERE p.categoria_id = c.id) AS topics_lidos 
             FROM tb_forum_categoria c 
             WHERE c.agrupamento = ?",
            "ii", array($userDetails->tripulacao["id"], $agrupamento_id)
        ); ?>
        <div class="row">
            <?php while ($categoria = $categorias->fetch_array()): ?>
                <div class="col-md-4 list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <h2>
                                <i class="<?= $categoria["icon"] ?>"></i>
                            </h2>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <a class="link_content" href="./?ses=forumTopics&categoria=<?= $categoria["id"] ?>">
                                    <?= $categoria["nome"] ?>
                                </a>
                            </h4>
                            <p><?= $categoria["descricao"] ?></p>
                            <p><?= $categoria["topics"] - $categoria["topics_lidos"] ?> Tópicos não lidos</p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <br/>
    <?php endforeach; ?>
</div>