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
		echo ("#Você não faz parte de uma alianca");
		exit();
	}
	$query="SELECT * FROM tb_alianca_membros WHERE id='".$usuario["id"]."'";
	$result=mysql_query($query);
	$permicao=mysql_fetch_array($result);
	
	if(substr($usuario["alianca"][$permicao["autoridade"]],6,1)==0){
		mysql_close();
		echo ("#Você não tem permissão para iniciar missões.");
		exit();
	}
	if(!isset($_GET["quant"])){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
	$quant=mysql_real_escape_string($_GET["quant"]);
	
	if(!preg_match("/^[\d]+$/", $quant)){
		mysql_close();
		echo ("#Você informou algum caracter inválido.");
		exit();
	}
    switch ($quant) {
        case 1:
            $quant=50;
            break;
        case 2:
            $quant=100;
            if($usuario["alianca"]["lvl"]<3){
                mysql_close();
                echo ("#Sua aliança não cumpre os requisitos necessários para iniciar a missão.");
                exit();
            }
            break;
        case 3:
            $quant=200;
            if($usuario["alianca"]["lvl"]<5){
                mysql_close();
                echo ("#Sua aliança não cumpre os requisitos necessários para iniciar a missão.");
                exit();
            }
            break;
        default:
            $quant=50;
            break;
    }
	
	$query="SELECT * FROM tb_alianca_missoes WHERE cod_alianca='".$usuario["alianca"]["cod_alianca"]."'";
	$result=mysql_query($query);
	if(mysql_num_rows($result)!=0){
		mysql_close();
		echo ("#Você ja esta em missao.");
		exit();
	}
	
	$query="INSERT INTO tb_alianca_missoes (cod_alianca, fim)
	VALUES ('".$usuario["alianca"]["cod_alianca"]."', '$quant')";
	mysql_query($query) or die("Nao foi possivel assinar");
	
	mysql_close();
	echo("Missão iniciada!");
?>