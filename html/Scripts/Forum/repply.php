<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$topico_id = $protector->post_number_or_exit("topico");
$response = bbcode_to_html(htmlspecialchars($protector->post_value_or_exit("response")));

$topico = $connection->run("SELECT * FROM tb_forum_topico WHERE id = ?", "i", array($topico_id));

if (!$topico->count()) {
    $protector->exit_error("Tópico inválido");
}

$topico = $topico->fetch_array();

if ($topico["bloqueado"]) {
    $protector->exit_error("Tópico bloqueado");
}

$connection->run("INSERT INTO tb_forum_post (conteudo, tripulacao_id, topico_id) VALUE (?, ?, ?)",
    "sii", array($response, $userDetails->tripulacao["id"], $topico_id));

$connection->run("DELETE FROM tb_forum_topico_lido WHERE topico_id = ?", "i", array($topico_id));

$acompanhantes = $connection->run("SELECT tripulacao_id FROM tb_forum_post WHERE topico_id = ? AND tripulacao_id <> ? GROUP BY tripulacao_id",
    "ii", array($topico_id, $userDetails->tripulacao["id"]))->fetch_all_array();
foreach ($acompanhantes as $acompanhante) {
    $hora = "às ";
    $hora .= date("H:i", time());
    $hora .= " do dia ";
    $hora .= date("d/m/Y", time());

    $connection->run("INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora) VALUE (?,?,?,?,?)",
        "iisss", array($userDetails->tripulacao["id"], $acompanhante["tripulacao_id"], "Resposta a um tópico no fórum",
            $userDetails->tripulacao["tripulacao"] . " respondeu o tópico \"" . $topico["nome"] . "\" no fórum do jogo.", $hora));

}

echo "-Resposta enviada";