<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$post_id = $protector->get_number_or_exit("post");

$post = $connection->run("SELECT * FROM tb_forum_post WHERE id = ?", "i", array($post_id));

if (!$post->count()) {
    $protector->exit_error("Post inválido");
}

$post = $post->fetch_array();

$topico = $connection->run("SELECT * FROM tb_forum_topico WHERE id = ?", "i", array($post["topico_id"]));

if (!$topico->count()) {
    $protector->exit_error("Tópico inválido");
}

$topico = $topico->fetch_array();

if ($topico["criador_id"] != $userDetails->tripulacao["id"]
    && !$userDetails->tripulacao["adm"]
    && $post["tripulacao_id"] != $userDetails->tripulacao["id"]
) {
    $protector->exit_error("Sem permissão");
}

$connection->run("UPDATE tb_forum_post SET oculto = ? WHERE id = ?",
    "ii", array($post["oculto"] ? 0 : 1, $post_id));

echo "-O estado do post foi atualizado";