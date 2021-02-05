<?php

$topico_id = $protector->get_number_or_exit("topico");

$topico = $connection->run(
    "SELECT *, (SELECT count(*) FROM tb_forum_post p WHERE p.topico_id = c.id) AS comments  FROM tb_forum_topico c WHERE c.id = ?",
    "i", array($topico_id)
);
if ($topico->count()) {
    $topico = $topico->fetch_array();
} else {
    $protector->exit_error("Tópico inválido");
}

$pagina = 1;
$posts_por_pagina = 30;

if (isset($_GET["pagina"]) && validate_number($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}

$leitura = $connection->run("SELECT * FROM tb_forum_topico_lido WHERE topico_id = ? AND tripulacao_id = ?",
    "ii", array($topico_id, $userDetails->tripulacao["id"]));

if (!$leitura->count()) {
    $connection->run("INSERT INTO tb_forum_topico_lido (tripulacao_id, topico_id) VALUE (?, ?)",
        "ii", array($userDetails->tripulacao["id"], $topico_id));
}

?>
<div class="panel-heading">
    <h4><?= $topico["nome"] ?></h4>
</div>

<style type="text/css">
    .post-content {
        word-break: break-word;
    }
</style>

<div class="panel-body">

    <div class="text-right">
        <p>
            <?php if ($topico["criador_id"] == $userDetails->tripulacao["id"] || $userDetails->tripulacao["adm"]): ?>
                <?php if ($topico["resolvido"]): ?>
                    <button class="btn btn-success link_send" href="link_Forum/resolver.php?topico=<?= $topico_id ?>">
                        <i class="fa fa-times"></i> Marcar como não resolvido
                    </button>
                <?php else: ?>
                    <button class="btn btn-success link_send" href="link_Forum/resolver.php?topico=<?= $topico_id ?>">
                        <i class="fa fa-check"></i> Marcar como resolvido
                    </button>
                <?php endif; ?>
                <?php if ($topico["bloqueado"]): ?>
                    <button class="btn btn-warning link_send" href="link_Forum/bloquear.php?topico=<?= $topico_id ?>">
                        <i class="fa fa-unlock-alt"></i> Desbloquear o tópico
                    </button>
                <?php else: ?>
                    <button class="btn btn-warning link_send" href="link_Forum/bloquear.php?topico=<?= $topico_id ?>">
                        <i class="fa fa-lock"></i> Bloquear o tópico
                    </button>
                <?php endif; ?>
            <?php endif; ?>
            <button class="btn btn-info link_content" href="./?ses=forumTopics&categoria=<?= $topico["categoria_id"] ?>">
                <i class="fa fa-arrow-left"></i> Voltar ao fórum
            </button>
        </p>
    </div>

    <?php if ($topico["resolvido"]): ?>
        <p class="text-success">
            Este tópico já foi resolvido.
        </p>
    <?php endif; ?>
    <?php if ($topico["bloqueado"]): ?>
        <p class="text-warning">
            Este tópico está bloqueado
        </p>
    <?php endif; ?>

    <?php $posts = $connection->run(
        "SELECT p.*, u.*, pers.nome, pers.img, pers.skin_r, pers.skin_c, pers.borda,
          IF (pers.sexo = 0, t.nome, t.nome_f) AS titulo, pers.fama_ameaca,
         (SELECT count(*) FROM tb_forum_likes l WHERE l.post_id = p.id AND l.tipo = 1) AS likes,
         (SELECT count(*) FROM tb_forum_likes l WHERE l.post_id = p.id AND l.tipo = 2) AS deslikes,
         l.tipo AS i_like
         FROM tb_forum_post p
         LEFT JOIN tb_usuarios u ON p.tripulacao_id = u.id
         LEFT JOIN tb_personagens pers ON u.cod_personagem = pers.cod
         LEFT JOIN tb_titulos t ON pers.titulo = t.cod_titulo
         LEFT JOIN tb_forum_likes l ON l.post_id = p.id AND l.tripulacao_id = ?
         WHERE p.topico_id = ?
         ORDER BY p.data_criacao ASC 
         LIMIT ?, ?",
        "iiii", array($userDetails->tripulacao["id"], $topico_id, ($pagina - 1) * $posts_por_pagina, $posts_por_pagina)
    ); ?>
    <?php while ($post = $posts->fetch_array()): ?>
        <div class="list-group-item <?= $post["adm"] ? "text-info" : "" ?>">
            <?php if ($post["oculto"]): ?>
                <a role="button" data-toggle="collapse" href="#post-<?= $post["id"] ?>" aria-expanded="false">
                    Uma resposta foi ocultada.
                </a>
            <?php endif; ?>
            <div id="post-<?= $post["id"] ?>" class="<?= $post["oculto"] ? "collapse opacity50" : "" ?>">
                <div class="row">
                    <div class="col-md-3">
                        <?php if ($post["adm"]): ?>
                            <h4 data-toggle="tooltip" title="Game Master">
                                <?= $post["nome"] ?>
                                <i class="glyphicon glyphicon-tower"></i>
                            </h4>
                            <?= big_pers_skin($post["img"], $post["skin_c"], isset($post["borda"]) ? $post["borda"] : 0, "hidden-xs hidden-sm", 'style="max-width: 100%; margin: auto;"') ?>
                            <img style="max-width: 100%; margin: auto;" class="visible-xs visible-sm"
                                 src="Imagens/Personagens/Icons/<?= getImg($post, "r"); ?>.jpg">
                            <p>Governo Mundial</p>
                        <?php else: ?>
                            <h4><?= $post["nome"] ?> - <?= $post["titulo"] ?></h4>
                            <?php render_cartaz_procurado($post, $post["faccao"]); ?>
                            <p><?= $post["tripulacao"] ?></p>
                            <p>Reputação: <?= mascara_berries($post["reputacao"]) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9 text-left">
                        <div class="pull-right">
                            <?php if ($topico["criador_id"] == $userDetails->tripulacao["id"]
                                || $post["tripulacao_id"] != $userDetails->tripulacao["id"]
                                || $userDetails->tripulacao["adm"]
                            ): ?>
                                <?php if ($post["oculto"]): ?>
                                    <button class="btn btn-info link_send"
                                            href="link_Forum/ocultar.php?post=<?= $post["id"] ?>">
                                        <i class="fa fa-eye"></i> Exibir
                                    </button>
                                <?php else: ?>
                                    <button class="btn btn-info link_send"
                                            href="link_Forum/ocultar.php?post=<?= $post["id"] ?>">
                                        <i class="fa fa-eye-slash"></i> Ocultar
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($post["tripulacao_id"] != $userDetails->tripulacao["id"]): ?>
                                <button class="btn btn-success link_send" data-toggle="tooltip"
                                        href="link_Forum/like.php?post=<?= $post["id"] ?>&tipo=1"
                                        title="<?= $post["likes"] ?> curtiram"
                                    <?= $post["i_like"] == 1 ? "disabled" : "" ?>>
                                    <i class="fa fa-thumbs-up"></i> <?= $post["likes"] ?>
                                </button>
                                <button class="btn btn-danger link_send" data-toggle="tooltip"
                                        href="link_Forum/like.php?post=<?= $post["id"] ?>&tipo=2"
                                        title="<?= $post["deslikes"] ?> não curtiram"
                                    <?= $post["i_like"] == 2 ? "disabled" : "" ?>>
                                    <i class="fa fa-thumbs-down"></i> <?= $post["deslikes"] ?>
                                </button>
                            <?php else: ?>
                                <span class="text-success" data-toggle="tooltip"
                                      title="<?= $post["likes"] ?> curtiram">
                                    <i class="fa fa-thumbs-up"></i> <?= $post["likes"] ?>
                                </span>
                                <span class="text-danger" data-toggle="tooltip"
                                      title="<?= $post["deslikes"] ?> não curtiram">
                                    <i class="fa fa-thumbs-down"></i> <?= $post["deslikes"] ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-muted">
                            <?= date("d/m/Y H:m", strtotime($post["data_criacao"])) ?>
                        </p>
                        <br/>
                        <div class="post-content">
                            <?= $post["conteudo"] ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
    <br/>

    <?php if (!$topico["bloqueado"]): ?>
        <div>
            <h4>Junte-se à conversa</h4>
            <div class="row <?= $userDetails->tripulacao["adm"] ? "text-info" : "" ?>">
                <div class="col-md-3 text-center">
                    <?php if ($userDetails->tripulacao["adm"]): ?>
                        <h4 data-toggle="tooltip" title="Game Master">
                            <?= $userDetails->capitao["nome"] ?>
                            <i class="glyphicon glyphicon-tower"></i>
                        </h4>
                        <img style="max-width: 100%; margin: auto;" class="hidden-xs hidden-sm"
                             src="Imagens/Personagens/Big/<?= getImg($userDetails->capitao, "c"); ?>.jpg">
                        <img style="max-width: 100%; margin: auto;" class="visible-xs visible-sm"
                             src="Imagens/Personagens/Icons/<?= getImg($userDetails->capitao, "r"); ?>.jpg">
                        <p>Governo Mundial</p>
                    <?php else: ?>
                        <h4><?= $userDetails->capitao["nome"] ?></h4>
                        <?php render_cartaz_procurado($userDetails->capitao, $userDetails->tripulacao["faccao"]); ?>
                        <p><?= $userDetails->tripulacao["tripulacao"] ?></p>
                        <p>Reputação: <?= mascara_berries($userDetails->tripulacao["reputacao"]) ?></p>
                    <?php endif; ?>
                </div>
                <div class="col-md-9 text-right">
                    <form class="ajax_form" method="POST" action="Forum/repply">
                        <input type="hidden" name="topico" value="<?= $topico["id"] ?>">
                        <textarea id="response" name="response"></textarea>
                        <br/>
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-reply"></i> Responder
                        </button>
                    </form>
                    <script type="text/javascript">
                        $(function () {
                            CKEDITOR.replace('response');
                        });
                    </script>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <br/>

    <?php if ($pagina > 1): ?>
        <button class="btn btn-info link_content"
                href="./?ses=forumPosts&topico=<?= $topico_id ?>&pagina=<?= $pagina - 1 ?>">
            <i class="fa fa-chevron-left"></i> Anterior
        </button>
    <?php endif; ?>

    Página <?= $pagina ?>

    <button class="btn btn-info link_content"
            href="./?ses=forumPosts&topico=<?= $topico_id ?>&pagina=<?= $pagina + 1 ?>">
        Próxima <i class="fa fa-chevron-right"></i>
    </button>
    <button class="btn btn-info link_content"
            href="./?ses=forumPosts&topico=<?= $topico_id ?>&pagina=<?= floor($topico["comments"] / $posts_por_pagina) + 1 ?>">
        Ir para última página <i class="fa fa-forward"></i>
    </button>
</div>