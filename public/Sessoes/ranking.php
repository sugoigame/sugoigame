<div class="panel-heading">
    Ranking
    <?= ajuda_tooltip("Conheça os melhores jogadores do Sugoi Game") ?>
</div>

<div class="panel-body">

    <div class="row">
        <div class="col-xs-6 col-md-6">
            <a href="./?ses=era" class="link_content btn btn-info btn-block">
                Ver recompensas da Grande Era dos Piratas
            </a>
        </div>
        <div class="col-xs-6 col-md-6">

            <a href="./?ses=batalhaPoderes" class="link_content btn btn-info btn-block">
                Ver recompensas da Batalha pelos Poneglyphs
            </a>
        </div>
        <div class="col-md-12">
            <hr>
        </div>
    </div>

    <?php
    $ranking = "reputacao";
    if (isset($_GET["rank"]) && validate_alphanumeric($_GET["rank"])) {
        $ranking = $_GET["rank"];
    }
    $page = 0;
    if (isset($_GET["page"]) && validate_number($_GET["page"])) {
        $page = $_GET["page"];
    }
    $limit_start = $page * 25;

    $faccao = null;
    if (isset($_GET["faccao"]) && ($_GET["faccao"] == FACCAO_MARINHA || $_GET["faccao"] == FACCAO_PIRATA)) {
        $faccao = $_GET["faccao"];
    }

    $total = 0;
    ?>
    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="<?= $ranking == "reputacao" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=reputacao" class="link_content">
                    Era dos Piratas
                </a>
            </li>
            <li class="<?= $ranking == "reputacao_mensal" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=reputacao_mensal" class="link_content">
                    Batalha pelos Poneglyphs
                </a>
            </li>
        </ul>
    </div>
    <?php if ($ranking != "incursao") : ?>
        <div>
            <ul class="nav nav-pills nav-justified">
                <li class="<?= $faccao === null ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>" class="link_content">Todas facções</a>
                </li>
                <li class="<?= $faccao !== null && $faccao == FACCAO_MARINHA ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= FACCAO_MARINHA ?>"
                        class="link_content">Marinheiros</a>
                </li>
                <li class="<?= $faccao !== null && $faccao == FACCAO_PIRATA ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= FACCAO_PIRATA ?>"
                        class="link_content">Piratas</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($ranking == "reputacao") : ?>
        <?php
        $query = "SELECT count(*) AS total FROM tb_usuarios ";
        if ($faccao !== null) {
            $query .= " WHERE faccao='$faccao' AND adm = 0";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT *, @rownum := @rownum + 1 as posicao FROM tb_usuarios, ( SELECT @rownum := 0 ) AS r ";
        if ($faccao !== null) {
            $query .= " WHERE faccao='$faccao' AND adm = 0 ";
        }
        $query .= " ORDER BY reputacao DESC LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] + $limit_start ?>º
                        <?= $rnk["tripulacao"] ?>
                    </h4>
                    <p>
                        Road Poneglyphs:
                        <?= $rnk["reputacao"] ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif ($ranking == "reputacao_mensal") : ?>
        <?php
        $query = "SELECT count(*) AS total FROM tb_usuarios ";
        if ($faccao !== null) {
            $query .= " WHERE faccao='$faccao' AND adm = 0";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT *, @rownum := @rownum + 1 as posicao FROM tb_usuarios, ( SELECT @rownum := 0 ) AS r ";
        if ($faccao !== null) {
            $query .= " WHERE faccao='$faccao' AND adm = 0 ";
        }
        $query .= " ORDER BY reputacao_mensal DESC LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>
        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º
                        <?= $rnk["tripulacao"] ?>
                    </h4>
                    <p>
                        Poneglyphs:
                        <?= $rnk["reputacao_mensal"] ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 0) : ?>
                <li>
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $page - 1 ?>"
                        class="link_content">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($total) : ?>
                <?php for ($i = $page - 3; $i <= $page + 3 && $i < floor($total / 25); $i++) : ?>
                    <?php if ($i >= 0) : ?>
                        <li class="<?= $i == $page ? "active" : "" ?>">
                            <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $i ?>" class="link_content">
                                <span aria-hidden="true">
                                    <?= $i + 1 ?>
                                </span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
            <?php if ($page < floor($total / 25)) : ?>
                <li>
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $page + 1 ?>"
                        class="link_content">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
