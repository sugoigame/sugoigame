<div class="panel-heading">
    Localizador PvP
</div>

<div class="panel-body">
    <?= ajuda("Localizador PvP", "O Localizador PvP é uma forma rápida e prática de encontrar adversários para testar a sua força!") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="active">
                <a href="./?ses=localizadorCasual" class="link_content">Casual</a>
            </li>
            <li>
                <a href="./?ses=coliseu" class="link_content">Coliseu</a>
            </li>
            <li>
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

    <?php $coliseu_aberto = !is_coliseu_aberto(); ?>

    <?php if ($coliseu_aberto && $userDetails->fila_coliseu): ?>
        <script type="text/javascript">
            setTimeout(function () {
                reloadPagina();
            }, 5000);
        </script>
    <?php endif; ?>

    <?php if ($userDetails->capitao["lvl"] < 5) : ?>
        <h3>O localizador Casual estará disponível quando o seu capitão alcançar o nível 5.</h3>
    <?php else: ?>
        <p>
            Atenção! Durante as partidas Casuais, todos os tripulantes de nível baixo são evoluídos automaticamente
            para o nível 50
        </p>

        <?php if ($coliseu_aberto) : ?>
            <div class="panel panel-default">
                <div class="panel-body">
                    <?php if ($userDetails->fila_coliseu && $userDetails->fila_coliseu["busca_casual"]) : ?>
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
                                <button href="link_LocalizadorCasual/cancelar.php" class="link_send btn btn-primary">
                                    Sair da fila
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <h4>Defina sua tripulação para combates Casuais</h4>
                        <?php $personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ?",
                            "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
                        <?php $tamanho_time = 0; ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tripulantes que irão participar:
                            </div>
                            <div class="panel-body">
                                <?php foreach ($personagens as $pers): ?>
                                    <?php if ($pers["time_casual"]): ?>
                                        <?php $tamanho_time++; ?>
                                        <a class="link_send"
                                           href="link_LocalizadorCasual/remover_time.php?cod=<?= $pers["cod"] ?>">
                                            <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                                 width="60px"/>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if ($tamanho_time == TAMANHO_TIME_COLISEU): ?>
                            <button href="link_LocalizadorCasual/atacar.php" class="link_send btn btn-info">
                                Procurar Oponente
                            </button>
                        <?php else: ?>
                            <div class="panel panael-default">
                                <div class="panel-heading">
                                    Tripulantes de fora:
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($personagens as $pers): ?>
                                        <?php if (!$pers["time_casual"] && !$pers["preso"]): ?>
                                            <a class="link_send"
                                               href="link_LocalizadorCasual/ativar_time.php?cod=<?= $pers["cod"] ?>">
                                                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                                     width="60px"/>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <p>
                                Você precisa escolher <?= TAMANHO_TIME_COLISEU ?> tripulantes para participar.
                            </p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <h4>
                Enquanto o Coliseu estiver aberto, não é possível utilizar o Localizador Casual. Entre na fila por
                batalhas no Coliseu em vez disso.
            </h4>
        <?php endif; ?>

        <h3>Recompensa por vitória:</h3>
        <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
        <?php $reagents = get_reagents_for_recompensa(); ?>
        <?php render_recompensa(array(
            "tipo" => "reagent",
            "cod_item" => 193,
            "quant" => 1
        ), $reagents, $equipamentos); ?>

        <h3>Recompensa por participação:</h3>
        <?php $equipamentos = get_equipamentos_for_recompensa(); ?>
        <?php $reagents = get_reagents_for_recompensa(); ?>
        <?php render_recompensa(array(
            "tipo" => "reagent",
            "cod_item" => 194,
            "quant" => 1
        ), $reagents, $equipamentos); ?>

        <p>
            Obs: Você só irá receber a recompensa por participação se não desistir da luta antes de cumprir pelo menos
            um dos seguintes requisitos:
        </p>
        <ul class="text-left">
            <li>Derrotar metade dos tripulantes adversários</li>
            <li>Perder metade dos seus tripulantes antes de desistir</li>
            <li>Permanecer na partida por pelo menos 10 minutos</li>
        </ul>
    <?php endif; ?>
</div>