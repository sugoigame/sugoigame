<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Voce precisa estar logado.");
		exit();
	}
	
	if (!isset($_GET["item"])) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	if (!isset($_GET["person"])) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$item = mysql_real_escape_string($_GET["item"]);
	$perso = mysql_real_escape_string($_GET["person"]);
	
	if (!preg_match("/^[\d]+$/", $item)) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	if (!preg_match("/^[\d]+$/", $perso)) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$query = "SELECT cod_acessorio FROM tb_personagens WHERE id='".$usuario["id"]."' AND cod='$perso'";
	$result = mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Personagem não encontrado.");
		exit();
	}
	$equipado = mysql_fetch_array($result);
	if($equipado["cod_acessorio"] == "0"){
		$query = "SELECT * FROM tb_usuario_itens WHERE id='".$usuario["id"]."' AND cod_item='$item' AND tipo_item='0'";
		$result = mysql_query($query);
		$cont = mysql_num_rows($result);
		if($cont != "0"){
			$query = "DELETE FROM tb_usuario_itens WHERE id='".$usuario["id"]."' AND cod_item='$item' AND tipo_item='0' LIMIT 1";
			mysql_query($query) or die("Nao foi possivel remover o item");
			
			$query = "UPDATE tb_personagens SET cod_acessorio='$item' WHERE cod='$perso'";
			mysql_query($query) or die("Nao foi possivel atualizar o item");
			
			mysql_close();
			echo("Item equipado!");
		}
		else{
			mysql_close();
			echo ("#Você não possui este item, por acaso andou roubando por aí?");
		}
	}
	else{
		$query = "SELECT * FROM tb_usuario_itens WHERE id='".$usuario["id"]."' AND cod_item='$item' AND tipo_item='0'";
		$result = mysql_query($query);
		$cont = mysql_num_rows($result);
		if($cont != 0){
			$query = "UPDATE tb_personagens SET cod_acessorio='$item' WHERE cod='$perso'";
			mysql_query($query) or die("Nao foi possivel atualizar o item");
			
			$query = "DELETE FROM tb_usuario_itens WHERE id='".$usuario["id"]."' AND cod_item='$item' AND tipo_item='0' LIMIT 1";
			mysql_query($query) or die("Nao foi possivel remover o item");
			
			$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES ('".$usuario["id"]."', '".$equipado["cod_acessorio"]."', '0', '1')";
			mysql_query($query) or die("Nao foi possivel inserir o item");
			
			mysql_close();
			echo("Item equipado!");
		}
		else{
			mysql_close();
			echo ("#Você não possui este item, por acaso andou roubando por aí?");
		}
	}
?>