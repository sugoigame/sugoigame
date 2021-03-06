<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->need_navio();

$skin = $protector->get_number_or_exit("skin");

if ($skin != 0) {
    $has_skin = $connection->run("SELECT count(id) AS total FROM tb_tripulacao_skin_navio WHERE tripulacao_id = ? AND skin_id = ?",
        "ii", array($userDetails->tripulacao["id"], $skin))->fetch_array()["total"];

    if (!$has_skin) {
        $protector->exit_error("Você não possui essa aparência");
    }
}

$connection->run("UPDATE tb_usuarios SET skin_navio = ? WHERE id = ?",
    "ii", array($skin, $userDetails->tripulacao["id"]));

echo "-A aparência foi habilitada.";