<?php
$pagina = 1;
$topicos_por_pagina = 30;

if (isset($_GET["pagina"]) && validate_number($_GET["pagina"])) {
    $pagina = $_GET["pagina"];
}
?>
<div class="panel-heading">
    Notícias
</div>

<div class="panel-body">
    <?php if ($userDetails->tripulacao) {
        $topicos = $connection->run(
            "SELECT *, (SELECT count(*) FROM tb_noticia_comment c WHERE n.cod_noticia = c.noticia_id) AS comentarios 
             FROM tb_noticias n
             LEFT JOIN tb_noticia_lida l ON l.noticia_id = n.cod_noticia AND l.tripulacao_id = ?
             ORDER BY n.criacao DESC 
             LIMIT ?, ?",
            "iii", array($userDetails->tripulacao["id"], ($pagina - 1) * $topicos_por_pagina, $topicos_por_pagina)
        );
    } else {
        $topicos = $connection->run(
            "SELECT *, NULL AS data_leitura, 0 AS comentarios FROM tb_noticias n
             ORDER BY n.criacao DESC 
             LIMIT ?, ?",
            "ii", array(($pagina - 1) * $topicos_por_pagina, $topicos_por_pagina)
        );
    } ?>
    <div class="list-group">
        <?php while ($noticia = $topicos->fetch_array()): ?>
            <a class="list-group-item link_content"
               href="./?ses=noticia&cod=<?= $noticia["cod_noticia"] ?>">
                <div class="media">
                    <div class="media-left hidden-xs">
                        <?php if ($noticia["banner"]): ?>
                            <img src="<?= $noticia["banner"] ?>"
                                 style="display: block;object-fit: cover;height: 70px; width: 200px;">
                        <?php endif; ?>
                    </div>
                    <p class="visible-xs">
                        <?php if ($noticia["banner"]): ?>
                            <img src="<?= $noticia["banner"] ?>"
                                 style="display: block;object-fit: cover;height: 70px; width: 200px; margin: auto">
                        <?php endif; ?>
                    </p>
                    <div class="media-body">
                        <h5 class="list-group-item-heading">
                            <div class="<?= !$noticia["data_leitura"] ? "text-warning" : "" ?>">
                                <?= $noticia["nome"] ?>
                                <?php if (!$noticia["data_leitura"]): ?>
                                    <span class="label label-warning">Novo!</span>
                                <?php endif; ?>
                            </div>
                        </h5>
                        <p class="pull-right">
                            <i class="fa fa-comment-o"></i>
                            <?= $noticia["comentarios"] ?>
                        </p>
                        <p class="list-group-item-text">
                            <?= date("d/m/Y H:m", strtotime($noticia["criacao"])) ?>
                        </p>
                    </div>
                </div>
            </a>
        <?php endwhile; ?>
    </div>

    <?php if ($pagina > 1): ?>
        <button class="btn btn-info link_content"
                href="./?ses=noticias&pagina=<?= $pagina - 1 ?>">
            <i class="fa fa-chevron-left"></i> Anterior
        </button>
    <?php endif; ?>

    Página <?= $pagina ?>

    <button class="btn btn-info link_content"
            href="./?ses=noticias&pagina=<?= $pagina + 1 ?>">
        Próxima <i class="fa fa-chevron-right"></i>
    </button>
</div>