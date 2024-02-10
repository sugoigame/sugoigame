<?php
$valida = "EquipeSugoiGame2012";
include "../../Includes/conectdb.php";
if (! isset($_GET["login"])) {
	echo 0;
	exit();
}
$login = $protector->get_alphanumeric_or_exit("login");
if (! preg_match("/^[\w]+$/", $login)) {
	echo 0;
	exit();
}

$sql = "SELECT login FROM tb_usuarios WHERE login='$login'";
$result = $connection->run($sql);
$cont = $result->count();
if ($cont == 0)
	echo 1;
else
	echo 0;

?>

