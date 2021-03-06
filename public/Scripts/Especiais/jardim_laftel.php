<?
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado!");
		exit();
	}
	if(!$inilha){
		mysql_close();
		echo "#Você precisa estar em uma ilha.";
		exit();
	}
	if($usuario["ilha"]!=47){
		mysql_close();
		echo "#Você precisa estar em uma ilha.";
		exit();
	}
	
	$query="SELECT * FROM tb_jardim_laftel WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	
	$possivel = FALSE;
	if(mysql_num_rows($result)==0)
		$possivel=TRUE;
	else {
		$tempo=mysql_fetch_array($result);
		
		if($tempo["tempo"]<atual_segundo()) $possivel=TRUE;
	}
	
	if(!$possivel){ 
		mysql_close();
		echo "#Você ainda não pode colher uma Akuma no Mi.";
		exit();
	}
	
	$query="SELECT * FROM tb_usuario_itens WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)>=$usuario["capacidade_iventario"]){ 
		mysql_close();
		echo "#Seu inventário está cheio.";
		exit();
	}
	
	$tipo=rand(8,10);
	$cod=rand(100,110);
	
	$query="DELETE FROM tb_jardim_laftel WHERE id='".$usuario["id"]."'";
	mysql_query($query);
	
	$ntime=atual_segundo()+604800;
	
	$query="INSERT INTO tb_jardim_laftel (id, tempo) VALUES ('".$usuario["id"]."', '$ntime')";
	mysql_query($query) or die("Nao foi possivel inserir o registro");
	
	$query="INSERT INTO tb_usuario_itens (id, tipo_item, cod_item)
	VALUES ('".$usuario["id"]."', '$tipo', '$cod')";
	mysql_query($query) or die("Nao foi possivel inserir o registro");
	
	echo "Você recebeu uma Akuma no Mi!";
	mysql_close();
	
	
	
