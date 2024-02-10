<?php
$valida = "EquipeSugoiGame2012";
require "../../Includes/conectdb.php";
include "../../Includes/verifica_login.php";

if (! $conect) {

	header("location:../../?msg=Você precisa estar logado.");
	exit();
}
if (! $inally) {

	header("location:../../?msg=Você não faz parte de uma aliança");
	exit();
}

if ($usuario["alianca"]["lvl"] < 5) {

	header("location:../../?msg=É necessário ter nível 5 para entrar em guerra!");
	exit();
}
$query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='" . $usuario["alianca"]["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() < 15) {

	header("location:../../?msg=Sua Aliança precisa de 15 membros para participar de uma guerra.");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE id='" . $usuario["id"] . "'";
$result = $connection->run($query);
$permicao = $result->fetch_array();

if (substr($usuario["alianca"][$permicao["autoridade"]], 4, 1) == 0) {

	header("location:../../?msg=Você não tem permissão para desafiar jogadores.");
	exit();
}

$forma = $protector->post_alphanumeric_or_exit("nome");
$tipo = $protector->post_number_or_exit("tipo");


$query = "SELECT * FROM tb_alianca WHERE nome='$forma'";
$result = $connection->run($query);
if ($result->count() == 0) {

	header("location:../../?msg=Aliança não encontrada.");
	exit();
}
$id_conv = $result->fetch_array();

if ($id_conv["lvl"] < 5) {

	header("location:../../?msg=É necessário que o oponente esteje no nível 5 para entrar em guerra!");
	exit();
}

if ($id_conv["cod_alianca"] == $usuario["alianca"]["cod_alianca"]) {

	header("location:../../?msg=Você não pode entrar em guerra com você.");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros WHERE cod_alianca='" . $id_conv["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() < 15) {

	header("location:../../?msg=A aliança oponente precisa de 15 membros para participar de uma guerra.");
	exit();
}

$query = "SELECT * FROM tb_alianca_membros INNER JOIN tb_usuarios ON tb_usuarios.id=tb_alianca_membros.id 
	WHERE tb_alianca_membros.autoridade='0' AND tb_alianca_membros.cod_alianca='" . $id_conv["cod_alianca"] . "'";
$result = $connection->run($query);
$lider = $result->fetch_array();

if ($lider["faccao"] == 0 and $usuario["faccao"] == 0) {

	header("location:../../?msg=Marinheiros não podem entrar em guerra com marinheiros.");
	exit();
}

$query = "SELECT * FROM tb_alianca_guerra_pedidos 
	WHERE convidado='" . $id_conv["cod_alianca"] . "' OR cod_alianca='" . $id_conv["cod_alianca"] . "'";
$result = $connection->run($query);
if ($result->count() != 0) {

	header("location:../../?msg=Essa aliança já tem um pedido de guerra pendente.");
	exit();
}

$query = "INSERT INTO tb_alianca_guerra_pedidos (cod_alianca, convidado, tipo)
	VALUES ('" . $usuario["alianca"]["cod_alianca"] . "', '" . $id_conv["cod_alianca"] . "', '$tipo')";
$connection->run($query) or die("Nao foi possivel convidar");

$query = "SELECT id FROM tb_alianca_membros 
	WHERE cod_alianca='" . $id_conv["cod_alianca"] . "' AND autoridade='0'";
$result = $connection->run($query);
$dono = $result->fetch_array();

$assunto = "Guerra!";
$texto = "Quero ver o quanto vocês são bons!\n
		Aceite minha declaração de guerra no link \"diplomacia\"\n
		Vocês serão massacrados!";
$hora = "às ";
$hora .= date("H:i", time());
$hora .= " do dia ";
$hora .= date("d/m/Y", time());

$query = "INSERT INTO tb_mensagens (remetente, destinatario, assunto, texto, hora)
	VALUES ('" . $usuario["id"] . "', '" . $dono["id"] . "', '$assunto', '$texto', '$hora')";
$connection->run($query) or die("Erro ao enviar mensagem, tente novamente ou contate o suporte.");


header("location:../../?ses=aliancaDiplomacia");
?>

