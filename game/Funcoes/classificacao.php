<?php 
function classificacao($rep,$fac){
	if($rep< 1000){
		if($fac == 0){
			$patente= "Marinheiro Aprendiz";
		}
		else{
			$patente= "Subordinado";
		}
	}
	else if($rep >= 1000 AND $rep < 3000){
		if($fac == 0){
			$patente= "Marinheiro";
		}
		else{
			$patente= "Novato";
		}
	}
	else if($rep >= 3000 AND $rep < 6000){
		if($fac == 0){
			$patente= "Sub-Oficial";
		}
		else{
			$patente= "Pirata";
		}
	}
	else if($rep >= 6000 AND $rep < 10000){
		if($fac == 0){
			$patente= "Oficial";
		}
		else{
			$patente= "Aventureiro";
		}
	}
	else if($rep >= 10000 AND $rep < 15000){
		if($fac == 0){
			$patente= "Capitão";
		}
		else{
			$patente= "Conquistador";
		}
	}
	else if($rep >= 15000 AND $rep < 25000){
		if($fac == 0){
			$patente= "Comodoro";
		}
		else{
			$patente= "Lord";
		}
	}
	else if($rep >= 25000 AND $rep < 50000){
		if($fac == 0){
			$patente= "Vice Almirante";
		}
		else{
			$patente= "Supernova";
		}
	}
	else if($rep >= 50000){
		if($fac == 0){
			$patente= "Almirante";
		}
		else{
			$patente= "Imperador";
		}
	}
	return "";
}
function classificacao_cbt($id1,$id2, $mar){
	$query="SELECT lvl FROM tb_personagens WHERE id='$id1' ORDER BY lvl DESC LIMIT 1";
	$result=mysql_query($query);
	$lvl1=mysql_fetch_array($result);
	
	$query="SELECT lvl FROM tb_personagens WHERE id='$id2' ORDER BY lvl DESC LIMIT 1";
	$result=mysql_query($query);
	$lvl2=mysql_fetch_array($result);
	if($mar==1 OR $mar==2 OR $mar==3 OR $mar==4){
		if(($lvl1["lvl"]-$lvl2["lvl"])<=2 AND ($lvl2["lvl"]-$lvl1["lvl"])<=2){
			return true;
		}
		else return false;
	}
	else if($mar==5){
		if(($lvl1["lvl"]-$lvl2["lvl"])<=5 AND ($lvl2["lvl"]-$lvl1["lvl"])<=5){
			return true;
		}
		else return false;
	}
	else{
		return true;
	}
}
function getPatente($id,&$pat=array()){
	$query="SELECT * FROM tb_usuarios WHERE id='$id'";
	$result=mysql_query($query);
	$trip=mysql_fetch_array($result);
	$rep = $trip["reputacao"];
	
	$query = "SELECT lvl FROM tb_personagens WHERE id='$id' ORDER BY lvl DESC LIMIT 1";
	$result=mysql_query($query);
	$lvl = mysql_fetch_array($result);
	$lvl = $lvl["lvl"];
	
	$pat["lvl"] = $lvl;
	
	if($rep<500)
		$patente = 0;
	else if($rep < 1700)
		$patente = 1;
	else if($rep < 3800)
		$patente = 2;
	else if($rep < 7000)
		$patente = 3;
	else if($rep < 11500)
		$patente = 4;
	else if($rep < 17500)
		$patente = 5;
	else if($rep < 23200)
		$patente = 6;
	else if($rep < 32800)
		$patente = 7;
	else if($rep < 44500)
		$patente = 8;
	else if($rep < 58500)
		$patente = 9;
	else if($rep < 75000)
		$patente = 10;
	else if($rep < 94200)
		$patente = 11;
	else if($rep < 116300)
		$patente = 12;
	else
		$patente = 13;
	
	$pat["patente"] = $patente;
	
	$query= "SELECT * FROM tb_rnk_patente WHERE patente_id='$patente'";
	$result=mysql_query($query);
	$resul = mysql_fetch_array($result);
	$pat["base"] = $resul["reputacao_base"];
	
	return $resul["nome_".$trip["faccao"]];
}
?>