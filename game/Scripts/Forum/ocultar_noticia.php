<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$noticia_id = $protector->get_number_or_exit("noticia");

$noticia = $connection->run("SELECT * FROM tb_noticia_comment WHERE id = ?", "i", array($noticia_id));

if (!$noticia->count()) {
    $protector->exit_error("Post inválido");
}

$noticia = $noticia->fetch_array();

if (!$userDetails->tripulacao["adm"]) {
    $protector->exit_error("Sem permissão");
}

$connection->run("UPDATE tb_noticia_comment SET oculto = ? WHERE id = ?",
    "ii", array($noticia["oculto"] ? 0 : 1, $noticia_id));

echo "-O estado do comentário foi atualizado";