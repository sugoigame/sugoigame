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
		echo("#Você não faz parte de uma aliança");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],3,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para alterar cargos.");
		exit();
	}
	
	if(!isset($_POST["mural"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$texto=mysql_real_escape_string(strip_tags($_POST["mural"]));
	
	if(!ereg("([0-9a-zA-Z])", $texto)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$query="UPDATE tb_alianca SET mural='$texto' WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	mysql_close();
	echo("Mural alterado");
?>