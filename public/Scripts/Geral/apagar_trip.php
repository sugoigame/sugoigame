<?php
include "../../Includes/conectdb.php";

$protector->need_conta();

$trip = $protector->get_number_or_exit("trip");

$result = $connection->run("SELECT conta_id FROM tb_usuarios WHERE id = ?",
    "i", array($trip));

if (!$result->count()) {
    $protector->exit_error("Tripulação não encontrada");
}

$tripulacao = $result->fetch_array();

if ($tripulacao["conta_id"] != $userDetails->conta["conta_id"]) {
    $protector->exit_error("Tripulação inválida");
}

$result = $connection->run("SELECT * FROM tb_alianca_membros WHERE id = ?",
    "i", array($trip));

if ($result->count()) {
    $protector->exit_error("Essa tripulação faz parte de uma Aliança ou Frota. Você precisa sair dessa Aliança ou Frota antes de poder excluir a tripulação.");
}

$connection->run("DELETE FROM tb_usuarios WHERE id = ?",
    "i", array($trip));

echo "-Tripulação removida";

