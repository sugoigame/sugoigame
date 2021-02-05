<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";


$protector->need_tripulacao();


$connection->run("DELETE FROM tb_mensagens WHERE destinatario = ?",
    "i", array($userDetails->tripulacao["id"]));

echo "Mensagens apagadas com sucesso.";