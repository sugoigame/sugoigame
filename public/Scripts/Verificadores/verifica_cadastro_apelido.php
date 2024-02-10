<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";
if (! isset($_GET["apelido"])) {
	echo 0;
	exit();
}
$capitao = $protector->get_alphanumeric_or_exit("apelido");
$sql = "SELECT * FROM tb_usuarios WHERE tripulacao='$capitao'";
$result = $connection->run($sql);
$cont = $result->count();
if ($cont == 0)
	echo 1;
else
	echo 0;

?>

