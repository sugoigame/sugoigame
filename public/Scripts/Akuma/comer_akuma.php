<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$pers = $protector->get_tripulante_or_exit("cod");
$cod_akuma = $protector->get_number_or_exit("akuma");

if ($pers["akuma"]) {
    $protector->exit_error("Personagem invalido");
}

if (! $userDetails->get_item($cod_akuma, TIPO_ITEM_AKUMA)) {
    $protector->exit_error("Você não tem essa akuma");
}

$userDetails->reduz_item($cod_akuma, TIPO_ITEM_AKUMA, 1);

$connection->run("UPDATE tb_personagens SET akuma='$cod_akuma' WHERE cod=?", "i", [$pers["cod"]]);

echo ":";
