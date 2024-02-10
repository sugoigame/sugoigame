<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";

if (! isset($_GET["alianca"])) {
	echo 0;
	exit();
}
$alianca = $protector->get_alphanumeric_or_exit("alianca");
if (! preg_match("/^[\w]+$/", $alianca)) {
	echo 0;
	exit();
}
$sql = "SELECT nome FROM tb_alianca WHERE nome='$alianca'";
$result = $connection->run($sql);
$cont = $result->count();
if ($cont == 0)
	echo 1;
else
	echo 0;

?>

