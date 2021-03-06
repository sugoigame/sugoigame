<div class="panel-heading">
    Ranking
</div>

<div class="panel-body">
    <?= ajuda("Ranking", "Conheça os melhores jogadores do Sugoi Game") ?>

    <div class="row">
        <div class="col-xs-6 col-md-6">
            <a href="./?ses=era" class="link_content btn btn-info btn-block">
                Ver recompensas pela Grande Era dos Piratas
            </a>
        </div>
        <div class="col-xs-6 col-md-6">

            <a href="./?ses=batalhaPoderes" class="link_content btn btn-info btn-block">
                Ver recompensas pela Batalha dos Grandes Poderes
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

    $faccao = NULL;
    if (isset($_GET["faccao"]) && ($_GET["faccao"] == FACCAO_MARINHA || $_GET["faccao"] == FACCAO_PIRATA)) {
        $faccao = $_GET["faccao"];
    }

    $total = 0;
    ?>
    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="<?= $ranking == "reputacao" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=reputacao" class="link_content">Era dos Piratas</a>
            </li>
            <li class="<?= $ranking == "reputacao_mensal" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=reputacao_mensal" class="link_content">Grandes Poderes</a>
            </li>
            <!--<li class="<?= $ranking == "incursao" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=incursao" class="link_content">Incursão à Alubarna</a>
            </li>-->
            <li class="<?= $ranking == "fa" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=fa" class="link_content">Recompensa</a>
            </li>
        </ul>
        <ul class="nav nav-pills nav-justified" style="margin-bottom:5px">
        <li class="<?= $ranking == "espadachins" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=espadachins" class="link_content">Espadachins</a>
            </li>
            <li class="<?= $ranking == "lutadores" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=lutadores" class="link_content">Lutadores</a>
            </li>
            <li class="<?= $ranking == "atiradores" ? "active" : "" ?>">
                <a href="./?ses=ranking&rank=atiradores" class="link_content">Atiradores</a>
            </li>
        </ul>
    </div>
    <?php if ($ranking != "incursao") : ?>
        <div>
            <ul class="nav nav-pills nav-justified">
                <li class="<?= $faccao === NULL ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>" class="link_content">Todas facções</a>
                </li>
                <li class="<?= $faccao !== NULL && $faccao == FACCAO_MARINHA ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= FACCAO_MARINHA ?>" class="link_content">Marinheiros</a>
                </li>
                <li class="<?= $faccao !== NULL && $faccao == FACCAO_PIRATA ? "active" : "" ?>">
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= FACCAO_PIRATA ?>" class="link_content">Piratas</a>
                </li>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($ranking == "reputacao") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_reputacao ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_reputacao ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>
        <h5>
            Atenção: Apenas os jogadores que já participaram de pelo menos 10 batalhas PvP durante essa Era aparecem no
            Ranking
        </h5>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img data-toggle="tooltip" data-placement="bottom" title="<?= get_patente_nome($rnk["faccao"], $rnk["reputacao"]) ?>" src="Imagens/Ranking/Patentes/<?= $rnk["faccao"] . "_" . get_patente_id($rnk["reputacao"]) ?>.png" />
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>Reputação: <?= $rnk["reputacao"] ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif ($ranking == "reputacao_mensal") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_reputacao_mensal ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_reputacao_mensal ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>
        <h5>
            Atenção: Apenas os jogadores que já participaram de pelo menos 3 batalhas PvP durante esse Mês aparecem no
            Ranking
        </h5>
        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>Reputação: <?= $rnk["reputacao"] ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif ($ranking == "fa") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_fa ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_fa INNER JOIN tb_personagens ON tb_ranking_fa.cod=tb_personagens.cod ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Personagens/Icons/<?= get_img($rnk, "r"); ?>.jpg" />
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>
                        <img src="Imagens/Icones/Berries.png" /> <?= mascara_berries(calc_recompensa($rnk["fama_ameaca"])) ?>
                    </p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif ($ranking == "espadachins") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_score_espadachim ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_score_espadachim INNER JOIN tb_personagens ON tb_ranking_score_espadachim.cod=tb_personagens.cod ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Personagens/Icons/<?= get_img($rnk, "r"); ?>.jpg" />
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>Score: <?= $rnk["score"] ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php elseif ($ranking == "lutadores") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_score_lutador ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_score_lutador INNER JOIN tb_personagens ON tb_ranking_score_lutador.cod=tb_personagens.cod ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Personagens/Icons/<?= get_img($rnk, "r"); ?>.jpg" />
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>Score: <?= $rnk["score"] ?></p>
                </li>
            <?php endwhile; ?>
        </ul>

    <?php elseif ($ranking == "atiradores") : ?>
        <?php
        $query = "SELECT count(posicao) AS total FROM tb_ranking_score_atirador ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $total = $connection->run($query)->fetch_array()["total"];
        ?>

        <?php
        $query = "SELECT * FROM tb_ranking_score_atirador INNER JOIN tb_personagens ON tb_ranking_score_atirador.cod=tb_personagens.cod ";
        if ($faccao !== NULL) {
            $query .= " WHERE faccao='$faccao' ";
        }
        $query .= " ORDER BY posicao LIMIT $limit_start, 25 ";
        $result = $connection->run($query);
        ?>

        <ul class="list-group">
            <?php while ($rnk = $result->fetch_array()) : ?>
                <li class="list-group-item">
                    <h4>
                        <img src="Imagens/Personagens/Icons/<?= get_img($rnk, "r"); ?>.jpg" />
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $rnk["bandeira"]; ?>&f=<?= $rnk["faccao"]; ?>" />
                        <?= $rnk["posicao"] ?>º <?= $rnk["nome"] ?>
                    </h4>
                    <p>Score: <?= $rnk["score"] ?></p>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php endif; ?>

    <nav aria-label="Page navigation">
        <ul class="pagination">
            <?php if ($page > 0) : ?>
                <li>
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $page - 1 ?>" class="link_content">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($total) : ?>
                <?php for ($i = $page - 3; $i <= $page + 3 && $i < floor($total / 25); $i++) : ?>
                    <?php if ($i >= 0) : ?>
                        <li class="<?= $i == $page ? "active" : "" ?>">
                            <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $i ?>" class="link_content">
                                <span aria-hidden="true"><?= $i + 1 ?></span>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
            <?php endif; ?>
            <?php if ($page < floor($total / 25)) : ?>
                <li>
                    <a href="./?ses=ranking&rank=<?= $ranking ?>&faccao=<?= $faccao ?>&page=<?= $page + 1 ?>" class="link_content">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>