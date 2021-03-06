<div class="panel-heading">
    Localizador PvP
</div>

<div class="panel-body">
    <?= ajuda("Localizador PvP", "O Localizador PvP é uma forma rápida e prática de encontrar adversários para testar a sua força!") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li>
                <a href="./?ses=coliseu" class="link_content">Coliseu</a>
            </li>
            <li class="active">
                <a href="./?ses=localizadorCompetitivo" class="link_content">Competitivo</a>
            </li>
        </ul>
    </div>
    <br/>
    <?php if ($userDetails->fila_coliseu) : ?>
        <?php if ($userDetails->fila_coliseu["pausado"]) : ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <h3>A sua busca por adversário foi pausada enquanto você fazia algo importante</h3>
                    <button href="link_Coliseu/despausar.php" class="link_send btn btn-info">
                        Continuar procurando um adversário
                    </button>
                </div>
            </div>
        <?php else : ?>
            <?php if ($userDetails->fila_coliseu["desafio"] || attack_coliseu()) : ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h3 class="text-info">Um adversário foi encontrado
                            para <?= nome_tipo_combate($userDetails->fila_coliseu["desafio_tipo"]) ?></h3>
                        <?php if ($userDetails->fila_coliseu["desafio_aceito"]) : ?>
                            <?php check_timeout_desafio(); ?>
                            <p>Aguardando seu adversário ficar pronto...</p>
                        <?php else : ?>
                            <button href="link_Coliseu/recusar.php" class="link_send btn btn-danger">
                                Recusar
                            </button>
                            <button href="link_Coliseu/aceitar.php" class="link_send btn btn-success">
                                Aceitar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endif; ?>

    <?php $coliseu_aberto = true; ?>

    <?php if ($coliseu_aberto && $userDetails->fila_coliseu): ?>
        <script type="text/javascript">
            setTimeout(function () {
                reloadPagina();
            }, 5000);
        </script>
    <?php endif; ?>

    <?php if ($userDetails->capitao["lvl"] < 50) : ?>
        <h3>O localizador Competitivo estará disponível quando o seu capitão alcançar o nível 50.</h3>
    <?php else: ?>

        <div class="panel panel-default">
            <div class="panel-body">
                <?php if ($userDetails->fila_coliseu && $userDetails->fila_coliseu["busca_competitivo"]) : ?>
                    <?php if (!$userDetails->fila_coliseu["pausado"]) : ?>
                        <?php if (!$userDetails->fila_coliseu["desafio"]) : ?>
                            <p>Você está na fila aguardando um oponente.</p>
                            <p>
                                A busca por oponentes pode demorar vários minutos, fique a vontade para continuar
                                jogando enquanto espera, você será avisado assim que um adversário digno for
                                encontrado.
                            </p>
                            <p>
                                Se você iniciar uma batalha enquanto aguarda um oponente, o localizador será pausado
                                e você poderá continuar a busca assim que terminar.
                            </p>
                            <p>
                                Sempre que você entra em uma batalha pelo Localizador, os pontos de vida e energia
                                da sua tripulação serão restaurados automaticamente para a batalha.
                            </p>
                            <p>
                                Tempo decorrido:
                                <?= transforma_tempo_min(time() - strtotime($userDetails->fila_coliseu["momento"])); ?>
                            </p>
                            <button href="link_LocalizadorCompetitivo/cancelar.php" class="link_send btn btn-primary">
                                Sair da fila
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else : ?>
                    <h4>Defina sua tripulação para combates Competitivos</h4>
                    <?php $personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ?",
                        "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
                    <?php $tamanho_time = 0; ?>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Tripulantes que irão participar:
                        </div>
                        <div class="panel-body">
                            <?php foreach ($personagens as $pers): ?>
                                <?php if ($pers["time_competitivo"]): ?>
                                    <?php $tamanho_time++; ?>
                                    <a class="link_send"
                                       href="link_LocalizadorCompetitivo/remover_time.php?cod=<?= $pers["cod"] ?>">
                                        <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                             width="60px"/>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($tamanho_time == TAMANHO_TIME_COMPETITIVO): ?>
                        <button href="link_LocalizadorCompetitivo/atacar.php" class="link_send btn btn-info">
                            Procurar Oponente
                        </button>
                    <?php else: ?>
                        <div class="panel panael-default">
                            <div class="panel-heading">
                                Tripulantes de fora:
                            </div>
                            <div class="panel-body">
                                <?php foreach ($personagens as $pers): ?>
                                    <?php if (!$pers["time_competitivo"] && !$pers["preso"]): ?>
                                        <a class="link_send"
                                           href="link_LocalizadorCompetitivo/ativar_time.php?cod=<?= $pers["cod"] ?>">
                                            <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                                 width="60px"/>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <p>
                            Você precisa escolher <?= TAMANHO_TIME_COMPETITIVO ?> tripulantes para participar.
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <p>As batalhas Competitivas valem 1 terço da reputação que valeria um Ataque em alto mar</p>

    <?php endif; ?>

    <h3>Grande Era dos Piratas</h3>
    <div class="row">
        <div class="col-sm-6">
            <h4>Candidatos a Rei dos Piratas</h4>
            <?php render_top_ranking_reputacao("reputacao", array(FACCAO_PIRATA), 4); ?>
            <a class="link_content" href="./?ses=ranking&faccao=1">Ver o ranking completo</a>
        </div>
        <div class="col-sm-6">
            <h4>Candidatos a Almirante de Frota</h4>
            <?php render_top_ranking_reputacao("reputacao", array(FACCAO_MARINHA), 4); ?>
            <a class="link_content" href="./?ses=ranking&faccao=0">Ver o ranking completo</a>
        </div>
    </div>

    <h3>Batalha pelos Grandes Poderes</h3>
    <div class="row">
        <div class="col-sm-6">
            <h4>Candidatos a Yonkou</h4>
            <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_PIRATA)); ?>
            <a class="link_content" href="./?ses=ranking&rank=reputacao_mensal&faccao=1">Ver o ranking completo</a>
        </div>
        <div class="col-sm-6">
            <h4>Candidatos a Almirante</h4>
            <?php render_top_ranking_reputacao("reputacao_mensal", array(FACCAO_MARINHA)); ?>
            <a class="link_content" href="./?ses=ranking&rank=reputacao_mensal&faccao=0">Ver o ranking completo</a>
        </div>
    </div>
</div>