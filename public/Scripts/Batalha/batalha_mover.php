<?php
require "../../Includes/conectdb.php";

$pers_cod = $protector->get_number_or_exit("pers");
$quadro = $protector->get_test_pass_or_exit("quadro", "/^[0-9a-zA-Z;_]+$/");

$protector->need_tripulacao();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

$combate->mover($pers_cod, $quadro);
