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
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],4,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para desafiar jogadores.");
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
	
	$query="DELETE FROM tb_alianca_guerra_pedidos 
	WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' AND convidado='$forma' LIMIT 1";
	mysql_query($query) or die("nao foi possivel cancelar o convite");
	
	mysql_close();
	echo("Desafio cancelado");
?>