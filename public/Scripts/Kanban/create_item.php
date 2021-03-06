<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$title = $protector->post_value_or_exit("title");
$description = $protector->post_value_or_exit("description");

$connection->run(
    "INSERT INTO tb_kanban_item (title, description, tripulacao_id) VALUES (?, ?, ?)",
    "ssi", array($title, $description, $userDetails->tripulacao["id"])
);

echo "-Sugestão enviada com sucesso.";