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
	
	$ano-=1;
	//se o ano for bisesto, multiplica os anos por 366 dias
	if($ano % 4 == 0)
	{
		$ano *= 366;
		$anobix = true;
	}
	// se nao for, multiplica por 365
	else
	{
		$ano *= 365;
		$anobix = false;
	}
	//dias do ano * 24 horas
	$ano *= 24;
	//horas do ano * 60 min
	$ano *= 60;
	//min do ano * 60 sec
	$ano *= 60;
	
	
	//se o mes tiver 31 dias, multiplica por 31
	if($mes == 1 OR $mes == 3 OR $mes == 5 OR $mes == 7 OR $mes == 8 OR $mes == 10 OR $mes == 12)
	{
		$mes *= 31;
	}
	//se for fevereiro e ano bisesto, multiplica mes por 29
	else if($mes == 2 && $anobix)
	{
		$mes *= 29;
	}
	//se for fevereiro e nao for ano bisesto, multiplica mes por 28
	else if($mes == 2 && !$anobix)
	{
		$mes *= 28;
	}
	//os outros meses multiplica por 30
	else
	{
		$mes *= 30;
	}
	//multiplica os dias do mes por 24 horas
	$mes *= 24;
	//multiplica as horas do mes por 60 min
	$mes *= 60;
	//multiplica os min do mes por 60 seg
	$mes *= 60;
	
	$dia-=1;
	//multiplica o dia por 24 horas
	$dia *= 24;
	//multiplica as horas do dia por 60 min
	$dia *= 60;
	//multiplica os min do dia por 60 sec
	$dia *= 60;
	
	//multiplica as horas por 60 min
	$hora *= 60;
	//multiplica os minutos da hora por 60 sec
	$hora *= 60;
	
	//multiplica os min por 60 sec
	$min *= 60;
	
	//depois de converter tudo pra segundo, soma os segundos totais do momento atual
	$tempo = $ano + $mes + $dia + $hora + $min + $sec;
	
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
	$ano-=1;
	//se o ano for bisesto, multiplica os anos por 366 dias
	if($ano % 4 == 0)
	{
		$ano *= 366;
		$anobix = true;
	}
	// se nao for, multiplica por 365
	else
	{
		$ano *= 365;
		$anobix = false;
	}
	//dias do ano * 24 horas
	$ano *= 24;
	//horas do ano * 60 min
	$ano *= 60;
	//min do ano * 60 sec
	$ano *= 60;
	
	$mes-=1;
	//se o mes tiver 31 dias, multiplica por 31
	if($mes == 1 OR $mes == 3 OR $mes == 5 OR $mes == 7 OR $mes == 8 OR $mes == 10 OR $mes == 12)
	{
		$mes *= 30;
	}
	//se for fevereiro e ano bisesto, multiplica mes por 29
	else if($mes == 2 && $anobix)
	{
		$mes *= 29;
	}
	//se for fevereiro e nao for ano bisesto, multiplica mes por 28
	else if($mes == 2 && !$anobix)
	{
		$mes *= 28;
	}
	//os outros meses multiplica por 30
	else
	{
		$mes *= 30;
	}
	//multiplica os dias do mes por 24 horas
	$mes *= 24;
	//multiplica as horas do mes por 60 min
	$mes *= 60;
	//multiplica os min do mes por 60 seg
	$mes *= 60;
	
	$dia-=1;
	//multiplica o dia por 24 horas
	$dia *= 24;
	//multiplica as horas do dia por 60 min
	$dia *= 60;
	//multiplica os min do dia por 60 sec
	$dia *= 60;
	
	//multiplica as horas por 60 min
	$hora *= 60;
	//multiplica os minutos da hora por 60 sec
	$hora *= 60;
	
	//multiplica os min por 60 sec
	$min *= 60;
	
	//depois de converter tudo pra segundo, soma os segundos totais do momento atual
	$tempo = $ano + $mes + $dia + $hora + $min + $sec;
	
	//retorna a soma dos segundos
	return $tempo;
}