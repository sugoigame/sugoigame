<div class="panel-heading">
    Batalhas Amigáveis
</div>


<div class="panel-body">
    <?= ajuda("Batalhas Amigáveis", "Desafie outros jogadores para partidas de treino e ganhe experiência") ?>

    <p>
        Batalhas Amigáveis são partidas de treino que não valem pontos nem reduzem a vida da tripulação, mas são
        bastante úteis para conseguir pontos de experiência e aprender mais sobre o jogo.
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            Desafios amigáveis recebidos
        </div>
        <div class="panel-body">
            <?php $desafios_recebidos = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ?", "i", array($userDetails->tripulacao["id"])); ?>
            <?php if (!$desafios_recebidos->count()): ?>
                <p>Você não recebeu nenhum desafio ainda.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php while ($desafio = $desafios_recebidos->fetch_array()): ?>
                        <div class="list-group-item">
                            <p>
                                <?= $desafio["desafiante_nome"] ?> desafiou você para uma batalha amigável.
                            </p>
                            <button class="btn btn-danger link_send"
                                    href="link_Batalha/recusar_desafio.php?desafiante=<?= $desafio["desafiante"] ?>">
                                Recusar
                            </button>
                            <button class="btn btn-success link_send"
                                    href="link_Batalha/aceitar_desafio.php?desafiante=<?= $desafio["desafiante"] ?>">
                                Aceitar
                            </button>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            Desafios amigáveis enviados
        </div>
        <div class="panel-body">
            <?php $desafios_enviados = $connection->run(
                "SELECT d.*, u.tripulacao FROM tb_combate_desafio d INNER JOIN tb_usuarios u ON d.desafiado = u.id WHERE d.desafiante = ?",
                "i", array($userDetails->tripulacao["id"])); ?>
            <?php if (!$desafios_enviados->count()): ?>
                <p>Você não enviou nenhum desafio ainda.</p>
            <?php else: ?>
                <div class="list-group">
                    <?php while ($desafio = $desafios_enviados->fetch_array()): ?>
                        <div class="list-group-item">
                            <p>
                                Você enviou um desafio para <?= $desafio["tripulacao"] ?>.
                            </p>
                            <button class="btn btn-danger link_send"
                                    href="link_Batalha/cancelar_desafio.php?desafiado=<?= $desafio["desafiado"] ?>">
                                Cancelar
                            </button>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            Jogadores Disponíveis
        </div>
        <div class="panel-body">
            <?php $jogadores = $connection->run(
                "SELECT 
                    u.id, 
                    u.tripulacao, 
                    (SELECT max(lvl) FROM tb_personagens p WHERE id = u.id AND p.ativo = 1) AS lvl_mais_forte,
                    (SELECT count(*) FROM tb_personagens p WHERE id = u.id AND p.ativo = 1) AS tripulantes 
                 FROM tb_usuarios u WHERE u.ultimo_logon > ? AND u.id <> ? AND adm = 0",
                "ii", array(atual_segundo() - 10 * 60, $userDetails->tripulacao["id"])); ?>
            <div class="list-group">
                <?php while ($jogador = $jogadores->fetch_array()): ?>
                    <div class="list-group-item">
                        <p>
                            <?= $jogador["tripulacao"] ?>,
                            nível <?= $jogador["lvl_mais_forte"] ?>,
                            <?= $jogador["tripulantes"] ?> tripulante(s)
                            <button class="btn btn-info link_send"
                                    href="link_Batalha/desafiar.php?alvo=<?= $jogador["id"] ?>">
                                Desafiar
                            </button>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>