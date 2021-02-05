<?php
	$valida = "EquipeSugoiGame2012";
	include "../../Includes/conectdb.php";
	
	if (!isset($_GET["alianca"])) {
		echo 0;
		exit();
	}
	$alianca =  mysql_real_escape_string($_GET["alianca"]);
	if (!preg_match("/^[\w]+$/", $alianca)) {
		echo 0;
		exit();
	}
	$sql = "SELECT nome FROM tb_alianca WHERE nome='$alianca'";
	$result = mysql_query($sql); 
	$cont = mysql_num_rows($result);
	if($cont == 0) echo 1;
	else echo 0;
	mysql_close();
?>