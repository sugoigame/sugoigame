<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$cod = $protector->get_number_or_exit("cod");

if (substr($_GET["cod"], 0, 6) == "000000") {
    $result = $connection->run(
        "SELECT
        msg.id AS cod,
        1 AS global,
        msg.assunto AS assunto,
        msg.mensagem AS texto,
        msg.data AS hora,
        lidas.data_leitura AS lido
        FROM tb_mensagens_globais msg
        LEFT JOIN tb_mensagens_globais_lidas lidas ON msg.id = lidas.mensagem_id AND lidas.tripulacao_id = ?
        WHERE msg.id = ?",
        "ii", array($userDetails->tripulacao["id"], $cod)
    );
    if (!$result->count()) {
        $protector->exit_error("Mensagem não encontrada");
    }
    $msg = $result->fetch_array();

    if (!$msg["lido"]) {
        $connection->run(
            "INSERT INTO tb_mensagens_globais_lidas (mensagem_id, tripulacao_id) VALUES (?, ?)",
            "ii", array($cod, $userDetails->tripulacao["id"]));
    }
} else {
    $result = $connection->run(
        "SELECT 
        msg.cod_mensagem AS cod,
        0 AS global,
        msg.assunto AS assunto,
        msg.texto AS texto,
        msg.hora AS hora,
        msg.lido AS lido,
        pers.nome AS remetente,
        usr.tripulacao AS tripulacao
        FROM tb_mensagens msg
        INNER JOIN tb_usuarios usr ON msg.remetente = usr.id
        INNER JOIN tb_personagens pers ON usr.cod_personagem = pers.cod
        WHERE msg.destinatario = ? AND msg.cod_mensagem = ?",
        "ii", array($userDetails->tripulacao["id"], $cod));
    if (!$result->count()) {
        $protector->exit_error("Mensagem não encontrada");
    }
    $msg = $result->fetch_array();

    $connection->run("UPDATE tb_mensagens SET lido='1' WHERE cod_mensagem = ?", "i", $cod);
}
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
    <h4 class="modal-title">
        <?= $msg["assunto"] ?>
    </h4>
</div>
<div class="modal-body">
    <p><?php if (!$msg["global"]): ?>
        <?= htmlspecialchars($msg["texto"], ENT_QUOTES) ?>
        <?php 
        else:
           echo $msg["texto"];
        endif;
        ?>
    </p>
</div>
<div class="modal-footer clearfix">
    <?php if ($msg["global"]): ?>
        <p class="pull-left">
            Recebida do Governo Mundial
            <?= $msg["hora"] ?>
        </p>
        <button id="bt_msg_listar" class="btn btn-primary">Voltar</button>
    <?php else: ?>
        <p class="pull-left">
            Recebida de <?= $msg["remetente"] ?> - <?= $msg["tripulacao"] ?>
            <?= $msg["hora"] ?>
        </p>
        <button id="bt_msg_listar" class="btn btn-primary">Voltar</button>
        <button id="bt_msg_apagar" data-cod="<?= $msg["cod"] ?>" class="btn btn-danger">Apagar</button>
        <button id="bt_msg_responder" data-remetente="<?= $msg["remetente"] ?>" data-assunto="<?= $msg["assunto"] ?>"
                class="btn btn-info">
            Responder
        </button>
    <?php endif; ?>
</div>