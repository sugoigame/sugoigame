<?php
require('../../Includes/conectdb.php');

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->post_tripulante_or_exit("cod");
$quant = $protector->post_number_or_exit('quant');

if ($quant <= $pers['xp']) {
    $userDetails->remove_xp_personagem($quant, $pers);
    $userDetails->add_haki($pers, $quant);
} else {
    $protector->exit_error("Seu tripulante não tem XP suficiente");
}

echo ":";
