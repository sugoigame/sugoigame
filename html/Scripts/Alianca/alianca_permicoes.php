<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		header("location:../../?msg=Você precisa estar logado.");
		exit();
	}
	if(!$inally){
		mysql_close();
		header("location:../../?msg=Voce nao faz parte de uma aliança");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if($permicao["autoridade"]!=0){
		mysql_close();
		header("location:../../?msg=Você não tem permissão para alterar cargos.");
		exit();
	}
	
	for($x=0;$x<12;$x++){
		if(isset($_POST["1_".$x])){
			$nperm1[$x]="1";
		}
		else{
			$nperm1[$x]="0";
		}
	}
	for($x=0;$x<12;$x++){
		if(isset($_POST["2_".$x])){
			$nperm2[$x]="1";
		}
		else{
			$nperm2[$x]="0";
		}
		
	}
	for($x=0;$x<12;$x++){
		if(isset($_POST["3_".$x])){
			$nperm3[$x]="1";
		}
		else{
			$nperm3[$x]="0";
		}
	}
	for($x=0;$x<12;$x++){
		if(isset($_POST["4_".$x])){
			$nperm4[$x]="1";
		}
		else{
			$nperm4[$x]="0";
		}
	}
	
	$nperm1=implode("",$nperm1);
	$nperm2=implode("", $nperm2);
	$nperm3=implode("", $nperm3);
	$nperm4=implode("", $nperm4);
	
	if(!preg_match("/^[\d]+$/", $nperm1)
	OR !preg_match("/^[\d]+$/", $nperm2)
	OR !preg_match("/^[\d]+$/", $nperm3)
	OR !preg_match("/^[\d]+$/", $nperm4)){
		mysql_close();
		header("location:../../?msg=Voce informou algum caracter invalido");
		exit();
	}
	
	$query="UPDATE tb_alianca SET `1`='$nperm1', `2`='$nperm2', `3`='$nperm3', `4`='$nperm4' 
	WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("Não foi possivel alterar permissoes");
	
	mysql_close();
	header("location:../../?ses=alianca&msg2=Permissões alteradas");
?>