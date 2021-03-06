<?php
	$valida = "EquipeSugoiGame2012";
	include "../../Includes/conectdb.php";
	if (!isset($_GET["apelido"])) {
		echo 0;
		exit();
	}
	$capitao = mysql_real_escape_string($_GET["apelido"]);
	if (!preg_match("/^[\w ]+$/", $capitao)) {
		echo 0;
		exit();
	}
	$sql = "SELECT * FROM tb_usuarios WHERE tripulacao='$capitao'";
	$result = mysql_query($sql); 
	$cont = mysql_num_rows($result);
	if($cont == 0) echo 1;
	else echo 0;
	mysql_close();
?>