<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_trip	= $protector->post_value_or_exit('id_trip');
$x		= $protector->post_value_or_exit('x');
$y		= $protector->post_value_or_exit('y');

$connection->run("UPDATE tb_usuarios SET x = ? and y = ? + 359 WHERE id = ?",
"iii", array($x,$y,$id_trip));

echo '%admin-teleporte';

