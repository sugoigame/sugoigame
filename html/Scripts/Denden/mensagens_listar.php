<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">Mensagens Recebidas</h4>
</div>
<div class="modal-body">
    <h5>Mensagens do Governo Mundial</h5>
    <ul class="list-group">
        <?php
        $result = $connection->run(
            "SELECT
            msg.id AS cod,
            msg.assunto AS assunto,
            msg.mensagem AS texto,
            msg.data AS hora,
            lidas.data_leitura AS lido
            FROM tb_mensagens_globais msg
            LEFT JOIN tb_mensagens_globais_lidas lidas ON msg.id = lidas.mensagem_id AND lidas.tripulacao_id = ?
            ORDER BY msg.id DESC
            LIMIT 5",
            "i", $userDetails->tripulacao["id"]
        );
        ?>

        <?php while ($msg = $result->fetch_array()): ?>
            <li class="list-group-item">
                <a href="#" class="noHref mensagem_ler" data-cod="000000<?= $msg["cod"] ?>">
                    <p>
                        <?= $msg["assunto"] ?> -
                        Recebida do Governo Mundial
                        <?= $msg["hora"] ?>
                        <?php if (!$msg["lido"]): ?>
                            <span class="label label-warning">Nova</span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>

    <h5>Mensagens de jogadores</h5>
    <ul class="list-group">
        <?php
        $result = $connection->run(
            "SELECT 
            msg.cod_mensagem AS cod,
            msg.assunto AS assunto,
            msg.texto AS texto,
            msg.hora AS hora,
            msg.lido AS lido,
            pers.nome AS remetente,
            usr.tripulacao AS tripulacao
            FROM tb_mensagens msg
            INNER JOIN tb_usuarios usr ON msg.remetente = usr.id
            INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
            WHERE msg.destinatario = ?
            ORDER BY cod_mensagem DESC
            LIMIT 30",
            "i", $userDetails->tripulacao["id"]
        );
        ?>
        <?php while ($msg = $result->fetch_array()): ?>
            <li class="list-group-item">
                <a href="#" class="noHref mensagem_ler" data-cod="<?= $msg["cod"] ?>">
                    <p>
                        <?= $msg["assunto"] ?> -
                        Recebida de <?= $msg["remetente"] ?> - <?= $msg["tripulacao"] ?>
                        <?= $msg["hora"] ?>
                        <?php if (!$msg["lido"]): ?>
                            <span class="label label-warning">Nova</span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
<div class="modal-footer">
    <button id="bt_msg_enviadas" class="btn btn-info">Mensagens enviadas</button>
    <button id="bt_apaga_msgs" class="btn btn-danger">Apagar todas</button>
    <button id="bt_nova_msg" class="btn btn-success">Nova mensagem</button>
</div>