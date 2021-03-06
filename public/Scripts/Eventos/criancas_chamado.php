<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

if (!$userDetails->buffs->get_efeito("chamado_infantil")) {
    $userDetails->buffs->add_buff(27, 24 * 60 * 60);
}

$response->send("O Bônus \"Chamado Infantil\" foi ativado por 24 horas.");