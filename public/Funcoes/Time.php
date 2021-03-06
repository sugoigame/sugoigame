<?php
function atual_segundo()
{
	//pega a hora atual
	$ano = date("Y",time());
	$mes = date("m",time());
	$dia = date("d",time());
	$hora = date("H",time());
	$min = date("i",time());
	$sec = date("s",time());
	
	
	$tempo = mktime($hora,$min,$sec,$mes,$dia,$ano);
	
	//retorna a soma dos segundos
	return $tempo;
}

function transforma_tempo_min($tempo){
	//salva os segundos que nao completam um minuto inteiro em uma var
	$resto_segundos = $tempo % 60;
	
	//tira os segundos excedentes do tempo
	$tempo -= $resto_segundos;
	
	//divide o tempo que forma minutos inteiros em minutos
	$tempo /= 60;
	
	//salva os minutos que nao completam uma hora inteira em uma var
	$resto_minutos = $tempo % 60;
	
	//tira os minutos excedentes do tempo
	$tempo -= $resto_minutos;
	
	//divide o tempo que forma horas inteiras em horas
	$tempo /= 60;
	
	//salva as horas que nao completam uma hora inteira em uma var
	$resto_horas = $tempo % 24;
	
	//tira as horas excedentes do tempo
	$tempo -= $resto_horas;
	
	//divide o tempo que forma dias inteiros em dias
	$tempo /= 24;
	
	//cria s string que vai retornar o tempo convertido em horas:min:sec
	$string_tempo = "";
	
	//se o tempo contiver pelo menos 1 casas decimais nos dias adiciona o tempo a string
	if($tempo > 1)
	{
		//adiciona os dias a string
		$string_tempo .= $tempo ." dias ";
	}
	else if($tempo==1){
		//adiciona os dias a string
		$string_tempo .= $tempo ." dia ";
	}
	
	//se o tempo nao contiver 2 casas decimais nas horas ou mais, adiciona um zero antes da string
	if(strlen($resto_horas) == 1)
	{
		$string_tempo .= "0";
		//adiciona as horas a string
		$string_tempo .= $resto_horas;
	}
	//se o tempo nao contiver nenhuma casa deciman nas horas, adiciona 2 zeros na string
	else if(strlen($resto_horas) == 0)
	{
		$string_tempo .= "00";
	}
	else
	{
		//adiciona as horas a string
		$string_tempo .= $resto_horas;
	}
	
	//adiciona um separador
	$string_tempo .= ":";
	
	//se o $resto_minutos nao contiver 2 casas decimais, adiciona um zero na da string
	if(strlen($resto_minutos) == 1)
	{
		$string_tempo .= "0";
		//adiciona $resto_minutos na string
		$string_tempo .= $resto_minutos;
	}
	//se o $resto_minutos nao contiver nenhuma casa decima,, adiciona 2 zeros na string
	else if(strlen($resto_minutos) == 0)
	{
		$string_tempo .= "00";
	}
	else
	{
		//adiciona $resto_minutos na string
		$string_tempo .= $resto_minutos;
	}
	
	//adiciona um separador
	$string_tempo .= ":";
	
	//se o $resto_segundos nao contiver 2 casas decimais, adiciona um zero na da string
	if(strlen($resto_segundos) == 1)
	{
		$string_tempo .= "0";
		//adiciona $resto_segundos na string
		$string_tempo .= $resto_segundos;
	}
	//se o $resto_segundos nao contiver nenhuma casa decimal, adiciona 2 zeros na string
	else if(strlen($resto_segundos) == 0)
	{
		$string_tempo .= "00";
	}
	else
	{
		//adiciona $resto_segundos na string
		$string_tempo .= $resto_segundos;
	}
	
	return $string_tempo;
}

function transforma_tempo_seg($ano,$mes,$dia,$hora,$min,$sec)
{
	$tempo = mktime($hora,$min,$sec,$mes,$dia,$ano);
	
	//retorna a soma dos segundos
	return $tempo;
}

function ultimo_logon($tempo){
	if($tempo>=atual_segundo()-86400){
		return 1;
	}
	else if($tempo>=atual_segundo()-259200){
		return 2;
	}
	else{
		return 3;
	}
}
function ultimo_logon_texto($tempo){
	if($tempo>=atual_segundo()-86400){
		$texto= "Este jogador esteve ativo nas últimas 24 horas";
	}
	else if($tempo>=atual_segundo()-259200){
		$texto= "Este jogador está a mais de 24 horas sem executar ações no jogo";
	}
	else{
		$texto= "Este jogador está a mais de 3 dias sem executar ações no jogo";
	}
	
	return $texto;
}