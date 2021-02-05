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
	if (!isset($_GET["pers"])) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	if (!isset($_GET["pts"])) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$item = mysql_real_escape_string($_GET["item"]);
	$perso = mysql_real_escape_string($_GET["pers"]);
	$pts = mysql_real_escape_string($_GET["pts"]);
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
	if (!preg_match("/^[\d]+$/", $pts)) {
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$query="SELECT * FROM tb_item_equipamentos WHERE item='$item' LIMIT 1";
	$result=mysql_query($query);
	$equipamento=mysql_fetch_array($result);
	
	$query="SELECT * FROM tb_personagens WHERE id='".$usuario["id"]."' AND cod='$perso'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Personagem não encontrado.");
		exit();
	}
	$personagem=mysql_fetch_array($result);
	
	$query="SELECT * FROM tb_personagem_equip_treino WHERE cod='$perso' AND item='$item'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		$treino["xp"]=0;
		$insert=TRUE;
	}
	else{
		$treino=mysql_fetch_array($result);
		$insert=FALSE;
	}
	$treino_max=$equipamento["treino_max"]*$personagem["lvl"];
	
	$treino_xp=$treino["xp"]+$pts;
	if($treino_xp>$treino_max)$treino_xp=$treino_max;
	
	if($treino_xp==$treino_max)$pts=$treino_max-$treino["xp"];
	
	if($usuario["disposicao"]<$pts){
		mysql_close();
		echo ("#Você não tem disposição para treinar.");
		exit();
	}
	
	$disp=$usuario["disposicao"]-$pts;
	$query="UPDATE tb_usuarios SET disposicao='$disp' WHERE id='".$usuario["id"]."'";
	mysql_query($query) or die("Nao foi possivel treinar");
	
	if($insert){
		$query="INSERT INTO tb_personagem_equip_treino (cod, item, xp) VALUES ('$perso', '$item', '$treino_xp')";
	}
	else{
		$query="UPDATE tb_personagem_equip_treino SET xp='$treino_xp' WHERE cod='$perso' AND item='$item'";
	}
	mysql_query($query) or die("Nao foi possivel treinar");
	
	mysql_close();
	echo ":equipamentos&outro=".$personagem["cod"];
?>