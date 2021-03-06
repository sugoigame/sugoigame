<?php
	$valida = "EquipeSugoiGame2012";
	require "../../Includes/conectdb.php";
	include "../../Includes/verifica_login_sem_pers.php";
	
	if($conect){
		
		$assunto = mysql_real_escape_string(strip_tags($_POST["assunto"]));
		$texto = mysql_real_escape_string(strip_tags($_POST["texto"]));
		$destino = mysql_real_escape_string(strip_tags($_POST["destino"]));
		
		if(!empty($_POST["assunto"]) AND !empty($_POST["texto"]) AND !empty($_POST["destino"])){
			if (!preg_match("/^[\w ]+$/", $_POST["destino"])) {
				mysql_close();
				echo "#Você informou algum caracter inválido";
				exit();
			}
			
			$query = "SELECT id FROM tb_personagens WHERE nome='$destino'";
			$result = mysql_query($query);
			$cont = mysql_num_rows($result);
			if($cont != 0){
				$id_destino = mysql_fetch_array($result);
				
				$hora = "às ";
				$hora .= date("H:i",time());
				$hora.= " do dia ";
				$hora .= date("d/m/Y",time());
				
				$query = "INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora)
				VALUES ('".$usuario["id"]."', '".$id_destino["id"]."', '$assunto', '$texto', '$hora')";
				mysql_query($query) or die("Erro ao enviar mensagem, tente novamente ou contate o suporte.");
				
				mysql_close();
				echo "Mensagem enviada com sucesso";
			}
			else {
				mysql_close();
				echo "#O destinatário informado não foi encontrado";
			}
		}
		else{
			mysql_close();
			echo "#Erro ao enviar mensagem, tente novamente ou contate o suporte.";
		}
	}
	
?>