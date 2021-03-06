<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado!");
		exit();
	}
	if(!isset($_GET["cod"])){
		mysql_close();
		echo ("#Informações insuficientes");
		exit();
	}
	
	$cod=$_GET["cod"];
	
	if(!preg_match("/^[\d]+$/", $cod)){
		mysql_close();
		echo ("#Informações inválidas");
		exit();
	}
	
	$query="SELECT * FROM tb_personagens WHERE id='".$usuario["id"]."' AND cod='$cod'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Personagem não encontrado");
		exit();
	}
	
	$query="UPDATE tb_personagens SET tatic_a='0' WHERE cod='$cod'";
	mysql_query($query) or die("Nao foi possivel atualizar a posição");
	
	echo "Posição aleatória definida!";
	mysql_close();
