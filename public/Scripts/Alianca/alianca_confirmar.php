<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		header("location:../../?msg=Você precisa estar logado.");
		exit();
	}
	if($inally){
		mysql_close();
		header("location:../../?msg=Você já faz parte de uma aliança");
		exit();
	}
	if(!isset($_GET["cod"])){
		mysql_close();
		header("location:../../?msg=Você informou algum caracter inválido.");
		exit();
	}
	$forma=mysql_real_escape_string($_GET["cod"]);
	
	if(!preg_match("/^[\d]+$/", $forma)){
		mysql_close();
		header("location:../../?msg=Você informou algum caracter inválido.");
		exit();
	}
	
	$query="SELECT * FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='".$usuario["id"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		header("location:../../?msg=Você não foi convidado para essa aliança.");
		exit();
	}
    
    $query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='$forma'";
	$result=mysql_query($query);
    if(mysql_num_rows($result) >= 10){
        $query="DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='".$usuario["id"]."' LIMIT 1";
        mysql_query($query) or die("nao foi possivel cancelar o convite");
        
        mysql_close();
		echo("#Essa aliança aliança já atingiu o número máximo de membros permitidos.");
		exit();
    }

	$query="INSERT INTO tb_alianca_membros (cod_alianca, id)
	VALUES ('$forma', '".$usuario["id"]."')";
	mysql_query($query) or die("Nao foi possivel assinar");
	
	$query="DELETE FROM tb_alianca_convite WHERE cod_alianca='$forma' AND convidado='".$usuario["id"]."' LIMIT 1";
	mysql_query($query) or die("nao foi possivel cancelar o convite");
	
	mysql_close();
	header("location:../../?ses=alianca");
?>