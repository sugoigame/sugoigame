<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$cod = $protector->get_number_or_exit("cod");

$connection->run("DELETE FROM tb_mensagens WHERE destinatario = ? AND cod_mensagem = ?",
    "ii", array($userDetails->tripulacao["id"], $cod));

echo "Mensagem apagada com sucesso.";