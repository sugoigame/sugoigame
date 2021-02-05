<div class="panel-heading">
    Semana da Amizade
</div>

<div class="panel-body">
    <?= ajuda("Semana da Amizade", "Celebre a semana da amizade diariamente com seus companheiros."); ?>

    <h4>Brinde com sua tripulação todos os dias para ganhar diversos bonus diferentes!</h4>

    <p>
        <button class="btn btn-success link_send" href="link_Eventos/amizade_brindar_tripulacao.php">
            Brindar com minha tripulação
        </button>
    </p>

    <?php $recompensas = DataLoader::load("recompensas_amizade"); ?>
    <?php $derrotados = $connection->run("SELECT count(id) AS total FROM tb_evento_amizade_brindes WHERE tripulacao_id = ?",
        "i", $userDetails->tripulacao["id"])->fetch_array()["total"]; ?>

    <h4>Brinde com os moradores de diferentes ilhas do jogo para ganhar recompensas</h4>
    <p>Você ganha um baú do tesouro a cada ilha diferente que visitar e brindar com os moradores</p>
    <?php if ($userDetails->ilha["ilha"]): ?>
        <p>
            <button class="btn btn-success link_send" href="link_Eventos/amizade_brindar_ilha.php">
                Brindar com os moradores da ilha
            </button>
        </p>
    <?php else: ?>
        <p>Visite alguma ilha para poder brindar com os moradores</p>
    <?php endif; ?>

    <h3>Até agora você já brindou com <?= $derrotados ?> moradores de ilhas diferentes</h3>

    <p>Você receberá uma recompensa por cada ilha diferente que fizer um brind. Quando brindar com até 7 ilhas
        diferentes, você poderá receber as recompensas listadas a baixo.</p>

    <p>
        <a href="./?ses=calendario" class="link_content">
            Confira o calendário do jogo para acompanhar a duração do evento
        </a>
    </p>

    <div class="row">
        <?php foreach ($recompensas as $id => $recompensa) : ?>
            <div class="col-md-12 list-group-item">
                <h4>Brinde com <?= $recompensa["minimo"] ?> moradores de ilhas diferentes</h4>
                <h5>Recompensa:</h5>
                <?php if ($recompensa["tipo"] == "berries"): ?>
                    <p>
                        <img src="Imagens/Icones/Berries.png"/> <?= mascara_berries($recompensa["quant"]) ?>
                    </p>
                <?php elseif ($recompensa["tipo"] == "dobrao"): ?>
                    <p>
                        <img src="Imagens/Icones/Dobrao.png"/> <?= $recompensa["quant"] ?>
                    </p>
                <?php elseif ($recompensa["tipo"] == "haki"): ?>
                    <p>
                        <?= $recompensa["quant"] ?> pontos de Haki para toda a tripulação
                    </p>
                <?php elseif ($recompensa["tipo"] == "akuma"): ?>
                    <p>
                        <img src="Imagens/Itens/100.png"> Akuma no Mi Aleatória
                    </p>
                <?php elseif ($recompensa["tipo"] == "alcunha"): ?>
                    <p>
                        Alcunha surpresa exclusiva
                    </p>
                <?php elseif ($recompensa["tipo"] == "skin"): ?>
                    <p>Aparência exclusiva</p>
                    <p>
                        <img src="Imagens/Personagens/Icons/<?= get_img(array("img" => $recompensa["img"], "skin_r" => $recompensa["skin"]), "r") ?>.jpg">
                    </p>
                    <p>
                        <img src="Imagens/Personagens/Big/<?= get_img(array("img" => $recompensa["img"], "skin_c" => $recompensa["skin"]), "c") ?>.jpg">
                    </p>
                <?php elseif ($recompensa["tipo"] == "moeda_evento"): ?>
                    <p>
                        <?= $recompensa["quant"] ?>
                        <img src="Imagens/Icones/MoedaEvento.png" data-toggle="tooltip"
                             title="Medalha de Evento. Pode ser usada para comprar recompensas especiais na Loja de Eventos">
                        Medalha de Evento
                    </p>
                <?php elseif ($recompensa["tipo"] == "effect"): ?>
                    <p>
                        Animação de habilidade: <?= $recompensa["effect"] ?>
                        <button class="play-effect btn btn-info" data-effect="<?= $recompensa["effect"] ?>">
                            <i class="fa fa-play"></i>
                        </button>
                    </p>
                <?php endif; ?>
                <p>
                    500 pontos de experiência para toda a tripulação
                </p>
                <?php if ($derrotados >= $recompensa["minimo"]): ?>
                    <?php $recompensado = $connection->run("SELECT count(*) AS total FROM tb_evento_amizade_recompensa WHERE tripulacao_id = ? AND recompensa_id = ?",
                        "ii", array($userDetails->tripulacao["id"], $id))->fetch_array()["total"]; ?>

                    <?php if (!$recompensado): ?>
                        <p>
                            <button class="btn btn-success link_send" href="link_Eventos/amizade.php?rec=<?= $id ?>">
                                Obter recompensa
                            </button>
                        </p>
                    <?php else: ?>
                        <p class="text-success">
                            <i class="fa fa-check"></i> Você já adquiriu essa recompensa!
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>