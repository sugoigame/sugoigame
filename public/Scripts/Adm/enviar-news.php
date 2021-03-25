<?php

require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$assunto	= $protector->post_value_or_exit('nome');
$texto		= $protector->post_value_or_exit('texto');
$autor		= $protector->post_value_or_exit('autor');


if (empty($_POST["nome"]) || empty($_POST["texto"])) {
	$protector->exit_error('Você deve preencher todos os dados!');
}

$connection->run("INSERT INTO tb_noticias (`nome`, `texto`,`autor`, `criacao`) VALUES (?, ?, ? , NOW())", 'sss', [
	$assunto,
	nl2br($texto),
    $autor
]);
echo "Mensagem enviada com sucesso!";