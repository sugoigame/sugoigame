<?php
include "../../Includes/conectdb.php";

$protector->need_tripulacao();

$connection->run("UPDATE tb_usuarios SET navegacao_automatica = ? WHERE id = ?",
    "ii", array($userDetails->tripulacao["navegacao_automatica"] ? 0 : 1, $userDetails->tripulacao["id"]));

echo "%oceano";