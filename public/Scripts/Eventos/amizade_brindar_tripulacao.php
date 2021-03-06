<?php
require "../../Includes/conectdb.php";

$protector->exit_error("Evento indisponivel");
$protector->need_tripulacao();

$buffs = [];
if (!$userDetails->buffs->has_buff(1)) {
    $userDetails->buffs->add_buff(1, 24 * 60 * 60);
    $buffs[] = "Confiança de Herói";
}
if (!$userDetails->buffs->has_buff(2)) {
    $userDetails->buffs->add_buff(2, 24 * 60 * 60);
    $buffs[] = "Postura Honrosa";
}
if (!$userDetails->buffs->has_buff(7)) {
    $userDetails->buffs->add_buff(7, 24 * 60 * 60);
    $buffs[] = "Reputação Perigosa";
}
if (!$userDetails->buffs->has_buff(8)) {
    $userDetails->buffs->add_buff(8, 24 * 60 * 60);
    $buffs[] = "Nome Famoso";
}

if (count($buffs)) {
    echo "Kanpai! Você ativou os efeitos " . implode(", ", $buffs) . " por 24 horas!";
} else {
    echo "Kanpai!";
}
