<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$skin = $protector->get_number_or_exit("borda");
$pers_cod = $protector->get_number_or_exit("pers");

$pers = $userDetails->get_pers_by_cod($pers_cod);

if (!$pers) {
    $protector->exit_error("Personagem inválido");
}

if ($skin != 0) {
    $has_skin = $connection->run("SELECT count(id) AS total FROM tb_tripulacao_bordas WHERE tripulacao_id = ? AND borda = ?",
        "ii", array($userDetails->tripulacao["id"], $skin))->fetch_array()["total"];

    if (!$has_skin) {
        $protector->exit_error("Borda inválida");
    }
}

$connection->run("UPDATE tb_personagens SET borda = ? WHERE cod = ?",
    "ii", array($skin, $pers_cod));

echo "-Borda habilitada!";