<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		header("location:../../index.php");
		exit();
	}
	
	$query="UPDATE tb_conta SET tripulacao_id=NULL WHERE conta_id='".$usuario["conta_id"]."'";
	mysql_query($query) or die ("nao foi possivel deslogar");
	
	mysql_close();
	header("location:../../?ses=seltrip");
