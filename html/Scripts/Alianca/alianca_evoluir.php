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
		echo ("#Voce nao faz parte de uma aliança");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if($permicao["autoridade"]>=3){
		mysql_close();
		echo ("#Você não tem permissão para isso.");
		exit();
	}
	if($usuario["alianca"]["xp"]<$usuario["alianca"]["xp_max"]){
		mysql_close();
		echo ("#Sua aliança não tem experiencia suficiente");
		exit();
	}
	if($usuario["alianca"]["lvl"]>=10){
		mysql_close();
		echo ("#Sua aliança já alcançou o nível máximo");
		exit();
	}
	$lvl=$usuario["alianca"]["lvl"]+1;
	$nxp=$usuario["alianca"]["xp"]-$usuario["alianca"]["xp_max"];
	$xp_max=$usuario["alianca"]["xp_max"]+500;
	
	$query="UPDATE tb_alianca SET lvl='$lvl', xp='$nxp', xp_max='$xp_max' 
	WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel cancelar o convite");
	
	mysql_close();
	echo("Novo nível alcançado!");
?>