<div class="panel-heading">
    Akuma Book
    <?= ajuda("Akuma Book", "Este é o livro que contém informações sobre todas as Akuma no Mi encontradas.") ?>
</div>

<div class="panel-body">

    <?php
    if (! isset($_GET["p"]) || ! validate_number($_GET["p"])) {
        $p = 0;
    } else {
        $p = $_GET["p"];
    }
    $p *= 10;

    $akumas = DataLoader::load("akumas");

    $total = count($akumas);

    $page = array_slice($akumas, $p, 10);
    ?>

    <ul class="list-group">
        <? foreach ($page as $akuma) : ?>
            <li class="list-group-item">
                <div class="media">
                    <div class="media-left">
                        <img src="Imagens/Itens/<?= $akuma["icon"] ?>.jpg" />
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= $akuma["nome"]; ?>
                        </h4>

                        <span class="label label-<?= label_tipo_akuma($akuma["tipo"]) ?>">
                            <?= nome_tipo_akuma($akuma["tipo"]) ?>
                        </span>
                        &nbsp;
                        <span class="label label-<?= label_categoria_akuma($akuma["categoria"]) ?>">
                            <?= nome_categoria_akuma($akuma["categoria"]) ?>
                        </span>
                        <div>
                            <?= $akuma["descricao"]; ?>
                        </div>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <?php $p /= 10; ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($p > 0) : ?>
                <li>
                    <a href="./?ses=akumaBook&p=<?= $p - 1 ?>" class="link_content">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($total) : ?>
                <?php for ($i = 0; $i < floor($total / 10); $i++) : ?>
                    <?php if ($i >= 0) : ?>
                        <li>
                            <a href="./?ses=akumaBook&p=<?= $i ?>" aria-label="link_content"
                                class="<?= $i == $p ? "active" : "" ?> link_content">
                                <?= $i + 1 ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
            <?php if ($p < floor($total / 10)) : ?>
                <li>
                    <a href="./?ses=akumaBook&p=<?= $p + 1 ?>" class="link_content">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
