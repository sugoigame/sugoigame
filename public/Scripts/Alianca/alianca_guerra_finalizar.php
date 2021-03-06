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
		echo("#Voce nao faz parte de uma aliança");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],5,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para desafiar jogadores.");
		exit();
	}
	$query="SELECT * FROM tb_alianca_guerra WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)==0){
		mysql_close();
		echo ("#Você não está em guerra.");
		exit();
	}
	$guerra=mysql_fetch_array($result);
	
	$vit=FALSE;
	$perd=FALSE;
	if($guerra["pts"]<$guerra["vitoria"]){
		$vit=FALSE;
	}
	else{
		$vit=TRUE;
	}
	
	if(!$vit){
		$query="SELECT * FROM tb_alianca_guerra WHERE cod_alianca='".$guerra["cod_inimigo"]."'";
		$result=mysql_query($query);
		if(mysql_num_rows($result)==0){
			mysql_close();
			echo ("#Você não está em guerra.");
			exit();
		}
		$guerra_inimigo=mysql_fetch_array($result);
		
		if($guerra_inimigo["pts"]<$guerra["vitoria"]){
			$perd=FALSE;
		}
		else{
			$perd=TRUE;
		}
	}
	
	if($guerra["fim"]<atual_segundo()){
		if($guerra["pts"]>$guerra_inimigo["pts"]){
			$vit=TRUE;
			$perd=FALSE;
		}
		else if($guerra["pts"]<$guerra_inimigo["pts"]){
			$vit=FALSE;
			$perd=TRUE;
		}
		else{
			$query="DELETE FROM tb_alianca_guerra_ajuda 
			WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' OR cod_alianca='".$guerra["cod_inimigo"]."'";
			mysql_query($query) or die("nao foi possivel pegar a recompensas");
			
			$query="DELETE FROM tb_alianca_guerra 
			WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' OR cod_alianca='".$guerra["cod_inimigo"]."'";
			mysql_query($query) or die("nao foi possivel pegar a recompensas");
			
			mysql_close();
			header("location:../alianca_diplomacia.php");
			exit();
		}
	}
	
	if($vit){
		$query="SELECT * FROM tb_alianca WHERE cod_alianca='".$guerra["cod_inimigo"]."'";
		$result=mysql_query($query);
		$inimigo=mysql_fetch_array($result);
		
		$rep = $usuario["alianca"]["score"]+($guerra["vitoria"]/5)*50;
		$xp = $usuario["alianca"]["xp"]+($guerra["vitoria"]/5)*10;
		$coop= $guerra["vitoria"]*5;
		$vitorias = $usuario["alianca"]["vitorias"]+1;
		$query="UPDATE tb_alianca SET xp='$xp', score='$rep', vitorias='$vitorias' WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
		mysql_query($query) or die("nao foi possivel pegar a recompensa");
		
		$query="SELECT * FROM tb_alianca_guerra_ajuda INNER JOIN tb_alianca_membros ON tb_alianca_guerra_ajuda.id=tb_alianca_membros.id
		WHERE tb_alianca_guerra_ajuda.cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
		$result=mysql_query($query);
		$x=0;
		while ($membro=mysql_fetch_array($result)) {
			$membro_info[$x]=$membro;
			$x++;
			$coop_this=($coop*$membro["quant"])+$membro["cooperacao"];
			$query="UPDATE tb_alianca_membros SET cooperacao='$coop_this' 
			WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' AND id='".$membro["id"]."'";
			mysql_query($query) or die("nao foi possivel pegar a recompensa");
		}
		
		$derrotas=$inimigo["derrotas"]+1;
		$rep = $inimigo["score"]-($guerra["vitoria"]/5)*10;
		if($rep<0)$rep=0;
		$query="UPDATE tb_alianca SET score='$rep', derrotas='$derrotas' WHERE cod_alianca='".$inimigo["cod_alianca"]."'";
		mysql_query($query) or die("nao foi possivel pegar a recompensa");
	}
	if($perd){
		$query="SELECT * FROM tb_alianca WHERE cod_alianca='".$guerra["cod_inimigo"]."'";
		$result=mysql_query($query);
		$inimigo=mysql_fetch_array($result);
		
		$rep = $inimigo["score"]+($guerra["vitoria"]/5)*50;
		$xp = $inimigo["xp"]+($guerra["vitoria"]/5)*10;
		$coop= $guerra["vitoria"]*5;
		$vitorias = $inimigo["vitorias"]+1;
		$query="UPDATE tb_alianca SET xp='$xp', score='$rep', vitorias='$vitorias'  WHERE cod_alianca='".$inimigo["cod_alianca"]."'";
		mysql_query($query) or die("nao foi possivel pegar a recompensa");
		
		$query="SELECT * FROM tb_alianca_guerra_ajuda INNER JOIN tb_alianca_membros ON tb_alianca_guerra_ajuda.id=tb_alianca_membros.id
		WHERE tb_alianca_guerra_ajuda.cod_alianca='".$inimigo["cod_alianca"]."'";
		$result=mysql_query($query);
		while ($membro=mysql_fetch_array($result)) {
			$coop_this=($coop*$membro["quant"])+$membro["cooperacao"];
			$query="UPDATE tb_alianca_membros SET cooperacao='$coop_this' 
			WHERE cod_alianca='".$inimigo["cod_alianca"]."' AND id='".$membro["id"]."'";
			mysql_query($query) or die("nao foi possivel pegar a recompensa");
		}
		
		$derrotas=$usuario["alianca"]["derrotas"]+1;
		$rep = $usuario["alianca"]["score"]-($guerra["vitoria"]/5)*10;
		if($rep<0)$rep=0;
		$query="UPDATE tb_alianca SET score='$rep', derrotas='$derrotas' WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
		mysql_query($query) or die("nao foi possivel pegar a recompensa");
	}
	
	$query="DELETE FROM tb_alianca_guerra_ajuda 
	WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' OR cod_alianca='".$guerra["cod_inimigo"]."'";
	mysql_query($query) or die("nao foi possivel pegar a recompensas");
	
	$query="DELETE FROM tb_alianca_guerra 
	WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."' OR cod_alianca='".$guerra["cod_inimigo"]."'";
	mysql_query($query) or die("nao foi possivel pegar a recompensas");
	
	mysql_close();
	echo("Guerra finalizada!");
?>