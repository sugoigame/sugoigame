<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();
$protector->must_be_in_ilha();

$cod = $protector->get_number_or_exit("cod");

$connection->run(
    "UPDATE tb_personagens SET 
    ativo = 0,
    respawn = 0,
    respawn_tipo = 0
    WHERE cod = ? AND id = ?"
    , "ii", array($cod, $userDetails->tripulacao["id"]));

echo "%tripulantesInativos";