<div class="panel-heading">
    Natal no Sugoi Game
</div>

<div class="panel-body">
    <h4>Enfrente as Renas do Papai Noel para ganhar recompensas!</h4>

    <p>
        As Renas do Papai Noel estão escondidas pelo mundo dando presentes aos jogadores capazes de derrota-las!
        Procure-as e derrote-as para ganhar recompensas!
    </p>
    <p>
        A localização das Renas ainda é um segredo e é seu dever encontrá-las, por isso é bom ficar de olhos bem
        atentos.
    </p>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php $recompensas = DataLoader::load("recompensas_natal"); ?>
    <?php
    $derrotados = [];
    $todos_ids = [];
    foreach ($recompensas as $id => $recompensa) {
        if (isset($recompensa["rdm_id"])) {
            $todos_ids[] = $recompensa["rdm_id"];
            $derrotados[$id] = $connection->run("SELECT sum(quant) AS total FROM tb_pve WHERE id= ? AND zona = ?",
                "ii", array($userDetails->tripulacao["id"], $recompensa["rdm_id"]))->fetch_array()["total"];
        }
    }
    $derrotados[10] = $connection->run("SELECT count(*) AS total FROM tb_pve WHERE id= ? AND zona IN (" . (implode(",", $todos_ids)) . ")",
        "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];
    ?>

    <?php render_recompensas($recompensas, $derrotados, "", "", "Eventos/natal.php", "tb_evento_amizade_recompensa"); ?>


    <?php $derrotados = $userDetails->get_item(204, TIPO_ITEM_REAGENT); ?>
    <?php $derrotados = $derrotados ? $derrotados["quant"] : 0; ?>

    <br/>
    <br/>
    <br/>

    <h4>Enfrente outros jogadores em batalhas PvP para ganhar doces de Natal!</h4>

    <p>A cada tripulante adversário derrotado em uma batalha PvP de qualquer tipo, você ganha 1 Doce de Natal</p>

    <h3>Você possui <?= $derrotados ?> <img src="Imagens/Itens/373.png"> Doces de Natal</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php
    $recompensas = DataLoader::load("recompensas_loja_natal");

    $reagents = get_reagents_for_recompensa();
    $equipamentos = get_reagents_for_recompensa();
    ?>
    <div class="row">
        <?php foreach ($recompensas as $id => $recompensa): ?>
            <div class="list-group-item col-xs-4 col-md-4">
                <?php if (isset($recompensa["haki"])): ?>
                    <p>
                        <i class="fa fa-certificate"></i>
                        <?= mascara_numeros_grandes($recompensa["haki"]) ?> pontos de Haki para distribuir entre os
                        tripulantes
                    </p>
                <?php endif; ?>
                <?php if (isset($recompensa["xp"])): ?>
                    <p>
                        <?= mascara_numeros_grandes($recompensa["xp"]) ?> pontos de experiência para toda a tripulação
                    </p>
                <?php endif; ?>
                <?php if (isset($recompensa["img"]) && isset($recompensa["skin"])): ?>
                    <p>Aparência exclusiva</p>
                    <p>
                        <?= icon_pers_skin($recompensa["img"], $recompensa["skin"]) ?>
                    </p>
                    <p>
                        <?= big_pers_skin($recompensa["img"], $recompensa["skin"]) ?>
                    </p>
                <?php endif; ?>
                <?php if (isset($recompensa["tipo_item"])): ?>
                    <?php if ($recompensa["tipo_item"] == TIPO_ITEM_REAGENT): ?>
                        <div class="clearfix">
                            <div class="equipamentos_casse_1" style="display: inline-block; margin: auto">
                                <img src="Imagens/Itens/<?= $reagents[$recompensa["cod_item"]]["img"] ?>.<?= $reagents[$recompensa["cod_item"]]["img_format"] ?>">
                            </div>
                            <p>
                                <?= $reagents[$recompensa["cod_item"]]["nome"] ?>
                                x <?= $recompensa["quant"] ?>
                            </p>
                        </div>
                    <?php elseif ($recompensa["tipo_item"] == TIPO_ITEM_EQUIPAMENTO): ?>
                        <div class="clearfix">
                            <?= info_item_with_img($equipamentos[$recompensa["cod_item"]], $equipamentos[$recompensa["cod_item"]], FALSE, FALSE, FALSE) ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (isset($recompensa["skin_navio"])): ?>
                    <p>Aparência de navio exclusiva</p>
                    <?php render_navio_skin($userDetails->tripulacao["bandeira"], $userDetails->tripulacao["faccao"], $recompensa["skin_navio"]); ?>
                <?php endif; ?>
                <br/>
                <p>
                    Preço: <?= $recompensa["preco"] ?>
                    <img src="Imagens/Itens/373.png">
                </p>
                <p>
                    <button class="btn btn-success link_confirm" href="Eventos/loja_natal.php?rec=<?= $id ?>"
                            data-question="Deseja comprar este item?"
                        <?= $derrotados >= $recompensa["preco"] ? "" : "disabled" ?>>
                        Comprar
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>