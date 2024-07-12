<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

$cod_skil = $protector->post_number_or_exit("cod_skil");
$cod_pers = $protector->post_number_or_exit("pers");
$quadros = $protector->post_value_or_exit("quadro");

$combate->atacar($cod_pers, $cod_skil, $quadros);
