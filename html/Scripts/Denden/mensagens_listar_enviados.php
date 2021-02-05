<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();


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
    INNER JOIN tb_usuarios usr ON msg.destinatario = usr.id
    INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
    WHERE msg.remetente = ?
    ORDER BY cod_mensagem DESC
    LIMIT 30",
    "i", $userDetails->tripulacao["id"]
);

?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        Mensagens Enviadas
    </h4>
</div>
<div class="modal-body">
    <ul class="list-group">
        <?php while ($msg = $result->fetch_array()): ?>
            <li class="list-group-item">
                <a href="#" class="noHref mensagem_ler" data-cod="<?= $msg["cod"] ?>">
                    <p>
                        <?= $msg["assunto"] ?> - <?= $msg["hora"] ?>
                        Enviada para <?= $msg["remetente"] ?> - <?= $msg["tripulacao"] ?>
                        <?php if (!$msg["lido"]): ?>
                            <span class="label label-primary">Não lida</span>
                        <?php endif; ?>
                    </p>
                </a>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
<div class="modal-footer">
    <button id="bt_msg_listar" class="btn btn-primary">Voltar</button>
</div>
