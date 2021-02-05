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
		header("location:../../?msg=Você não faz parte de uma aliança.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if($permicao["autoridade"]!=0){
		mysql_close();
		header("location:../../?msg=Você não permissao para apagar essa aliança.");
		exit();
	}
	
	$query="DELETE FROM tb_alianca_membros WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra_ajuda WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra WHERE cod_inimigo='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_convite WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra_pedidos WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_guerra_pedidos WHERE convidado='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_aliados WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca_aliados WHERE cod_aliado='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	$query="DELETE FROM tb_alianca WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	mysql_query($query) or die("nao foi possivel expulsar");
	
	mysql_close();
	header("location:../../?ses=aliancaCriar");
?>