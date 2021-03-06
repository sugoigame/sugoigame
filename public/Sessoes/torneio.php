<div class="panel-heading">
    <h3>Torneio dos Melhores</h3>
</div>

<div class="panel-body text-left">
    <h3>O Torneio começará no dia 08/01/2018, segunda-feira às 19 horas (horário de Brasília)</h3>
    <p>
        Esse é o torneio PvP entre os melhores jogadores da Primeira Grande Era dos Piratas!
    </p>
    <p>
        Todos os Yonkous e Almirantes da primeira Grande Era foram convocados a participar, mas se você não conseguiu um
        cargo desses a acha que consegue supera-los, é possível participar contribuindo com o jogo através da
        <a class="link_content" href="./?ses=vipComprar">aquisição de Moedas de Ouro</a>.
    </p>
    <p>
        Jogadores que adquirirem pelo menos R$ 10,00 em Moedas de Ouro até as vésperas do torneio também terão o direito
        de participar.
    </p>

    <?php
    $now = time();
    $start = strtotime("2018-01-08 21:00:00");
    ?>

    <?php if ($now < $start): ?>
        <h3>Jogadores convocados</h3>

        <?php $players = $connection->run(
            "SELECT t.*, u.faccao, u.tripulacao,u.bandeira, p.nome, p.img, p.skin_r FROM tb_torneio_inscricao t 
          INNER JOIN tb_usuarios u ON t.tripulacao_id=u.id
          INNER JOIN tb_personagens p ON u.cod_personagem = p.cod"
        ); ?>
        <div class="row list-group">
            <?php while ($player = $players->fetch_array()): ?>
                <div class="list-group-item col-xs-6 col-sm-4 col-md-3 text-center">
                    <p>
                        <?= icon_pers_skin($player["img"], $player["skin_r"]); ?>
                        <img src="Imagens/Bandeiras/img.php?f=<?= $player["faccao"] ?>&cod=<?= $player["bandeira"] ?>"/>
                    </p>
                    <p>
                        <?= $player["nome"] ?> - <?= $player["tripulacao"] ?>
                        <span class="text-<?= $player["confirmacao"] ? "success" : "warning" ?>">
                        <i class="fa fa-<?= $player["confirmacao"] ? "check" : "times" ?>"></i>
                            <?= $player["confirmacao"] ? "Confirmado" : "Ainda não confirmado" ?>
                        </span>
                    </p>
                    <?php if ($userDetails->tripulacao["id"] == $player["tripulacao_id"]): ?>
                        <button class="btn btn-<?= $player["confirmacao"] ? "danger" : "success" ?> link_send"
                                href="link_Eventos/torneio_confirmar.php">
                            <?= $player["confirmacao"] ? "Recusar participação" : "Confirmar participação" ?>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <h3>O torneio começou!</h3>
        <?php $participante = $connection->run(
            "SELECT * FROM tb_torneio_inscricao WHERE tripulacao_id = ? AND confirmacao = 1",
            "i", array($userDetails->tripulacao["id"])
        ); ?>

        <?php if ($participante->count()): ?>
            <?php $participante = $participante->fetch_array(); ?>
            <?php if ($participante["rodada"] < 5): ?>
                <h4>Você ainda precisa disputar <?= 5 - $participante["rodada"]; ?> partidas nessa fase</h4>
                <p class="text-center">
                    <?php if ($participante["na_fila"]): ?>
                        <button class="btn btn-danger link_send" href="link_Torneio/fila.php">
                            Sair da fila
                        </button>
                    <?php else: ?>
                        <button class="btn btn-success link_send" href="link_Torneio/fila.php">
                            Entrar na fila
                        </button>
                    <?php endif; ?>
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <h4>Ranking</h4>
        <?php $players = $connection->run(
            "SELECT t.*, u.faccao, u.tripulacao,u.bandeira, p.nome, p.img, p.skin_r FROM tb_torneio_inscricao t 
          INNER JOIN tb_usuarios u ON t.tripulacao_id=u.id
          INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
          WHERE t.confirmacao = 1 ORDER BY  t.pontos DESC"
        ); ?>
        <div class="row list-group">
            <?php for ($position = 1; $player = $players->fetch_array(); $position++): ?>
                <div class="list-group-item col-xs-6 col-sm-4 col-md-3 text-center">
                    <h4><?= $position ?>º Lugar</h4>
                    <p>
                        <?= icon_pers_skin($player["img"], $player["skin_r"]); ?>
                        <img src="Imagens/Bandeiras/img.php?f=<?= $player["faccao"] ?>&cod=<?= $player["bandeira"] ?>"/>
                    </p>
                    <p>
                        <?= $player["nome"] ?> - <?= $player["tripulacao"] ?>
                    </p>
                    <?php if ($player["na_fila"]): ?>
                        <span class="text-success">
                            Este jogador está na fila aguardando por um adversário com <?= $player["pontos"] ?> pontos
                            na <?= $player["rodada"] + 1 ?>º Rodada
                        </span>
                    <?php endif; ?>
                    <p>
                        <?= $player["pontos"] ?> pontos na <?= $player["rodada"] + 1 ?>º rodada
                    </p>
                </div>
            <?php endfor; ?>
        </div>

        <h4>Rodadas</h4>
        <?php $players = $connection->run(
            "SELECT r.*, t.*, u.faccao, u.tripulacao,u.bandeira,a.tripulacao AS adversario, p.nome, p.img, p.skin_r FROM tb_torneio_rodadas r 
          INNER JOIN tb_torneio_inscricao t ON r.tripulacao_id=t.tripulacao_id
          INNER JOIN tb_usuarios u ON t.tripulacao_id=u.id
          INNER JOIN tb_usuarios a ON r.adversario_id=a.id
          INNER JOIN tb_personagens p ON u.cod_personagem = p.cod
          WHERE t.confirmacao = 1 ORDER BY t.pontos DESC, r.rodada"
        ); ?>

        <?php $rodadas = []; ?>
        <?php while ($player = $players->fetch_array()) {
            $rodadas[$player["tripulacao_id"]]["player_data"] = $player;
            $rodadas[$player["tripulacao_id"]][$player["rodada"]] = $player;
        } ?>

        <table class="table table-striped">
            <thead>
            <tr>
                <th>Participante</th>
                <?php for ($x = 1; $x <= 5; $x++): ?>
                    <th><?= $x ?>º rodada</th>
                <?php endfor; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rodadas

                           as $player_row): ?>
                <tr>
                    <td>
                        <p>
                            <?= icon_pers_skin($player_row["player_data"]["img"], $player_row["player_data"]["skin_r"]); ?>
                            <img src="Imagens/Bandeiras/img.php?f=<?= $player_row["player_data"]["faccao"] ?>&cod=<?= $player_row["player_data"]["bandeira"] ?>"/>
                        </p>
                        <p>
                            <?= $player_row["player_data"]["nome"] ?> - <?= $player_row["player_data"]["tripulacao"] ?>
                        </p>
                    </td>
                    <?php foreach ($player_row as $id => $player): ?>
                        <?php if ($id == "player_data") {
                            continue;
                        } ?>
                        <td>
                            <div class="text-<?= $player["status"] ? "success" : "danger" ?>">
                                <p>
                                    <i class="fa fa-<?= $player["status"] ? "check" : "times" ?>"></i>
                                    <?= $player["status"] ? "Venceu de " : "Perdeu para " ?>
                                </p>
                                <p><?= $player["adversario"] ?></p>
                            </div>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

    <h3>Regras</h3>
    <p>
        O torneio contará com 2 fases, a primeira fase será em um formato inspirado em um torneio Suíço assim como
        Inazuma sugeriu em outro no fórum do jogo e a segunda fase será uma semi-final entre os 4 primeiros colocados da
        primeira fase seguida de uma grande final entre os vencedores.
    </p>
    <h4>Primeira Fase</h4>
    <ul class="text-left">
        <li>A primeira fase será dividida em 5 rodadas
        </li>
        <li>A cada rodada, cada participante irá enfrentar um adversário diferente de acordo com seu desempenho no
            torneio
        </li>
        <li>As batalhas serão no formato 15 contra 15 e não haverá nivelamento de tripulantes</li>
        <li>Todas as lutas serão televisionadas e outros jogadores poderão apostar nos resultados</li>
        <li>Na primeira rodada os jogadores poderão se enfrentar aleatóriamente graças a um sistema de fila de busca por
            adversário assim como ocorre no coliseu ou nas batalhas casuais e competitivas. Quando dois jogadores
            estiverem na fila ao mesmo tempo o jogo os colocará em combate.
        </li>
        <li>Quando todos os participantes terminarem suas lutas, a primeira rodada se encerrará, cada vitória concederá
            1 ponto para o vencedor e zero pontos para o perdedor.
        </li>
        <li>Na segunda rodada, os participantes poderão novamente entrar na fila em busca de um adversário, porém o jogo
            só colocará jogadores com a mesma pontuação para se enfrentar, ou seja, jogadores que venceram a partida da
            primeira rodada enfrentarão apenas um adversário que também venceu a partida da primeira rodada, e os
            jogadores que perderam a partida da primeira rodada só enfrentarão jogadores que também a perderam.
        </li>
        <li>Uma vez que todos os jogadores enfrentarem seus adversários da segunda rodada, terá início a terceira rodada
            e mais uma vez o sistema de filas colocará jogadores com a mesma pontuação para se enfrentar.
        </li>
        <li>Caso não haja adversários suficientes com a mesma pontuação para um combate, o jogo colocará participantes
            com a pontuação mais parecida possível, para garantir que a medida que o torneio avança, as lutas ocorram
            entre jogadores de habilidades parecidas, dando chances para que qualquer um dos competidores possa vencer o
            torneio.
        </li>
        <li>Cada rodada terá no máximo 3 dias para ser finalizada, ou seja, depois de três dias após o início da rodada
            pode ocorrer de dois ou mais jogadores não conseguirem se enfrentar, nesse caso, o jogador que tiver passado
            o maior tempo na fila buscando um adversário será considerado o vencedor. Por exemplo, suponha que temos 10
            participantes no torneio e na primeira rodada apenas 8 conseguem lutar, isso significa que 2 jogadores
            sobraram e não conseguiram um horário para lutar entre si nesses três dias, então quandos nós olhamos o
            histórico desses jogadores, vimos que um passou os três dias procurando um adversário apenas na parte da
            manhã, ou seja, 4 horas por dia, já o outro jogador passou os 3 dias procurando um adversário na parte da
            tarde e também a noite, ou seja, 8 horas por dia. Então o jogador que ficou procurando uma dversário na
            parte da tarde e também a noite será considerado o vencedor dessa luta que não ocorreu e o torneio poderá
            seguir sem atrasos e sem prejucar outros jogadores. Lembrando que isso não impede que os jogadores entrem em
            contato entre si para combinarem um horário e resolver este problema, só adicionamos essa condição para que
            não hajam atrasos no torneio.
        </li>
        <li>Diferente da fila do Coliseu, não será necessário aceitar o desafio para começar a luta, se você entrar na
            fila isso significa que você está apto a enfrentar um adversário a qualquer momento, e a luta iniciará assim
            que eler for encontrado, por isso se você fechar o jogo e deixar a conta na fila esperando um oponente você
            pode ser atacado enquanto estiver offline.
        </li>
        <li>No caso de haver uma quantidade ímpar de participantes no torneio, a cada rodada um dos jogadores que teve a
            melhor colocação no ranking da PRIMEIRA Grande Era dos Piratas receberá um vitória automática. Por exemplo,
            se tivermos 5 rodadas, os 5 primeiros colocados do ranking da PRIMEIRA era que participarem do torneio
            reverzarão uma vitória automática a cada rodada.
        </li>
        <li>No fim da primeira fase, os 4 primeiros colocados no ranking de pontuação por vitórias se classificará para
            a segunda fase.
        </li>
        <li>A quantidade de rodadas será ajustada para que não hajam empates entre os 4 primeiro colocados.</li>
    </ul>


    <h4>Segunda Fase</h4>

    <ul>
        <li>A segunda fase será um mata mata chaveado entre os 4 primeiros colocados da primeira fase</li>
        <li>Haverá uma semi-final entre o primeiro vs o segundo colocado e o terceiro vs o quarto colocado, para
            aumentar as chances do terceiro e quarto lugar disputarem a final
        </li>
        <li>Depois haverá uma grande final entre os vencedores da semi-final</li>
    </ul>

    <h3>Premiação</h3>

    <ul>
        <li>Todos os paricipantes do torneio receberão uma Akuma no Mi aleatória, uma borda exclusiva de personagem, um
            equipamento branco aleatório, alcunhas exclusivas e 20 milhões de Berries
        </li>
        <li>Os participantes que ficarem entre os 50% mais bem pontuados da primeira fase receberão um equipamento verde
            aleatório e 50 milhões de Berries
        </li>
        <li>Os 4 participantes que se classificarem para a segunda fase receberão um equipamento azul aleatório em vez
            do equipamento verde e 100 milhões de Berries
        </li>
        <li>O Vencedor do torneio receberá um equipamento preto aleatório.</li>
    </ul>

</div>