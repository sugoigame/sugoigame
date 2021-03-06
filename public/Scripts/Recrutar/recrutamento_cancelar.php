<?php
require_once "../../Includes/conectdb.php";
require_once "../../Includes/verifica_login.php";
require_once "../../Includes/verifica_missao.php";
require_once "../../Includes/verifica_combate.php";

if(!$conect){
	echo ("#Você precisa estar logado!");
	exit();
}
if($incombate){
	echo("#Você está em combate!");
	exit();
}
if($inmissao AND !$inrecrute){
	echo ("#Você está ocupado em uma missão neste meomento.");
	exit();
}
if(!$inilha){
	echo ("#Você precisa estar em uma ilha!");
	exit();
}
$connection->run("UPDATE tb_usuarios SET recrutando = '0' WHERE id = ?", 'i', $usuario["id"]);

echo("Recrutamento cancelado");