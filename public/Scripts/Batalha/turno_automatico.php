<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_in_any_kind_of_combat();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

if ($combate->vez_de_quem() != $combate->minhaTripulacao->indice) {
    $protector->exit_error("Não é a sua vez");
}
$combate->turno_automatico();


