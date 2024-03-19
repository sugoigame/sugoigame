<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();
$protector->must_be_out_of_missao_and_recrute();

$alvo = $protector->get_number_or_exit("id");
$tipo = $protector->get_enum_or_exit("tipo", array(TIPO_ATAQUE, TIPO_AMIGAVEL, TIPO_COLISEU, TIPO_CONTROLE_ILHA));

inicia_combate($alvo, $tipo);

echo ("%combate");
