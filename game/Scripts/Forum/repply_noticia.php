<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$noticia_id = $protector->post_number_or_exit("noticia");
$response = bbcode_to_html(htmlspecialchars($protector->post_value_or_exit("response")));

$noticia = $connection->run("SELECT * FROM tb_noticias WHERE cod_noticia = ?", "i", array($noticia_id));

if (!$noticia->count()) {
    $protector->exit_error("Tópico inválido");
}

$noticia = $noticia->fetch_array();

$connection->run("INSERT INTO tb_noticia_comment (conteudo, tripulacao_id, noticia_id) VALUE (?, ?, ?)",
    "sii", array($response, $userDetails->tripulacao["id"], $noticia_id));

echo "-Resposta enviada";