<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!($conect OR $contaOk)){
		mysql_close();
		header("location:../../index.php");
		exit();
	}
	
	session_destroy();
	
	setcookie("sg_c","",time()-80000,'/', FALSE, TRUE);
	setcookie("sg_k","",time()-80000,'/', FALSE, TRUE);
	
	$query="UPDATE tb_conta SET cookie='0', tripulacao_id = NULL WHERE conta_id='".$conta["conta_id"]."'";
	mysql_query($query) or die ("nao foi possivel deslogar");
	
	mysql_close();
	header("location:../../login.php");
