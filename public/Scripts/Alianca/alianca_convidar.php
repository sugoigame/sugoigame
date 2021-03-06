<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado.");
		exit();
	}
	if(!$inally){
		mysql_close();
		echo("#Você não faz parte de uma aliança");
		exit();
	}
    
    $query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
    if(mysql_num_rows($result) >= 10){
        mysql_close();
		echo("#Sua aliança já atingiu o número máximo de membros permitidos.");
		exit();
    }
    
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],0,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para convidar jogadores.");
		exit();
	}
	
	if(!isset($_POST["nome"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$forma=mysql_real_escape_string($_POST["nome"]);
	
	if(!preg_match("/^[\w ]+$/", $forma)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	
	$query="SELECT * FROM tb_personagens INNER JOIN tb_usuarios ON tb_personagens.id=tb_usuarios.id
	WHERE tb_personagens.nome='$forma'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("Personagem não encontrado.");
		exit();
	}
	$id_conv=mysql_fetch_array($result);
	if($id_conv["id"]==$usuario["id"]){
		mysql_close();
		echo ("#Você não pode convidar você mesmo.");
		exit();
	}
	
	if($id_conv["faccao"]!=$usuario["faccao"]){
		mysql_close();
		echo ("#Você não pode convidar jogadores de facções diferentes.");
		exit();
	}
	if($id_conv["lvl"]<5){
		mysql_close();
		echo ("#É necessário que o jogador esteje no nível 5 para entrar na sua Aliança/Frota.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_guerra WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		echo ("#Você está em guerra!");
		exit();
	}
	
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$id_conv["id"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		echo ("Esse jogador já pertence a uma aliança.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_convite WHERE convidado='".$id_conv["id"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		echo ("Você já convidou este jogador.");
		exit();
	}
	
	$query="INSERT INTO tb_alianca_convite (cod_alianca, convidado)
	VALUES ('".$usuario["alianca"]["cod_alianca"]."', '".$id_conv["id"]."')";
	mysql_query($query) or die("Nao foi possivel convidar");
	
	$assunto="Convite para ";
	if($usuario["faccao"]==0)$assunto.="Frota";
	else $assunto.="Aliança";
	$texto="Está preparado para entrar para o meu time?\n
		Clique em \"Juntar-se\" no menu principal para ver meu convite!";
	$hora = "às ";
	$hora .= date("H:i",time());
	$hora.= " do dia ";
	$hora .= date("d/m/Y",time());
	
	$query = "INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora)
	VALUES ('".$usuario["id"]."', '".$id_conv["id"]."', '$assunto', '$texto', '$hora')";
	mysql_query($query) or die("Erro ao enviar mensagem, tente novamente ou contate o suporte.");
	
	mysql_close();
	echo("Convite enviado!");
	
?>