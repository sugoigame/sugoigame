<?php
include "../../Includes/conectdb.php";

$protector->need_conta();

$trip = $protector->get_number_or_exit("trip");

$result = $connection->run("SELECT conta_id FROM tb_usuarios WHERE id = ?",
    "i", array($trip));

if (!$result->count()) {
    $protector->redirect_error("Tripulação não encontrada", "seltrip");
}

$tripulacao = $result->fetch_array();

if ($tripulacao["conta_id"] != $userDetails->conta["conta_id"]) {
    $protector->redirect_error("Tripulação inválida", "seltrip");
}

$connection->run("UPDATE tb_conta SET tripulacao_id = ? WHERE conta_id = ?",
    "ii", array($trip, $userDetails->conta["conta_id"]));

header("location:../../?ses=home");
	
