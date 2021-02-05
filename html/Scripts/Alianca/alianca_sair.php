<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		header("location:../../?msg=Você precisa estar logado.");
		exit();
	}
	if(!$inally){
		mysql_close();
		header("location:../../?msg=Você não faz parte de uma aliança.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if($permicao["autoridade"]==0){
		mysql_close();
		header("location:../../?msg=Você não pode sair da sua aliança.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_guerra WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		header("location:../../?msg=Você está em guerra!");
		exit();
	}
	
	$query="DELETE FROM tb_alianca_membros WHERE id='".$usuario["id"]."' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra_ajuda WHERE id='".$usuario["id"]."' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	mysql_close();
	header("location:../../?ses=aliancaCriar");
?>