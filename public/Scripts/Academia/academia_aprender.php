<?php
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login_sem_pers.php";

$protector->must_be_out_of_missao();

$classes = DataLoader::load("habilidades")["classes"];

$class = $protector->get_enum_or_exit("class", array_keys($classes));
$personagem = $protector->get_tripulante_or_exit("cod");

$classe = $classes[$class];

$query = "UPDATE tb_personagens SET classe='$class' WHERE cod='" . $personagem["cod"] . "'";
$connection->run($query);
$response->send_conquista_pers($personagem, $personagem["nome"] . " aprendeu o estilo " . $classe["nome"] . "!");
