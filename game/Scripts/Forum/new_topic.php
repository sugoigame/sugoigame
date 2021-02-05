<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$categoria_id = $protector->post_number_or_exit("categoria");
$nome = $protector->post_value_or_exit(htmlspecialchars("nome"));
$response = bbcode_to_html(htmlspecialchars($protector->post_value_or_exit("response")));

$categoria = $connection->run("SELECT * FROM tb_forum_categoria WHERE id = ?", "i", array($categoria_id));

if (!$categoria->count()) {
    $protector->exit_error("Fórum inválido");
}

$categoria = $categoria->fetch_array();

if (!$categoria["permite_topico_jogador"]) {
    $protector->exit_error("Fórum bloqueado");
}

$topico = $connection->run("INSERT INTO tb_forum_topico (categoria_id, nome, criador_id) VALUE (?, ?, ?)",
    "isi", array($categoria_id, $nome, $userDetails->tripulacao["id"]));

$topico_id = $topico->last_id();

$connection->run("INSERT INTO tb_forum_post (conteudo, tripulacao_id, topico_id) VALUE (?, ?, ?)",
    "sii", array($response, $userDetails->tripulacao["id"], $topico_id));

echo "%forumPosts&topico=$topico_id";