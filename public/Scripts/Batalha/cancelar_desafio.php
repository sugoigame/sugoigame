<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$desafiado = $protector->get_number_or_exit("desafiado");
$connection->run("DELETE FROM tb_combate_desafio WHERE desafiado = ? AND desafiante = ?", "ii", [
    $desafiado,
    $userDetails->tripulacao["id"]
]);
