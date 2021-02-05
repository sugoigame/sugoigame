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
		echo("#Voce nao faz parte de uma aliança");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],1,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para expulsar jogadores.");
		exit();
	}
	
	if(!isset($_GET["cod"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$forma=mysql_real_escape_string($_GET["cod"]);
	
	if(!ereg("[0-9]", $forma)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	if($forma==$usuario["id"]){
		mysql_close();
		echo ("#Você não pode se explusar da aliança.");
		exit();
	}
	
	$query="SELECT * FROM tb_alianca_membros WHERE id='$forma'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Você não pode expulsar jogadores que nao estão na aliança.");
		exit();
	}
	
	$allydele=mysql_fetch_array($result);
	if($allydele["cod_alianca"]!=$usuario["alianca"]["cod_alianca"]){
		mysql_close();
		echo ("#Você não pode expulsar jogadores que nao estão na aliança.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_guerra WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		echo ("#Você está em guerra!");
		exit();
	}
	
	$query="DELETE FROM tb_alianca_membros WHERE id='$forma' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra_ajuda WHERE id='$forma' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	mysql_close();
	echo("Membro expulso");
?>