<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if (!$userDetails->is_progress_finished()) {
    $protector->exit_error("Você ainda não finalizou este objetivo");
}

$rewards = $userDetails->get_progress_reward();

if ($rewards["xp"]) {
    $userDetails->xp_for_all($rewards["xp"]);
}
if ($rewards["berries"]) {
    $connection->run("UPDATE tb_usuarios SET berries = berries + " . $rewards["berries"] . " WHERE id = ?", "i", $userDetails->tripulacao["id"]);
}

$connection->run("UPDATE tb_usuarios SET progress = ? WHERE id = ?",
    "ii", array($userDetails->get_next_progress(), $userDetails->tripulacao["id"]));

echo "-Objetivo concluído";
