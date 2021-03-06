<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if (isset($_GET["desafiante"])) {
    $desafiante = $protector->get_number_or_exit("desafiante");
} else {
    $desafiante = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ? LIMIT 1",
        "i", array($userDetails->tripulacao["id"]))->fetch_array()["desafiante"];
}

$connection->run("DELETE FROM tb_combate_desafio WHERE desafiado = ? AND $desafiante = ?",
    "ii", array($userDetails->tripulacao["id"], $desafiante));
