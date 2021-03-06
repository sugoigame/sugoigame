<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$post_id = $protector->get_number_or_exit("post");
$tipo = $protector->get_enum_or_exit("tipo", array(1, 2));

$post = $connection->run("SELECT * FROM tb_forum_post WHERE id = ?", "i", array($post_id));

if (!$post->count()) {
    $protector->exit_error("Post inválido");
}

$like = $connection->run("SELECT * FROM tb_forum_likes WHERE tripulacao_id = ? AND post_id = ?",
    "ii", array($userDetails->tripulacao["id"], $post_id));

if ($like->count()) {
    $connection->run("UPDATE tb_forum_likes SET tipo = ? WHERE tripulacao_id = ? AND post_id = ?",
        "iii", array($tipo, $userDetails->tripulacao["id"], $post_id));
} else {
    $connection->run("INSERT INTO tb_forum_likes (tripulacao_id, tipo, post_id) VALUE (?,?,?)",
        "iii", array($userDetails->tripulacao["id"], $tipo, $post_id));
}