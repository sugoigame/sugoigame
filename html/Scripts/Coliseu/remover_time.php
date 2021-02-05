<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers_cod = $protector->get_number_or_exit("cod");

$personagem = $connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?", "ii", array($pers_cod, $userDetails->tripulacao["id"]));

if (!$personagem->count()) {
    $protector->exit_error("Personagem inválido");
}

$personagem = $personagem->fetch_array();

if (!$personagem["time_coliseu"]) {
    $protector->exit_error("Esse personagem não está no time");
}

$connection->run("UPDATE tb_personagens SET time_coliseu = 0 WHERE cod = ?", "i", array($pers_cod));

echo "%coliseu";