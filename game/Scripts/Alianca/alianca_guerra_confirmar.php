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
	if($usuario["alianca"]["lvl"]<5){
		mysql_close();
		echo("#É necessário ter nível 5 para entrar em guerra!");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],0,1)==0){
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
	
	if(!preg_match("/^[\d]+$/", $forma)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$query="SELECT * FROM tb_alianca_guerra_pedidos WHERE cod_alianca='$forma' AND convidado='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Você não foi desafiado por essa aliança.");
		exit();
	}
	$tipo=mysql_fetch_array($result);
	
	$fim=atual_segundo()+($tipo["tipo"]/5)*86400;

	$query="INSERT INTO tb_alianca_guerra (cod_alianca, cod_inimigo, vitoria,fim)
	VALUES ('".$usuario["alianca"]["cod_alianca"]."', '$forma', ".$tipo["tipo"].", '$fim')";
	mysql_query($query) or die("Nao foi possivel assinar");
	
	$query="INSERT INTO tb_alianca_guerra (cod_alianca, cod_inimigo, vitoria, fim)
	VALUES ('$forma', '".$usuario["alianca"]["cod_alianca"]."', ".$tipo["tipo"].", '$fim')";
	mysql_query($query) or die("Nao foi possivel assinar");
	
	$query="DELETE FROM tb_alianca_guerra_pedidos WHERE cod_alianca='$forma' AND convidado='".$usuario["alianca"]["cod_alianca"]."' 
	LIMIT 1";
	mysql_query($query) or die("nao foi possivel cancelar o convite");
	
	mysql_close();
	echo("Desafio aceito!");
?>