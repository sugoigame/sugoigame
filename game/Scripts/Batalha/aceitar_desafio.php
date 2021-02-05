<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

if (isset($_GET["desafiante"])) {
    $desafiante = $protector->get_number_or_exit("desafiante");
} else {
    $desafiante = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ? LIMIT 1",
        "i", array($userDetails->tripulacao["id"]))->fetch_array()["desafiante"];
}

$desafiado = $connection->run("SELECT * FROM tb_combate_desafio WHERE desafiado = ? AND desafiante = ?",
    "ii", array($userDetails->tripulacao["id"], $desafiante));

if (!$desafiado->count()) {
    $protector->exit_error("Você não foi desafiado");
}

header("location:../Mapa/mapa_atacar.php?id=" . $desafiante . "&tipo=3");
