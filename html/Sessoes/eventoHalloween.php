<div class="panel-heading">
    Semana do Terror
</div>

<div class="panel-body">
    <?= ajuda("Navios fantasmas estão aparecendo por toda parte!", "Encontre e derrote os Navios Fantasmas para ganhar recompensas!"); ?>

    <?php $especial = $connection->run("SELECT * FROM tb_evento_amizade_recompensa WHERE tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->count(); ?>

    <?php if (!$especial): ?>
        <h3>
            Recompensa especial!
        </h3>
        <button class="btn btn-success link_send" href="link_Eventos/halloween_especial.php">
            Receber recompensa especial
        </button>
    <?php endif; ?>

    <?php $derrotados = $userDetails->get_item(195, TIPO_ITEM_REAGENT); ?>
    <?php $derrotados = $derrotados ? $derrotados["quant"] : 0; ?>

    <h4>Encontre e derrote os Navios Fantasmas para conseguir Doces de Halloween, utilize esses doces para comprar
        recompensas!</h4>

    <h3>Você possui <?= $derrotados ?> <img src="Imagens/Itens/345.jpg"> Doces de Haloween</h3>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <?php
    $recompensas = DataLoader::load("recompensas_halloween");

    $reagents = get_reagents_for_recompensa();
    $equipamentos = get_reagents_for_recompensa();
    ?>
    <div class="row">
        <?php foreach ($recompensas as $id => $recompensa): ?>
            <div class="list-group-item col-md-4">
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
                    <img src="Imagens/Itens/345.jpg">
                </p>
                <p>
                    <button class="btn btn-success link_confirm" href="Eventos/halloween.php?rec=<?= $id ?>"
                            data-question="Deseja comprar este item?"
                        <?= $derrotados >= $recompensa["preco"] ? "" : "disabled" ?>>
                        Comprar
                    </button>
                </p>
            </div>
        <?php endforeach; ?>
    </div>