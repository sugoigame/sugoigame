<div class="panel-heading">
    Localizador PvP
</div>

<style type="text/css">
    .champion {
        background: center no-repeat url(Imagens/Backgrounds/cinturao.png);
        height: 130px;
        padding-top: 35px;
    }

    .champion img {
        border: 1px solid #000;
    }
</style>

<?php $equipamentos = get_equipamentos_for_recompensa(); ?>
<?php $reagents = get_reagents_for_recompensa(); ?>

<div class="panel-body">
    <?= ajuda("Coliseu", "O Coliseu é um lugar onde as mais inusitadas batalhas acontecem. A cada semana você tem a chance de conseguir novas recompensas.") ?>

    <div>
        <ul class="nav nav-pills nav-justified">
            <li class="active">
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

    <?php $CP = $userDetails->tripulacao["coliseu_points"]; ?>

    <?php $coliseu_aberto = is_coliseu_aberto(); ?>

    <?php if ($coliseu_aberto && $userDetails->fila_coliseu): ?>
        <script type="text/javascript">
            setTimeout(function () {
                reloadPagina();
            }, 5000);
        </script>
    <?php endif; ?>

    <?php if ($userDetails->capitao["lvl"] < 5) : ?>
        <h3>O coliseu estará disponível quando o seu capitão alcançar o nível 5.</h3>
    <?php else: ?>
        <h3>
            O campeão do Coliseu rececebe o Cinturão do Campeão do Coliseu <img src="Imagens/Itens/348.jpg"/>
            (Acessório de Ataque +12)
        </h3>
        <h4>
            A Alcunha de Campeão e o Cinturão são sempre repassados ao novo campeão.
        </h4>
        <br/>
        <p>
            Atenção! Durante as partidas do Coliseu, todos os tripulantes de nível baixo são evoluídos automaticamente
            para o nível 50.
        </p>

        <?php $ranking = $connection->run(
        "SELECT c.*, u.*, p.nome, p.img, p.skin_r
            FROM tb_coliseu_ranking c 
            INNER JOIN tb_usuarios u ON c.id = u.id 
            INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
            WHERE c.cp = (SELECT MAX(c2.cp) FROM tb_coliseu_ranking c2)"
    )->fetch_all_array() ?>
        <h3>Atual Campeão do Coliseu:</h3>
        <ul class="list-group">
            <?php foreach ($ranking as $campeao): ?>
                <li class="list-group-item">
                    <div class="champion">
                        <p>
                            <?= icon_pers_skin($campeao["img"], $campeao["skin_r"]) ?>
                            <img src="Imagens/Bandeiras/img.php?f=<?= $campeao["faccao"] ?>&cod=<?= $campeao["bandeira"] ?>">
                        </p>
                    </div>
                    <h4><?= $campeao["nome"] ?> - <?= $campeao["tripulacao"] ?></h4>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($coliseu_aberto) : ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4>O Coliseu está aberto!</h4>
                    <p>Essa edição do Coliseu será encerrada as 22:00 do Domingo.</p>
                </div>
                <div class="panel-body">
                    <?php if ($userDetails->fila_coliseu && $userDetails->fila_coliseu["busca_coliseu"]) : ?>
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
                                    Sempre que você entra em uma batalha no Coliseu, os pontos de vida e energia da sua
                                    tripulação serão restaurados automaticamente para a batalha.
                                </p>
                                <p>
                                    Tempo decorrido:
                                    <?= transforma_tempo_min(time() - strtotime($userDetails->fila_coliseu["momento"])); ?>
                                </p>
                                <button href="link_Coliseu/cancelar.php" class="link_send btn btn-primary">
                                    Sair da fila
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else : ?>
                        <h4>Defina sua tripulação para combates no Coliseu</h4>
                        <?php $personagens = $connection->run("SELECT * FROM tb_personagens WHERE id = ?",
                            "i", array($userDetails->tripulacao["id"]))->fetch_all_array(); ?>
                        <?php $tamanho_time = 0; ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Tripulantes que irão participar:
                            </div>
                            <div class="panel-body">
                                <?php foreach ($personagens as $pers): ?>
                                    <?php if ($pers["time_coliseu"]): ?>
                                        <?php $tamanho_time++; ?>
                                        <a class="link_send"
                                           href="link_Coliseu/remover_time.php?cod=<?= $pers["cod"] ?>">
                                            <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                                 width="60px"/>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if ($tamanho_time == TAMANHO_TIME_COLISEU): ?>
                            <button href="link_Coliseu/atacar.php" class="link_send btn btn-info">
                                Procurar Oponente
                            </button>
                        <?php else: ?>
                            <div class="panel panael-default">
                                <div class="panel-heading">
                                    Tripulantes de fora:
                                </div>
                                <div class="panel-body">
                                    <?php foreach ($personagens as $pers): ?>
                                        <?php if (!$pers["time_coliseu"] && !$pers["preso"]): ?>
                                            <a class="link_send"
                                               href="link_Coliseu/ativar_time.php?cod=<?= $pers["cod"] ?>">
                                                <img src="Imagens/Personagens/Icons/<?= get_img($pers, "r") ?>.jpg"
                                                     width="60px"/>
                                            </a>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <p>Você precisa escolher <?= TAMANHO_TIME_COLISEU ?> tripulantes para participar do
                                Coliseu.</p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php else : ?>
            <h4>
                O Coliseu acontece toda sexta-feira às 22:00 até domingo às 22:00.
            </h4>
        <?php endif; ?>
        <h3>
            Na última edição você
            conseguiu <?= mascara_numeros_grandes($userDetails->tripulacao["coliseu_points_edicao"]) ?>
            <img src="Imagens/Icones/CP.png"/>
        </h3>
        <p>
            Se conseguir 600 <img src="Imagens/Icones/CP.png"/> em uma única edição do Coliseu, você receberá os
            seguintes prêmios assim que a edição for encerrada:
        </p>
        <?php render_recompensa(array(
            "tipo" => "reagent",
            "cod_item" => 155,
            "quant" => 1
        ), $reagents, $equipamentos); ?>
        <?php render_recompensa(array(
            "tipo" => "reagent",
            "cod_item" => 121,
            "quant" => 1
        ), $reagents, $equipamentos); ?>
        <?php render_recompensa(array(
            "tipo" => "reagent",
            "cod_item" => 200,
            "quant" => 5
        ), $reagents, $equipamentos); ?>

        <?php if ($userDetails->tripulacao["coliseu_points_edicao_passada"] >= 600) : ?>
            <button class="btn btn-success link_send" href="link_Coliseu/premio_especial.php">
                Receber prêmio especial
            </button>
        <?php endif; ?>

        <h3>No total, você já conseguiu <?= mascara_numeros_grandes($CP) ?> <img src="Imagens/Icones/CP.png"/></h3>
        <?php
        $recompensas = DataLoader::load("recompensas_coliseu");

        $reagents = get_reagents_for_recompensa();
        $equipamentos = get_equipamentos_for_recompensa();
        ?>
        <div class="list-group">
            <?php foreach ($recompensas as $premio_index => $recompensa): ?>
                <?php if ($premio_index == $userDetails->tripulacao["coliseu_premio"]): ?>
                    <?php $recompensa_anterior = isset($recompensas[$premio_index - 1]) ? $recompensas[$premio_index - 1] : array("minimo" => 0); ?>
                    <div class="list-group-item">
                        <h4>
                            <?= $premio_index + 1 ?>º Objetivo:
                            conseguir <?= mascara_numeros_grandes($recompensa["minimo"]) ?>
                            <img src="Imagens/Icones/CP.png"/>
                        </h4>
                        <div class="progress">
                            <div class="progress-bar progress-bar-success"
                                 style="width: <?= ($CP - $recompensa_anterior["minimo"]) / ($recompensa["minimo"] - $recompensa_anterior["minimo"]) * 100 ?>%;">
                                <?= mascara_numeros_grandes($CP) . "/" . mascara_numeros_grandes($recompensa["minimo"]) ?>
                            </div>
                        </div>
                        <p>Você receberá uma recompensa surpresa por atingir esse objetivo.</p>
                        <?php if ($CP >= $recompensa["minimo"]): ?>
                            <p>
                                <button class="btn btn-success link_send"
                                        href="link_Coliseu/premio.php">
                                    Receber a recompensa
                                </button>
                            </p>
                        <?php endif; ?>
                    </div>
                    <?php break ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($coliseu_aberto) : ?>
        <h3>Ranking da edição</h3>
        <?php $rep_mais_forte = $connection->run(
            "SELECT max(coliseu_points_edicao) AS total FROM tb_usuarios"
        )->fetch_array()["total"]; ?>
        <?php if (!$rep_mais_forte) {
            $rep_mais_forte = 1;
        } ?>
        <?php $result = $connection->run(
            "SELECT usr.id, usr.coliseu_points_edicao AS reputacao, usr.faccao, usr.bandeira, usr.tripulacao, pers.img, pers.nome, pers.skin_r 
                FROM tb_usuarios usr
                INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
                WHERE usr.adm='0' ORDER BY usr.coliseu_points_edicao DESC LIMIT 6"
        ); ?>

        <div class="list-group">
            <?php render_top_ranking($result, $rep_mais_forte, "reputacao", "coliseu points"); ?>
        </div>
    <?php endif; ?>
</div>