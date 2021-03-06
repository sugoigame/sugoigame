<?php function render_ganhos($combate) { ?>
    <ul>
        <li>Reputação ganha na era: <?= $combate["reputacao_ganha"]; ?></li>
        <li>Reputação ganha no mês: <?= $combate["reputacao_mensal_ganha"]; ?></li>
        <li>Berries ganhos: <?= $combate["berries_ganhos"]; ?></li>
    </ul>
<?php } ?>
<?php function render_perdas($combate) { ?>
    <ul>
        <li>Reputação perdida na era: <?= $combate["reputacao_perdida"]; ?></li>
        <li>Reputação perdida no mês: <?= $combate["reputacao_mensal_perdida"]; ?></li>
        <li>Berries perdidos: <?= $combate["berries_perdidos"]; ?></li>
    </ul>
<?php } ?>

<div class="panel-heading">
    Histórico de combates
</div>

<div class="panel-body">
    <?php $result = $connection->run(
        "SELECT 
          log.horario AS horario,
          log.tipo AS tipo,
          log.id_1 AS id_1,
          log.id_2 AS id_2,
          log.vencedor AS vencedor,
          log.reputacao_ganha AS reputacao_ganha,
          log.reputacao_perdida AS reputacao_perdida,
          log.reputacao_mensal_ganha AS reputacao_mensal_ganha,
          log.reputacao_mensal_perdida AS reputacao_mensal_perdida,
          log.berries_ganhos AS berries_ganhos,
          log.berries_perdidos AS berries_perdidos,
          usr1.tripulacao AS tripulacao_1,
          usr1.faccao AS faccao_1,
          usr1.bandeira AS bandeira_1,
          usr2.tripulacao AS tripulacao_2,
          usr2.faccao AS faccao_2,
          usr2.bandeira AS bandeira_2
        FROM tb_combate_log log
        INNER JOIN tb_usuarios usr1 ON log.id_1 = usr1.id
        INNER JOIN tb_usuarios usr2 ON log.id_2 = usr2.id
        WHERE log.id_1 = ? OR log.id_2 = ?
        ORDER BY log.horario DESC",
        "ii", array($userDetails->tripulacao["id"], $userDetails->tripulacao["id"])
    ); ?>
    <ul class="list-group">
        <?php if (!$result->count()): ?>
            <p>Você ainda não participou de nenhum combate</p>
        <?php endif; ?>
        <?php while ($combate = $result->fetch_array()) : ?>
            <li class="list-group-item">
                <p><?= date("d/m/Y - H:i", strtotime($combate["horario"])) ?></p>
                <div class="row">
                    <div class="col-md-5 text-right">
                        <?= $combate["tripulacao_1"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-warning'>Vencedor</span>" : "" ?>
                    </div>
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-5 text-left">
                        <?= $combate["tripulacao_2"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-warning'>Vencedor</span>" : "" ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5 text-right">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>">
                    </div>
                    <div class="col-md-2">
                        <img src="Imagens/Batalha/vs.png">
                        <?= nome_tipo_combate($combate["tipo"]) ?>
                    </div>
                    <div class="col-md-5 text-left">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>">
                    </div>
                </div>
                <?php if ($combate["vencedor"]): ?>
                    <div class="row text-left">
                        <div class="col-md-5">
                            <?php if ($combate["vencedor"] == $combate["id_1"]): ?>
                                <?php render_ganhos($combate); ?>
                            <?php else: ?>
                                <?php render_perdas($combate); ?>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-2">
                        </div>
                        <div class="col-md-5">
                            <?php if ($combate["vencedor"] == $combate["id_2"]): ?>
                                <?php render_ganhos($combate); ?>
                            <?php else: ?>
                                <?php render_perdas($combate); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </li>
        <?php endwhile; ?>
    </ul>
</div>