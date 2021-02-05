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
	if(!$inilha){
		mysql_close();
		echo("#Você precisa estar em uma ilha");
		exit();
	}
	
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],11,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para isso.");
		exit();
	}
	
	if(!isset($_GET["quant"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$quant=mysql_real_escape_string($_GET["quant"]);
	
	if(!preg_match("/^[\d]+$/", $quant)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	if($quant<0){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	if($personagem[0]["lvl"]<15){
		mysql_close();
		echo ("#É necessário ter o capitão no nível 15 para utilizar o banco.");
		exit();
	}
	
	if($usuario["alianca"]["banco"]<$quant){
		mysql_close();
		echo ("#O banco não tem todo esse dinheiro.");
		exit();
	}
	
	$quant_a=$usuario["alianca"]["banco"]-$quant;
	
	$quant_u=$usuario["berries"]+$quant;
	
	$query="INSERT INTO tb_alianca_banco_log (cod_alianca, usuario, item, tipo)
	VALUES ('".$usuario["alianca"]["cod_alianca"]."', '".$personagem[0]["nome"]."', '$quant Berries', '2')";
	mysql_query($query);
	
	$query="UPDATE tb_usuarios SET berries='$quant_u' WHERE id='".$usuario["id"]."'";
	mysql_query($query) or die("não foi possivel depositar o dinheiro");
	
	$query="UPDATE tb_alianca SET banco='$quant_a' WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("não foi possivel depositar o dinheiro");
	
	mysql_close();
	echo("@Dinheiro retirado");
	
?>