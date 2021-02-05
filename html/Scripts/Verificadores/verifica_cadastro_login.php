<?php
	$valida = "EquipeSugoiGame2012";
	include "../../Includes/conectdb.php";
	if (!isset($_GET["login"])) {
		echo 0;
		exit();
	}
	$login =  mysql_real_escape_string($_GET["login"]);
	if (!preg_match("/^[\w]+$/", $login)) {
		echo 0;
		exit();
	}
	
	$sql = "SELECT login FROM tb_usuarios WHERE login='$login'";
	$result = mysql_query($sql); 
	$cont = mysql_num_rows($result);
	if($cont == 0) echo 1;
	else echo 0;
	mysql_close();
?>