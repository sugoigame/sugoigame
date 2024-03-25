<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_trip	= $protector->post_value_or_exit('id_trip');
$quant		= $protector->post_value_or_exit('quantidade_beries');

$connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
"ii", array($quant,$id_trip));

echo '%admin-add-beries';

