<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$combate = Regras\Combate\Combate::build($connection, $userDetails, $protector);

$combate->perder_vez();

echo("%combate");
