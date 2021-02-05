<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	include "../../Includes/verifica_missao.php";
	include "../../Includes/verifica_combate.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado!");
		exit();
	}
	if($incombate){
		mysql_close();
		echo("#Você está em combate!");
		exit();
	}
	if($inmissao AND !$inrecrute){
		mysql_close();
        echo ("#Você está ocupado em uma missão neste meomento.");
		exit();
    }
	if(!$inilha){
		mysql_close();
		echo ("#Você precisa estar em uma ilha!");
		exit();
	}
	$query="UPDATE tb_usuarios SET recrutando='0' WHERE id='".$usuario["id"]."'";
	mysql_query($query) or die("Erro ao iniciar recrutamento");
	
	mysql_close();
	echo("Recrutamento cancelado");
?>