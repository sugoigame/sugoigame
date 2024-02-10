<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";
$email = $_GET["email"];
$sql = "SELECT email FROM tb_conta WHERE email= ?";
$result = $connection->run($sql, "s", $email);
$cont = $result->count();
if ($cont == 0)
	echo 1;
else
	echo 0;

?>

