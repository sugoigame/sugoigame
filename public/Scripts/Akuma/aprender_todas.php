<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();
$protector->must_be_out_of_any_kind_of_combat();

$pers = $protector->get_tripulante_or_exit("cod");

aprende_todas_habilidades_disponiveis_akuma($pers);

$response->send($pers["nome"] . " aprendeu novas habilidades. Visite o menu de Habilidades para customiza-las!");