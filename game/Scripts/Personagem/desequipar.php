<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado.");
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
    
	$query = "SELECT * FROM tb_usuario_itens WHERE id='".$usuario["id"]."'";
	$result = mysql_query($query);
	$cont = mysql_num_rows($result);
	
	if($cont <$usuario["capacidade_iventario"]){
		$query = "SELECT * FROM tb_personagens WHERE cod='$perso'";
		$result = mysql_query($query);
		$equipado = mysql_fetch_array($result);
		if($equipado["cod_acessorio"] == $item AND $equipado["id"] == $usuario["id"]){
			$query = "UPDATE tb_personagens SET cod_acessorio='0' WHERE cod='$perso'";
			mysql_query($query) or die("erro ao remover o item");
			
			$query = "INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES ('".$usuario["id"]."', '".$equipado["cod_acessorio"]."', '0', '1')";
			mysql_query($query) or die("Nao foi possivel inserir o item");
			
			mysql_close();
			echo("Item removido");
		}
		else{
			mysql_close();
			echo ("#Seu personagem não tem este item equipado.");
		}
	}
	else{
		mysql_close();
		echo ("#A capacidade do Iventário é de 55 itens apenas.");
	}
?>