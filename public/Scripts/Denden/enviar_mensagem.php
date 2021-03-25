<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$assunto	= $protector->post_value_or_exit('assunto');
$texto		= $protector->post_value_or_exit('texto');
$destino	= $protector->post_value_or_exit('destino');

if (empty($_POST["assunto"]) || empty($_POST["texto"]) || empty($_POST["destino"])) {
	$protector->exit_error('Você deve preencher todos os dados!');
}

$result = $connection->run("SELECT id FROM tb_personagens WHERE nome = ?", 's', $destino);
if ($result->count() <= 0) {
	$protector->exit_error('O destinatário informado não foi encontrado!');
}

$id_destino	= $result->fetch_array();
$hora		= "às " . date("H:i") . " do dia " . date("d/m/Y");
$connection->run("INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora) VALUES (?, ?, ?, ?, ?)", 'iisss', [
	$userDetails->tripulacao['id'],
	$id_destino['id'],
	$assunto,
	$texto,
	$hora
]);

echo "Mensagem enviada com sucesso!";
