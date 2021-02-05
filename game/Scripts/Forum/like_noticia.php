<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$noticia_id = $protector->get_number_or_exit("noticia");
$tipo = $protector->get_enum_or_exit("tipo", array(1, 2));

$noticia = $connection->run("SELECT * FROM tb_noticia_comment WHERE id = ?", "i", array($noticia_id));

if (!$noticia->count()) {
    $protector->exit_error("Post inválido");
}

$like = $connection->run("SELECT * FROM tb_noticia_likes WHERE tripulacao_id = ? AND comment_id = ?",
    "ii", array($userDetails->tripulacao["id"], $noticia_id));

if ($like->count()) {
    $connection->run("UPDATE tb_noticia_likes SET tipo = ? WHERE tripulacao_id = ? AND comment_id = ?",
        "iii", array($tipo, $userDetails->tripulacao["id"], $noticia_id));
} else {
    $connection->run("INSERT INTO tb_noticia_likes (tripulacao_id, tipo, comment_id) VALUE (?,?,?)",
        "iii", array($userDetails->tripulacao["id"], $tipo, $noticia_id));
}