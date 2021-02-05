<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$topico_id = $protector->get_number_or_exit("topico");

$topico = $connection->run("SELECT * FROM tb_forum_topico WHERE id = ?", "i", array($topico_id));

if (!$topico->count()) {
    $protector->exit_error("Tópico inválido");
}

$topico = $topico->fetch_array();

if ($topico["criador_id"] != $userDetails->tripulacao["id"] && !$userDetails->tripulacao["adm"]) {
    $protector->exit_error("Sem permissão");
}

$connection->run("UPDATE tb_forum_topico SET resolvido = ? WHERE id = ?",
    "ii", array($topico["resolvido"] ? 0 : 1, $topico_id));

echo "-O estado do tópico foi atualizado";