<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_trip	= $protector->post_value_or_exit('id_conta');
$quant		= $protector->post_value_or_exit('quantidade_ouro');

$userDetails->add_ouro($quant,$id_trip);

echo '%admin-add-ouro';

