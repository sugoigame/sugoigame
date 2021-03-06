<?php
	$valida = "EquipeSugoiGame2012";
	include "../../Includes/conectdb.php";
	$email = mysql_real_escape_string($_GET["email"]);
	$sql = "SELECT email FROM tb_conta WHERE email='$email'";
	$result = mysql_query($sql); 
	$cont = mysql_num_rows($result);
	if($cont == 0) echo 1;
	else echo 0;
	mysql_close();
?>