<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_ilha();

$id = $protector->get_number_or_exit("id");

$formacao = $connection->run("SELECT * FROM tb_tripulacao_formacao WHERE id = ? AND tripulacao_id = ?",
    "ii", array($id, $userDetails->tripulacao["id"]));

if (!$formacao->count()) {
    $protector->exit_error("Essa formação não existe");
}

$formacao_id = $formacao->fetch_array()["formacao_id"];

$connection->run("DELETE FROM tb_tripulacao_formacao WHERE formacao_id = ? AND tripulacao_id = ?",
    "si", array($formacao_id, $userDetails->tripulacao["id"]));

echo "Formação removida!";