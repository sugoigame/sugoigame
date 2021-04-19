<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login.php";
	include "../../Includes/verifica_missao.php";
	include "../../Includes/verifica_combate.php";
	
	if(!$conect){
		mysql_close();
		echo ("#Você precisa estar logado!");
		exit();
	}
	if(!$incombate){
		mysql_close();
		echo("%oceano");
		exit();
	}
	if($inmissao){
		mysql_close();
        echo ("#Você está ocupado em uma missão neste momento.");
		exit();
    }
	if(!$innavio){
		mysql_close();
		echo ("#Você precisa de uma navio!");
		exit();
	}
	if(isset($usuario["pvp"])){
		
		$query="SELECT categoria FROM tb_torneio WHERE id='".$usuario["id"]."'";
		$result=mysql_query($query);
		if(mysql_num_rows($result)!=0){
			mysql_close();
			echo ("#Você não pode fugir de batalhas pelo torneio!");
			exit();
		}
		
		if($usuario["pvp"]["id_1"]==$usuario["id"]){
			if($usuario["pvp"]["vez"]==1){
				$vez=TRUE;
			}
			else{
				$vez=FALSE;
			}
		}
		else if($usuario["pvp"]["id_2"]==$usuario["id"]){
			if($usuario["pvp"]["vez"]==2){
				$vez=TRUE;
			}
			else{
				$vez=FALSE;
			}
		}
		if(!$vez){
			mysql_close();
			echo("#Não é a sua vez!");
			exit();
		}
		
		$query="SELECT * FROM tb_combate_personagens WHERE id='".$usuario["id"]."' AND hp>'0'";
		$result=mysql_query($query);
		if(mysql_num_rows($result)==0){
			$perdeu=TRUE;
			$venceu=FALSE;
		}
		else {
			$perdeu=FALSE;
			$venceu=TRUE;
		}
		
		$query="SELECT * FROM tb_combate_personagens WHERE id='".$usuario["pvp"]["id_ini"]."' AND hp>'0'";
		$result=mysql_query($query);
		if(mysql_num_rows($result)==0){
			$ini_perdeu=TRUE;
			$ini_venceu=FALSE;
		}
		else {
			$ini_perdeu=FALSE;
			$ini_venceu=TRUE;
		}
			
		if($perdeu OR $ini_perdeu){
			mysql_close();
			echo "#Não é a sua vez!";
			exit();
		}
	}
	if(FALSE){
		$query="SELECT * FROM tb_combate_personagens WHERE id='".$usuario["id"]."'";
		$result=mysql_query($query);
		for($x=0;$sql=mysql_fetch_array($result);$x++){
			$personagem_info[$x]=$sql;
		}
		
		for($x=0;$x<sizeof($personagem);$x++){
			if($personagem_info[$x]["hp"]>$personagem[$x]["hp"]){
				$nhp=$personagem[$x]["hp"];
			}
			else{
				$nhp=$personagem_info[$x]["hp"];
			}
			if($personagem_info[$x]["mp"]>$personagem[$x]["mp"]){
				$nmp=$personagem[$x]["mp"];
			}
			else{
				$nmp=$personagem_info[$x]["mp"];
			}
			$query="UPDATE tb_personagens SET hp='$nhp', mp='$nmp'
			WHERE id='".$usuario["id"]."' AND cod='".$personagem[$x]["cod"]."'";
			mysql_query($query) or die("nao foi possivel atualizar o personagem ".$personagem[$x]["cod"]);
		}
		
		$fuga=$usuario["fugas"]+1;
		$query="UPDATE tb_usuarios SET fugas='$fuga' WHERE id='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel contabilizar a fuga");
		
		$query="DELETE FROM tb_combate_npc WHERE id='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel remover combate");
		
		$query="DELETE FROM tb_combate WHERE id_1='".$usuario["id"]."' OR id_2='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel remover combate");
		
		$query="DELETE FROM tb_combate_personagens WHERE id='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel remover combate");
		
		if(isset($usuario["pvp"]["id_ini"])){
			$query="DELETE FROM tb_combate_personagens WHERE id='".$usuario["pvp"]["id_ini"]."'";
			mysql_query($query) or die("nao foi possivel remover combate");
		}
		
		$query="DELETE FROM tb_combate_skil_espera WHERE id='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel remover combate");
		
		if(isset($usuario["pvp"]["id_ini"])){
			$query="DELETE FROM tb_combate_skil_espera WHERE id='".$usuario["pvp"]["id_ini"]."'";
			mysql_query($query) or die("nao foi possivel remover combate");
		}
		
		$query="DELETE FROM tb_combate_buff WHERE id='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel remover combate");
		
		if(isset($usuario["pvp"]["id_ini"])){
			$query="DELETE FROM tb_combate_buff WHERE id='".$usuario["pvp"]["id_ini"]."'";
			mysql_query($query) or die("nao foi possivel remover combate");
		}
		
		mysql_close();
		echo ("%oceano");
		exit();
	}
	else{
		$query="SELECT * FROM tb_combate_skil_espera WHERE id='".$usuario["id"]."'";
		$result=mysql_query($query);
		for($x=0;$sql=mysql_fetch_array($result);$x++){
			$espera=$sql["espera"]-1;
			if($espera>0){
				$query2="UPDATE tb_combate_skil_espera SET espera='$espera' 
				WHERE id='".$usuario["id"]."' AND cod='".$sql["cod"]."' AND cod_skil='".$sql["cod_skil"]."' AND tipo='".$sql["tipo"]."'";
				mysql_query($query2) or die("nao foi possivel remover espera da skil ".$sql["cod_skil"]);
			}
			else{
				$query2="DELETE FROM tb_combate_skil_espera 
				WHERE id='".$usuario["id"]."' AND cod='".$sql["cod"]."' AND cod_skil='".$sql["cod_skil"]."' AND tipo='".$sql["tipo"]."'";
				mysql_query($query2) or die("nao foi possivel remover espera da skil ".$sql["cod_skil"]);
			}
		}
		
		
		
		//atualiza buff de personagens
		$query="SELECT * FROM tb_combate_buff WHERE id='".$usuario["id"]."'";
		$result=mysql_query($query);
		while($buff=mysql_fetch_array($result)){
			if($buff["espera"]>0){
				$espera=$buff["espera"]-1;
				$query2="UPDATE tb_combate_buff SET espera='$espera' 
				WHERE id='".$usuario["id"]."' AND cod='".$buff["cod"]."' AND atr='".$buff["atr"]."' 
				AND efeito='".$buff["efeito"]."' AND cod_buff='".$buff["cod_buff"]."' AND espera='".$buff["espera"]."'
				LIMIT 1";
				mysql_query($query2) or die("nao foi possivel reduzir duração do buff");
			}
			else{
				$query2="DELETE FROM tb_combate_buff 
				WHERE id='".$usuario["id"]."' AND cod='".$buff["cod"]."' AND atr='".$buff["atr"]."' 
				AND efeito='".$buff["efeito"]."' AND cod_buff='".$buff["cod_buff"]."' AND espera='".$buff["espera"]."'
				LIMIT 1";
				mysql_query($query2) or die("nao foi possivel remover o buff");
			}
		}
		
		//atualiza mp dos personagens
		$query="SELECT * FROM tb_combate_personagens WHERE id='".$usuario["id"]."'";
		$result=mysql_query($query);
		while($personagem_em_combate=mysql_fetch_array($result)){
			$nmp=$personagem_em_combate["mp"]+($personagem_em_combate["mp"]*0.01);
			if($nmp>$personagem_em_combate["mp_max"])$nmp=$personagem_em_combate["mp_max"];
			$query2="UPDATE tb_combate_personagens SET mp='$nmp' WHERE cod='".$personagem_em_combate["cod"]."'";
			mysql_query($query2) or die("Nao foi possivel atualizar buffs");
		}
		
		$relatorio="<b>".$personagem[0]["nome"]."</b> tentou fugir com sua tripulação mas falhou<br><br>";
	}
	if(isset($usuario["npc"]["img_npc"])){
		$query="SELECT * FROM tb_combate_personagens WHERE id='".$usuario["id"]."' AND hp<>0";
		$result=mysql_query($query);
		for($x=0;$sql=mysql_fetch_array($result);$x++){
			$personagem_alvo[$x]=$sql;
		}
		$cod_rand=rand(0,(sizeof($personagem_alvo)-1));
		
		$query="SELECT nome, lvl FROM tb_personagens WHERE id='".$usuario["id"]."' AND cod='".$personagem_alvo[$cod_rand]["cod"]."'";
		$result=mysql_query($query);
		$personagem_info=mysql_fetch_array($result);
		
		$query="SELECT * FROM tb_combate_buff WHERE cod='".$personagem_alvo[$cod_rand]["cod"]."'";
		$result=mysql_query($query);
		while($buff=mysql_fetch_array($result)){
			$personagem_alvo[$cod_rand][nome_atributo_tabela($buff["atr"])]+= $buff["efeito"];
			if($personagem_alvo[$cod_rand][nome_atributo_tabela($buff["atr"])]<0)
			$personagem_alvo[$cod_rand][nome_atributo_tabela($buff["atr"])]=0;
		}
		
		$esquiva=$personagem_alvo[$cod_rand]["pre"]-$usuario["npc"]["pre_npc"];
		if($esquiva>50){
			$esquiva=50;
		}
		else if($esquiva<0){
			$esquiva=0;
		}
		$esquiva+=$personagem_alvo[$cod_rand]["haki_esq"];
		
		if ((rand(1 * 10, 100 * 10) / 10) <= $esquiva){
			$relatorio.="<b>".$personagem_info["nome"]."</b> se <font style=\"background-color: #00FF00\">Esquivou</font><br><br>";
			$query="SELECT relatorio FROM tb_combate_npc WHERE id='".$usuario["id"]."'";
			$result=mysql_query($query);
			$relat=mysql_fetch_array($result);
			$relatorio.=$relat["relatorio"];
			$query="UPDATE tb_combate_npc SET relatorio='$relatorio' WHERE id='".$usuario["id"]."'";
			mysql_query($query) or die("nao foi possivel relatar turno");
		}
		else{
			$critico_c=$usuario["npc"]["dex_npc"]-$personagem_alvo[$cod_rand]["con"];
			if($critico_c>50){
				$critico_c=50;
			}
			else if($critico_c<0){
				$critico_c=0;
			}
			
			if ((rand(1 * 10, 100 * 10) / 10) <= $critico_c){
				$critico_d=$usuario["npc"]["dex_npc"]-$personagem_alvo[$cod_rand]["con"];
				$critico_d+=$personagem["haki_cri"];
				if($critico_d>90){
					$critico_d=90;
				}
				else if($critico_d<50){
					$critico_d=50;
				}
				$critico_d/=100;
				$critico_d+=1;
			}
			else{
				$critico_d=1;
			}
			
			$block_c=$personagem_alvo[$cod_rand]["res"]-$usuario["npc"]["pre_npc"];
			if($block_c>50){
				$block_c=50;
			}
			else if($block_c<0){
				$block_c=0;
			}
			$block_c+=$personagem_alvo[$cod_rand]["haki_blo"];
			if ((rand(1 * 10, 100 * 10) / 10) <= $block_c){
				$block_d=$personagem_alvo[$cod_rand]["res"]-$usuario["npc"]["pre_npc"];
				$block_d+=$alvo["haki_blo"];
				if($block_d>90){
					$block_d=90;
				}
				else if($block_d<50){
					$block_d=50;
				}
				$block_d/=100;
			}
			else{
				$block_d=1;
				$block=FALSE;
			}
			
			$tipo_skil_npc=1;
			if($tipo_skil_npc==1){
				$query="SELECT * FROM tb_skil_atk WHERE requisito_lvl<='".$personagem_info["lvl"]."'";
				$result=mysql_query($query);
				for($x=0;$sql=mysql_fetch_array($result);$x++){
					$skil_info_npc[$x]=$sql;
				}
				$skil_rand=rand(0,(sizeof($skil_info_npc)-1));
				
				$dano=$usuario["npc"]["atk_npc"]-$personagem_alvo[$cod_rand]["def"];
				if($dano<0)$dano=0;
				$dano=($dano+$skil_info_npc[$skil_rand]["dano"])*$block_d*$critico_d;
				$dano=(int)$dano;
				if($dano<1)$dano=1;
				$dano=(int)($dano*10);
				
				$nhp=$personagem_alvo[$cod_rand]["hp"]-$dano;
				if($nhp<0){
					$nhp=0;
				}
				$nmp=$usuario["npc"]["mp_npc"]-$skil_info_npc[$skil_rand]["consumo"];
				if($nmp<0){
					$nmp=0;
				}
				$nmp=(int)$nmp;
				$query="UPDATE tb_combate_npc SET mp_npc='$nmp' WHERE id='".$usuario["id"]."'";
				mysql_query($query) or die("Nao foi possivel atualizar energia do npc");
				
				$query="UPDATE tb_combate_personagens SET hp='$nhp' WHERE id='".$usuario["id"]."' AND cod='".$personagem_alvo[$cod_rand]["cod"]."'";
				mysql_query($query) or die("Nao foi possivel atualizar hp do alvo do npc");
				
				$relatorio.="<b>".$usuario["npc"]["nome_npc"]."</b> atacou<br>";
				$relatorio.="<b>".$personagem_info["nome"]."</b> ";
				if($block_d!=1){
					$relatorio.="<font style=\"background-color: #0000FF\">Bloqueou</font> e ";
				}
				$relatorio.="perdeu ".$dano." pontos de vida ";
				if($critico_d!=1){
					$relatorio.="<font style=\"background-color: #FF0000\">Ataque crítico</font>";
				}
				if($nhp==0){
					$relatorio.="<br><u>".$personagem_info["nome"]." foi derrotado</u>";
				}
				$relatorio.="<br><br>";
				$relatorio.="_";
				$query="SELECT relatorio FROM tb_combate_npc WHERE id='".$usuario["id"]."'";
				$result=mysql_query($query);
				$relat=mysql_fetch_array($result);
				for($x=0;$x<strlen($relat["relatorio"]);$x++){
					if(substr($relat["relatorio"],$x,1)!="_"){
						$relatorio.=substr($relat["relatorio"],$x,1);
					}
					else{
						$x=strlen($relat["relatorio"]);
					}
				}
				$relatorio.="_";
				$query="UPDATE tb_combate_npc SET relatorio='$relatorio' WHERE id='".$usuario["id"]."'";
				mysql_query($query) or die("nao foi possivel relatar turno npc");
				
				$move=5;
				$query="UPDATE tb_combate_npc SET move='$move' WHERE id='".$usuario["id"]."'";
				mysql_query($query) or die("nao doi possivel registrar movimento");
				echo "Você não conseguiu fugir";
			}
		}
	}
	else if(isset($usuario["pvp"])){
		//atualiza os movimentos
		$move=5;
		$query="UPDATE tb_combate SET ".$usuario["pvp"]["move_ini_"]."='$move' WHERE ".$usuario["pvp"]["id_ini_"]."='".$usuario["pvp"]["id_ini"]."'";
		mysql_query($query) or die("nao doi possivel registrar movimento");
		
		$relatorio="<b>".$personagem[0]["nome"]."</b> tentou fugir da batalha mas nao conseguiu<br>_<br>";
		
		if($usuario["id"]==$usuario["pvp"]["id_1"]){
			$vez=2;
		}
		else if($usuario["id"]==$usuario["pvp"]["id_2"]){
			$vez=1;
		}
		$vez_tempo=atual_segundo()+90;
		$query="UPDATE tb_combate SET relatorio='$relatorio', vez='$vez', vez_tempo='$vez_tempo' WHERE ".$usuario["pvp"]["id_user_"]."='".$usuario["id"]."'";
		mysql_query($query) or die("nao foi possivel relatar fim de turno");
		echo "Você não conseguiu fugir";
	}
	mysql_close();
	exit();
?>