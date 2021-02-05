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

if ($personagem["time_casual"]) {
    $protector->exit_error("Esse personagem já está no time");
}

$tamanho_time = $connection->run("SELECT count(*) AS total FROM tb_personagens WHERE id = ? AND time_casual = 1",
    "i", array($userDetails->tripulacao["id"]))->fetch_array()["total"];

if ($tamanho_time >= TAMANHO_TIME_COLISEU) {
    $protector->exit_error("Seu time já está cheio");
}

$same_tatic = $connection->run(
    "SELECT * FROM tb_personagens
      WHERE id = ? 
      AND tatic_a <> '0' AND tatic_a = ?
      AND tatic_d <> '0' AND tatic_d = ?
      AND tatic_p <> '0' AND tatic_p = ?
      AND cod <> ? AND time_casual = 1",
    "isssi", array($userDetails->tripulacao["id"], $personagem["tatic_a"], $personagem["tatic_d"], $personagem["tatic_p"], $pers_cod)
);

if ($same_tatic->count()) {
    $protector->exit_error("As taticas deste tripulante entram em conflito com as táticas de " . $same_tatic->fetch_array()["nome"]);
}

$connection->run("UPDATE tb_personagens SET time_casual = 1 WHERE cod = ?", "i", array($pers_cod));

echo "%localizadorCasual";