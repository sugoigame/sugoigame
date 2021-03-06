<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado.");
		exit();
	}
	if(!$inally){
		mysql_close();
		echo ("#Você não faz parte de uma alianca");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],7,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para iniciar missões.");
		exit();
	}
	
	$query="DELETE FROM tb_alianca_missoes WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("Nao foi possivel assinar");
	
	mysql_close();
	echo("Missão abortada");
?>