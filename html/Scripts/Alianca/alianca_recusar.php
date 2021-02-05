<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		header("location:../../?msg=Você precisa estar logado.");
		exit();
	}
	
	$forma=mysql_real_escape_string($_GET["cod"]);
	
	if(!preg_match("/^[\d]+$/", $forma)){
		mysql_close();
		header("location:../../?msg=Você informou algum caracter inválido.");
		exit();
	}
	
	$query="DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='".$usuario["id"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel cancelar o convite");
	
	mysql_close();
	header("location:../../?ses=aliancaCriar");
?>