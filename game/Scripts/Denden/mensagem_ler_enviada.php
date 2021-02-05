<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$cod = $protector->get_number_or_exit("cod");

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
    WHERE msg.remetente = ? AND msg.cod_mensagem = ?",
    "ii", array($userDetails->tripulacao["id"], $cod));

if (!$result->count()) {
    $protector->exit_error("Mensagem não encontrada");
}
$msg = $result->fetch_array();
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        <?= $msg["assunto"] ?>
    </h4>
</div>
<div class="modal-body">
    <p>
        <?= htmlspecialchars($msg["texto"], ENT_QUOTES) ?>
    </p>
</div>
<div class="modal-footer clearfix">
    <p class="pull-left">
        Enviada para <?= $msg["remetente"] ?> - <?= $msg["tripulacao"] ?>
        <?= $msg["hora"] ?>
    </p>
    <button id="bt_msg_listar" class="btn btn-primary">Voltar</button>
</div>