<?php
	$valida = "EquipeSugoiGame2012";
	require_once "../../Includes/conectdb.php";
	include_once "../../Includes/verifica_login.php";
	include_once "../../Includes/verifica_combate.php";
	
	if(!$conect){
		mysql_close();
		echo "#Você precisa estar logado!";
		exit();
	}
	if(!$incombate){
		mysql_close();
		echo "%oceano";
		exit();
	}

	include "batalha_tabuleiro_content.php";
	
	mysql_close();
