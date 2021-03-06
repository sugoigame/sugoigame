<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id = $protector->get_number_or_exit("id");

if (!$userDetails->tripulacao["adm"]) {
    $protector->exit_error("Acesso restrito");
}

$connection->run("DELETE FROM tb_kanban_item WHERE id = ?", "i", $id);

echo "-Sugestão removida com sucesso.";