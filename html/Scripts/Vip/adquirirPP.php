<?php
require "../../Includes/conectdb.php";

$protector->need_conta();

$idPlano = base64_decode($_GET['plano']);

$result = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ?", 'i', [$idPlano]);
if ($result->count() < 1) {
	header("Location: ../../?ses=vipComprar&msg=" . urlencode('Escolha um plano válido!') . "&");
	exit;
} else {
	$plano =$result->fetch();

	require_once('../../Classes/PayPal.php');
	$p = new PayPal();
	// $p->useSandbox();

	// Inserir log de compra e definir referencia para pagamento
	$connection->run("INSERT INTO tb_vip_compras (conta_id,plano_id,gateway) VALUE (?,?,?)", "iis", [
		$userDetails->conta['conta_id'],
		$plano['id'],
		'PayPal'
	]);
	$paymentId = $connection->last_id();

	$p->addField('business',		'medeiros.dev@gmail.com');
	$p->addField('return',			'https://' . $_SERVER['HTTP_HOST'] . '/?ses=vipComprar&success');
	$p->addField('cancel_return',	'https://' . $_SERVER['HTTP_HOST'] . '/?ses=vipComprar&cancel');
	$p->addField('notify_url',		'https://' . $_SERVER['HTTP_HOST'] . '/Scripts/PayPal/retorno.php');
	$p->addField('item_name',		$plano['nome']);
	$p->addField('currency_code',	'BRL');
	$p->addField('amount',			$plano['valor']);
	$p->addField('custom',			$paymentId);

	$p->submitPayment();	# submit the fields to paypal
}