<div class="panel-heading">
    Aparência do Navio
</div>

<div class="panel-body">
    <div>
        <?php render_navio_icon(); ?>
    </div>
    <h4>
        <?= $userDetails->navio["nome"]; ?>
    </h4>

    <p>
        <?= $userDetails->navio["descricao"]; ?>
    </p>

    <div class="clearfix">
        <?php render_navio_hp_bar() ?>
        <?php render_navio_xp_bar() ?>
    </div>

    <?php if ($userDetails->tripulacao["credito_skin_navio"]): ?>
        <h3>Você tem direito à <?= $userDetails->tripulacao["credito_skin_navio"] ?> Aparência(s) Gratuita(s)</h3>
    <?php endif; ?>

    <h3>Aparência do Navio:</h3>
    <?php $minhas_skins = $connection->run("SELECT * FROM tb_tripulacao_skin_navio WHERE tripulacao_id = ?",
        "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
    <div class="list-group-item">
        <p>
            <?php render_navio_skin($userDetails->tripulacao["bandeira"], $userDetails->tripulacao["faccao"], 0); ?>
        </p>
        <?php if ($userDetails->tripulacao["skin_navio"] == 0): ?>
            Aparência ativa <i class="fa fa-check"></i>
        <?php else: ?>
            <button href="link_Navio/habilitar_skin.php?skin=0" class="link_send btn btn-success">
                Habilitar Aparência do navio
            </button>
        <?php endif; ?>
        <?php if ($userDetails->tripulacao["skin_tabuleiro_navio"] == 0): ?>
            Aparência do tabuleiro ativa <i class="fa fa-check"></i>
        <?php else: ?>
            <button href="link_Navio/habilitar_skin_tabuleiro.php?skin=0"
                    class="link_send btn btn-success">
                Habilitar Aparência do tabuleiro
            </button>
        <?php endif; ?>
    </div>
    <?php for ($skin = 1; $skin <= SKINS_NAVIO_MAX; $skin++): ?>
        <div class="list-group-item">
            <p>
                <?php render_navio_skin($userDetails->tripulacao["bandeira"], $userDetails->tripulacao["faccao"], $skin); ?>
            </p>
            <?php $has_skin = FALSE; ?>
            <?php foreach ($minhas_skins as $minha_skin): ?>
                <?php if ($minha_skin["skin_id"] == $skin): ?>
                    <?php if ($userDetails->tripulacao["skin_navio"] == $skin): ?>
                        Aparência do navio ativa <i class="fa fa-check"></i>
                    <?php else: ?>
                        <button href="link_Navio/habilitar_skin.php?skin=<?= $skin ?>"
                                class="link_send btn btn-success">
                            Habilitar Aparência do navio
                        </button>
                    <?php endif; ?>
                    <?php if ($userDetails->tripulacao["skin_tabuleiro_navio"] == $skin): ?>
                        Aparência do tabuleiro ativa <i class="fa fa-check"></i>
                    <?php else: ?>
                        <button href="link_Navio/habilitar_skin_tabuleiro.php?skin=<?= $skin ?>"
                                class="link_send btn btn-success">
                            Habilitar Aparência do tabuleiro
                        </button>
                    <?php endif; ?>
                    <?php $has_skin = TRUE; ?>
                    <?php break; ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (!$has_skin): ?>
                <?php if ($PRECO_GOLD_SKIN_NAVIO[$skin] > 0): ?>
                    <button href="Vip/comprar_skin_navio.php?skin=<?= $skin ?>&tipo=gold"
                            data-question="A aparência só precisa ser comprada uma única vez. Após comprada você poderá ativa-la ou desativa-la a vontade. Deseja comprar essa aparência?"
                            class="link_confirm btn btn-success"
                        <?= $userDetails->conta["gold"] < $PRECO_GOLD_SKIN_NAVIO[$skin] ? "disabled" : "" ?>>
                        <?= $PRECO_GOLD_SKIN_NAVIO[$skin] ?> <img src="Imagens/Icones/Gold.png"/> Comprar
                    </button>
                    <button href="Vip/comprar_skin_navio.php?skin=<?= $skin ?>&tipo=dobrao"
                            data-question="A aparência só precisa ser comprada uma única vez. Após comprada você poderá ativa-la ou desativa-la a vontade. Deseja comprar essa aparência?"
                            class="link_confirm btn btn-success"
                        <?= $userDetails->conta["dobroes"] < $PRECO_DOBRAO_SKIN_NAVIO[$skin] ? "disabled" : "" ?>>
                        <?= $PRECO_DOBRAO_SKIN_NAVIO[$skin] ?> <img src="Imagens/Icones/Dobrao.png"/> Comprar
                    </button>
                <?php else: ?>
                    <p>Essa é uma aparência rara obtida através de eventos e não pode ser comprada</p>
                <?php endif; ?>

                <?php if ($userDetails->tripulacao["credito_skin_navio"]): ?>
                    <button href="Vip/comprar_skin_navio.php?tipo=credito&skin=<?= $skin ?>"
                            data-question="A aparência só precisa ser comprada uma única vez. Após comprada você poderá ativa-la ou desativa-la a vontade. Deseja comprar essa aparência?"
                            class="btn btn-success link_confirm">
                        Adquirir gratuitamente
                    </button>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    <?php endfor; ?>
</div>