<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		echo "#Você precisa estar logado.";
		exit();
	}
	if($usuario["adm"]!=1){
		mysql_close();
		echo "#Você precisa estar logado.";
		exit();
	}
	
	$id=mysql_real_escape_string($_GET["id"]);
	if(!preg_match("/^[\d]+$/", $id)){
		mysql_close();
		echo "#informação inválida";
		exit();
	}
	
	$query="UPDATE tb_torneio_inscricao SET status='2' WHERE id='$id'";
	mysql_query($query);
	mysql_close();
	
	echo "inscrição aprovada";
