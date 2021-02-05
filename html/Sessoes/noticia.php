<?php
$noticia_id = $protector->get_number_or_exit("cod");

$noticia = $connection->run("SELECT * FROM tb_noticias WHERE cod_noticia = ?", "i", array($noticia_id));
if ($noticia->count()) {
    $noticia = $noticia->fetch_array();
} else {
    $protector->exit_error("Notícia inválida");
}

if ($userDetails->tripulacao) {
    $leitura = $connection->run("SELECT * FROM tb_noticia_lida WHERE noticia_id = ? AND tripulacao_id = ?",
        "ii", array($noticia_id, $userDetails->tripulacao["id"]));

    if (!$leitura->count()) {
        $connection->run("INSERT INTO tb_noticia_lida (tripulacao_id, noticia_id) VALUE (?, ?)",
            "ii", array($userDetails->tripulacao["id"], $noticia_id));
    }
}
?>
<div class="panel-heading">
    <h4><?= $noticia["nome"] ?></h4>
</div>

<div class="panel-body">
    <?php if ($noticia["banner"]): ?>
        <div class="text-center">
            <img src="<?= $noticia["banner"] ?>"
                 style="max-height: 300px; width: auto; max-width: 100%; margin: auto auto 20px;">
        </div>
    <?php endif; ?>
    <div class="text-left">
        <div>
            <?= $noticia["texto"] ?>
        </div>
        <br/>
        <p>
            <button class="btn btn-info link_content" href="./?ses=noticias">
                Voltar para as notícias
            </button>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode("https://sugoigame.com.br/?ses=noticia&cod=$noticia_id") ?>&t=<?= $noticia["nome"] ?>"
               title="Compartilhe no Facebook" target="_blank" class="btn btn-info">
                <i class="fa fa-facebook-official"></i>
                Compartilhar
            </a>
        </p>
    </div>
    <br/>
    <?php if ($userDetails->tripulacao): ?>
        <h3>Comentários</h3>
        <?php $posts = $connection->run(
            "SELECT p.*, u.*, pers.nome, pers.img, pers.skin_r, pers.skin_c, pers.borda,
          IF (pers.sexo = 0, t.nome, t.nome_f) AS titulo, pers.fama_ameaca,
         (SELECT count(*) FROM tb_noticia_likes l WHERE l.comment_id = p.id AND l.tipo = 1) AS likes,
         (SELECT count(*) FROM tb_noticia_likes l WHERE l.comment_id = p.id AND l.tipo = 2) AS deslikes,
         l.tipo AS i_like
         FROM tb_noticia_comment p
         LEFT JOIN tb_usuarios u ON p.tripulacao_id = u.id
         LEFT JOIN tb_personagens pers ON u.cod_personagem = pers.cod
         LEFT JOIN tb_titulos t ON pers.titulo = t.cod_titulo
         LEFT JOIN tb_noticia_likes l ON l.comment_id = p.id AND l.tripulacao_id = ?
         WHERE p.noticia_id = ?
         ORDER BY data_criacao ",
            "ii", array($userDetails->tripulacao["id"], $noticia_id)
        ); ?>
        <?php while ($post = $posts->fetch_array()): ?>
            <div class="list-group-item <?= $post["adm"] ? "text-info" : "" ?>">
                <?php if ($post["oculto"]): ?>
                    <a role="button" data-toggle="collapse" href="#post-<?= $post["id"] ?>" aria-expanded="false">
                        Um comentário foi ocultado.
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
                                <?php if ($userDetails->tripulacao["adm"]): ?>
                                    <?php if ($post["oculto"]): ?>
                                        <button class="btn btn-info link_send"
                                                href="link_Forum/ocultar_noticia.php?noticia=<?= $post["id"] ?>">
                                            <i class="fa fa-eye"></i> Exibir
                                        </button>
                                    <?php else: ?>
                                        <button class="btn btn-info link_send"
                                                href="link_Forum/ocultar_noticia.php?noticia=<?= $post["id"] ?>">
                                            <i class="fa fa-eye-slash"></i> Ocultar
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($post["tripulacao_id"] != $userDetails->tripulacao["id"]): ?>
                                    <button class="btn btn-success link_send" data-toggle="tooltip"
                                            href="link_Forum/like_noticia.php?noticia=<?= $post["id"] ?>&tipo=1"
                                            title="<?= $post["likes"] ?> curtiram"
                                        <?= $post["i_like"] == 1 ? "disabled" : "" ?>>
                                        <i class="fa fa-thumbs-up"></i> <?= $post["likes"] ?>
                                    </button>
                                    <button class="btn btn-danger link_send" data-toggle="tooltip"
                                            href="link_Forum/like_noticia.php?noticia=<?= $post["id"] ?>&tipo=2"
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
                            <div>
                                <?= $post["conteudo"] ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
        <br/>
        <div>
            <h4>Deixe seu comentário</h4>
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
                    <form class="ajax_form" method="POST" action="Forum/repply_noticia">
                        <input type="hidden" name="noticia" value="<?= $noticia_id ?>">
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
</div>