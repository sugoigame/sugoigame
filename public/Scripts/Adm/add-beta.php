<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$id_trip	= $protector->post_value_or_exit('id_conta');
$beta		= $protector->post_value_or_exit('beta');

$connection->run("UPDATE tb_conta SET beta = ? WHERE conta_id = ?",
			"ii", array($beta, $id_trip));

echo '%admin-beta';

