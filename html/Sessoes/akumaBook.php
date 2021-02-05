<div class="panel-heading">
    Akuma Book
</div>

<div class="panel-body">
    <?= ajuda("Akuma Book", "Este é o livro que contém informações sobre todas as Akuma no Mi encontradas.") ?>

    <?php
    $result = $connection->run("SELECT count(akm.cod_akuma) AS `count` FROM tb_akuma akm
INNER JOIN tb_personagens per ON per.akuma = akm.cod_akuma
INNER JOIN tb_usuarios usr ON per.id = usr.id
WHERE usr.adm = 0");

    if (!isset($_GET["p"]) || !validate_number($_GET["p"])) {
        $p = 0;
    } else {
        $p = $_GET["p"];
    }
    $p *= 10;

    $total = $result->fetch_array()["count"];

    if ($total):
        $result = $connection->run(
            "SELECT 
                akm.img AS img, 
                akm.nome AS akm_nome, 
                per.nome AS per_nome, 
                akm.descricao AS descricao,
                akm.tipo AS tipo
            FROM tb_akuma AS akm 
            INNER JOIN tb_personagens AS per ON per.akuma = akm.cod_akuma
            INNER JOIN tb_usuarios AS usr ON per.id = usr.id
            WHERE usr.adm = 0
            ORDER BY cod_akuma DESC LIMIT $p, 10"
        );
        ?>

        <ul class="list-group">
            <? while ($akuma = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <div class="media">
                        <div class="media-left">
                            <img src="Imagens/Itens/<?= $akuma["img"] ?>.png"/>
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading">
                                <? echo $akuma["akm_nome"]; ?> - <? echo $akuma["per_nome"]; ?>
                                <span class="label label-success"><?= nome_tipo_akuma($akuma["tipo"]) ?></span>
                            </h4>
                            <?= $akuma["descricao"]; ?>
                        </div>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>
            Nenhuma Akuma foi encontrada ainda...
        </p>
    <?php endif; ?>
    <?php $p /= 10; ?>
    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($p > 0): ?>
                <li>
                    <a href="./?ses=akumaBook&p=<?= $p - 1 ?>" class="link_content">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($total): ?>
                <?php for ($i = $p - 3; $i <= $p + 3 && $i < floor($total / 10); $i++): ?>
                    <?php if ($i >= 0): ?>
                        <li>
                            <a href="./?ses=akumaBook&p=<?= $i ?>" aria-label="link_content"
                               class="<?= $i == $p ? "active" : "" ?> link_content">
                                <?= $i + 1 ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
            <?php if ($p < floor($total / 10)): ?>
                <li>
                    <a href="./?ses=akumaBook&p=<?= $p + 1 ?>" class="link_content">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>