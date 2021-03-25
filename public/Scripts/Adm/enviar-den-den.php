<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$assunto	= $protector->post_value_or_exit('assunto');
$texto		= $protector->post_value_or_exit('texto');


if (empty($_POST["assunto"]) || empty($_POST["texto"])) {
	$protector->exit_error('Você deve preencher todos os dados!');
}

$connection->run("INSERT INTO tb_mensagens_globais (`assunto`, `mensagem`, `data`) VALUES (?, ?, NOW())", 'ss', [
	$assunto,
	nl2br($texto)
]);
echo "Mensagem enviada com sucesso!";