<?php
    $valida = "EquipeSugoiGame2012";
    require "../../Includes/conectdb.php";
    include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado.");
		exit();
	}
	
	if(!isset($_GET["pers"]) OR !isset($_GET["ini"]) OR !isset($_GET["fa"])){
		mysql_close();
		echo "Você informou algo inválido";
		exit();
	}
	
	if(!preg_match("/^[\d]+$/",$_GET["pers"]) OR !preg_match("/^[\d]+$/",$_GET["ini"]) OR !preg_match("/^[\d]+$/",$_GET["fa"])){
		mysql_close();
		echo "Você informou algum caracter inválido";
		exit();
	}
	
	$pers=$_GET["pers"];
	$ini=$_GET["ini"];
	$fa=$_GET["fa"];
	
	$query="DELETE FROM tb_inimigos WHERE id='".$usuario["id"]."' AND personagem='$pers' AND inimigo='$ini' AND fa='$fa' LIMIT 1";
	mysql_query($query) or die("Não foi possível remover esse registro da lista negra");
	
	mysql_close();
	echo "Registro removido";
