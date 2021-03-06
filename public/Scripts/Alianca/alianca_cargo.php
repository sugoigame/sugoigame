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
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],2,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para alterar cargos.");
		exit();
	}
	
	if(!isset($_GET["cod"]) OR !isset($_GET["cargo"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$forma=mysql_real_escape_string($_GET["cod"]);
	$cargo=mysql_real_escape_string($_GET["cargo"]);
	
	if(!preg_match("/^[\d]+$/", $forma) OR !preg_match("/^[\d]+$/", $cargo)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	if($forma==$usuario["id"]){
		mysql_close();
		echo ("#Você não pode alterar o próprio cargo.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='$forma'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		echo ("#Você não pode alterar cargo de jogadores que nao estão na aliança.");
		exit();
	}
	
	$allydele=mysql_fetch_array($result);
	if($allydele["cod_alianca"]!=$usuario["alianca"]["cod_alianca"]){
		mysql_close();
		echo ("#Você não pode alterar cargo de jogadores que nao estão na aliança.");
		exit();
	}
	if($cargo==0){
		if($permicao["autoridade"]!=0){
			mysql_close();
			echo ("#Você não tem permissão para nomear outro lider.");
			exit();
		}
		else{
			$query="UPDATE tb_alianca_membros SET autoridade='1' WHERE id='".$usuario["id"]."' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
			mysql_query($query) or die("nao foi possivel expulsar");
		}
	}
	
	$query="UPDATE tb_alianca_membros SET autoridade='$cargo' WHERE id='$forma' AND cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	mysql_close();
	echo("Autoridade alterada");
?>