<?php
	$valida = "EquipeSugoiGame2012";
	require "../Includes/conectdb.php";
	include "../Includes/verifica_login_sem_pers.php";
	include "../Includes/verifica_combate.php";
	
	if($incombate){
		echo "1";
	}
	else{
		echo "0";
	}
	mysql_close();
?>