<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id = $protector->get_number_or_exit("id");

if (!$userDetails->tripulacao["adm"]) {
    $protector->exit_error("Acesso restrito");
}

$connection->run("UPDATE tb_kanban_item SET `column` = `column` - 1 WHERE id = ?", "i", $id);

echo "-Sugestão modificada com sucesso.";