<?php function render_vantagem($img, $titulo, $descricao, $duracao, $preco_gold, $preco_dobrao, $link_gold, $link_dobrao) { ?>
    <?php global $userDetails; ?>
    <li class="list-group-item">
        <div class="row">
            <div class="col-xs-2 col-md-2">
                <img src="Imagens/Vip/<?= $img ?>" height="60px"/>
            </div>
            <div class="col-xs-7 col-md-7">
                <h4><?= $titulo ?></h4>
                <p><?= $descricao ?></p>
                <?php if ($duracao === FALSE): ?>
                    <p>
                        Instantâneo
                    </p>
                <?php else: ?>
                    <?php if ($duracao == 0 OR $duracao < atual_segundo()) : ?>
                        <p>
                            Duração: 30 dias
                        </p>
                    <?php else : ?>
                        <p class="text-success">
                            <i class="fa fa-check"></i> <span>Você já possui essa vantagem!</span>
                        </p>
                        <p>
                            Tempo Restante: <?= transforma_tempo_min($duracao - atual_segundo()) ?>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <div class="col-xs-3 col-md-3">
                <p>
                    <button href="<?= $link_gold ?>" class="link_confirm btn btn-success"
                            data-question="Deseja adquirir essa vantagem?"
                        <?= $userDetails->conta["gold"] < $preco_gold ? "disabled" : "" ?>>
                        <?= $preco_gold ?> <img src="Imagens/Icones/Gold.png"/>
                        <?= $duracao !== FALSE && ($duracao >= atual_segundo()) ? "Extender" : "Comprar" ?>
                    </button>
                </p>
                <p>
                    <button href="<?= $link_dobrao ?>" class="link_confirm btn btn-info"
                            data-question="Deseja adquirir essa vantagem?"
                        <?= $userDetails->conta["dobroes"] < $preco_dobrao ? "disabled" : "" ?>>
                        <?= $preco_dobrao ?> <img src="Imagens/Icones/Dobrao.png"/>
                        <?= $duracao !== FALSE && ($duracao >= atual_segundo()) ? "Extender" : "Comprar" ?>
                    </button>
                </p>
            </div>
        </div>
    </li>
<?php } ?>

<div class="panel-heading">Gold Shop</div>
<script type="text/javascript">
    $(function () {
        $("#renomeia_trip").click(function () {
            bootbox.prompt('Escreva um novo nome para sua tripulação:', function (input) {
                if (input) {
                    sendGet('Vip/reset_tripulacao.php?nome=' + input);
                }
            });
        });
        $("#renomeia_trip_dobrao").click(function () {
            bootbox.prompt('Escreva um novo nome para sua tripulação:', function (input) {
                if (input) {
                    sendGet('VipDobroes/reset_tripulacao.php?nome=' + input);
                }
            });
        });
    });
</script>
<div class="panel-body">
    <?= ajuda("O que é o Gold SHop", "Adquira vantagens exclusivas com suas moedas de ouro.") ?>

    <ul class="list-group">
        <?php render_vantagem(
            "tatics.png",
            "Táticas",
            "Defina uma posição fixa para cada tripulante antes de combates.",
            $userDetails->vip["tatic_duracao"],
            PRECO_GOLD_TATICAS,
            PRECO_DOBRAO_TATICAS,
            "Vip/tatics_comprar.php",
            "VipDobroes/tatics_comprar.php"
        ); ?>

        <?php render_vantagem(
            "luneta.png",
            "Luneta",
            "Aumenta o campo de visão no oceano em um quadro em cada direção.",
            $userDetails->vip["luneta_duracao"],
            PRECO_GOLD_LUNETA,
            PRECO_DOBRAO_LUNETA,
            "Vip/luneta_comprar.php",
            "VipDobroes/luneta_comprar.php"
        ); ?>

        <?php render_vantagem(
            "img.png",
            "Formações de tripulantes",
            "Permite criar e ativar formações de tripulantes fora do barco.",
            $userDetails->vip["formacoes_duracao"],
            PRECO_GOLD_USAR_FORMACOES,
            PRECO_DOBRAO_USAR_FORMACOES,
            "Vip/formacao_comprar.php?tipo=gold",
            "Vip/formacao_comprar.php?tipo=dobrao"
        ); ?>

        <?php/* render_vantagem(
            "atributos.png",
            "Conhecimento estratégico",
            "Permite ver os atributos, experiência de profissão, categoria de akuma e score dos seus tripulantes durante um combate. Exibe também os atributos dos personagens ao clicar nos respectivos cartazes de procurado no topo da tela.",
            $userDetails->vip["conhecimento_duracao"],
            PRECO_GOLD_CONHECIMENTO,
            PRECO_DOBRAO_CONHECIMENTO,
            "Vip/conhecimento_comprar.php?tipo=gold",
            "Vip/conhecimento_comprar.php?tipo=dobrao"
        ); */?>

        <?php render_vantagem(
            "coup-de-burst.gif",
            "Pacote de Coup De Burst diário",
            "Reduz em 10 segundos o tempo necessário para navegar 1 quadro da rota traçada. Pode ser usado 5 vezes por dia. Não pode ser usado se você estiver invisível. Não pode ser usado duas vezes no mesmo quadro.",
            $userDetails->vip["coup_de_burst_duracao"],
            PRECO_GOLD_COUP_DE_BURST,
            PRECO_DOBRAO_COUP_DE_BURST,
            "Vip/coup_de_burst_comprar.php?tipo=gold",
            "Vip/coup_de_burst_comprar.php?tipo=dobrao"
        ); ?>

        <?php/* render_vantagem(
            "ocultar.jpg",
            "Camuflagem",
            "Esconda seu navio no oceano ficando invisível para os outros jogadores. Você só estará invisível enquanto estiver parado, quando navegar voltará a ser visível.",
            FALSE,
            PRECO_GOLD_CAMUFLAGEM,
            PRECO_DOBRAO_CAMUFLAGEM,
            "Vip/ocultar.php",
            "VipDobroes/ocultar.php"
        ); */?>
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-2 col-md-2">
                    <img src="Imagens/Vip/gold_berries.png"/>
                </div>
                <div class="col-xs-7 col-md-7">
                    <h4>Trocar Moedas de Ouro por Berries</h4>
                    <p>
                        Instantâneo
                    </p>
                </div>
                <div class="col-xs-3 col-md-3">
                    <p>
                        <a href="./?ses=leiloes" class="link_content btn btn-success">
                            Centro de Comércio
                        </a>
                    </p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-2 col-md-2">
                    <img src="Imagens/Vip/renomear.png"/>
                </div>
                <div class="col-xs-7 col-md-7">
                    <h4>Renomear tripulação</h4>
                    <p>Mude o nome da sua tripulação.</p>
                    <p>
                        Instantâneo
                    </p>
                </div>
                <div class="col-xs-3 col-md-3">
                    <p>
                        <button id="renomeia_trip" class="btn btn-success"
                            <?= $userDetails->conta["gold"] < PRECO_GOLD_RENOMEAR_TRIPULACAO ? "disabled" : "" ?>>
                            <?= PRECO_GOLD_RENOMEAR_TRIPULACAO ?> <img src="Imagens/Icones/Gold.png"/> Comprar
                        </button>
                    </p>
                    <p>
                        <button id="renomeia_trip_dobrao" class="btn btn-info"
                            <?= $userDetails->conta["dobroes"] < PRECO_DOBRAO_RENOMEAR_TRIPULACAO ? "disabled" : "" ?>>
                            <?= PRECO_DOBRAO_RENOMEAR_TRIPULACAO ?> <img src="Imagens/Icones/Dobrao.png"/> Comprar
                        </button>
                    </p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-xs-2 col-md-2">
                    <img src="Imagens/Vip/faccao.png"/>
                </div>
                <div class="col-xs-7 col-md-7">
                    <h4>Mudar de facção</h4>
                    <p>Piratas se tornam marinheiros e Marinheiros se tornam piratas.</p>
                    <p>Não é possível trocar de facção se você fizer parte de uma Aliança ou Frota.</p>
                    <p>ATENÇÃO: Ao trocar de facção seus pontos de reputação serão resetados.</p>
                    <p>
                        Instantâneo
                    </p>
                </div>
                <div class="col-xs-3 col-md-3">
                    <p>
                        <button href="Vip/faccao_trocar.php" data-question="Deseja trocar de facção?"
                                class="link_confirm btn btn-success"
                            <?= $userDetails->ally
                            || $userDetails->conta["gold"] < PRECO_GOLD_TROCAR_FACCAO ? "disabled" : "" ?>>
                            <?= PRECO_GOLD_TROCAR_FACCAO ?> <img src="Imagens/Icones/Gold.png"/> Comprar
                        </button>
                    </p>
                    <p>
                        <button href="VipDobroes/faccao_trocar.php" data-question="Deseja trocar de facção?"
                                class="link_confirm btn btn-info"
                            <?= $userDetails->ally
                            || $userDetails->conta["dobroes"] < PRECO_DOBRAO_TROCAR_FACCAO ? "disabled" : "" ?>>
                            <?= PRECO_DOBRAO_TROCAR_FACCAO ?> <img src="Imagens/Icones/Dobrao.png"/> Comprar
                        </button>
                    </p>
                </div>
            </div>
        </li>
    </ul>
</div>