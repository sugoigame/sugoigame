<div class="panel-heading">
    Log de Batalhas PvP
</div>
<div class="panel-body">
    <div class="list-group">
        <?php $result = $connection->run(
            "SELECT
              log.combate AS combate_id,
              log.vencedor AS vencedor,
              log.id_1 AS id_1,
              log.id_2 AS id_2,
              log.tipo AS tipo,
              usr_1.tripulacao AS tripulacao_1,
              usr_1.faccao AS faccao_1,
              usr_1.bandeira AS bandeira_1,
              usr_2.tripulacao AS tripulacao_2,
              usr_2.faccao AS faccao_2,
              usr_2.bandeira AS bandeira_2,
              combate.combate AS andamento
             FROM tb_combate_log log
             LEFT JOIN tb_combate combate ON log.combate=combate.combate
             INNER JOIN tb_usuarios usr_1 ON log.id_1 = usr_1.id
             INNER JOIN tb_usuarios usr_2 ON log.id_2 = usr_2.id
             ORDER BY log.horario DESC"
        );
        ?>
        <?php while ($combate = $result->fetch_array()): ?>
            <div class="list-group-item col-sm-6 col-md-4">
                <p>
                    <?php if ($userDetails->tripulacao["adm"] && $combate["andamento"]): ?>
                        <a href="./?ses=combateAssistir&combate=<?= $combate["combate_id"]; ?>" class="link_content">
                            Assistir
                        </a>
                    <?php endif; ?>
                    <?php if ($userDetails->tripulacao["adm"] && !$combate["andamento"]): ?>
                        <a href="./?ses=combateAssistirAdm&combate=<?= $combate["combate_id"]; ?>" class="link_content">
                            Log da Batalha
                        </a>
                    <?php endif; ?>
                </p>
                <div class="row">
                    <div class="col-xs-5">
                        <?= $combate["tripulacao_1"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_1"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                    </div>
                    <div class="col-xs-2">
                        VS
                    </div>
                    <div class="col-xs-5">
                        <?= $combate["tripulacao_2"] ?><br/>
                        <?= $combate["vencedor"] == $combate["id_2"] ? "<span class='text-warning'>Vencedor</span>" : "--" ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_1"] ?>&f=<?= $combate["faccao_1"] ?>" style="width:100%; max-width: 95px;" />
                    </div>
                    <div class="col-xs-2">
                    </div>
                    <div class="col-xs-5">
                        <img src="Imagens/Bandeiras/img.php?cod=<?= $combate["bandeira_2"] ?>&f=<?= $combate["faccao_2"] ?>" style="width:100%; max-width: 95px;" />
                    </div>
                </div>
                <div><?= ucwords(nome_tipo_combate($combate["tipo"])) ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</div>