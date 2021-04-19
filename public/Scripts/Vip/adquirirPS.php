<?php
require "../../Includes/conectdb.php";

$protector->need_conta();

$idPlano    = base64_decode($_GET['plano']);

$result = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ?", 'i', $idPlano);
if ($result->count() < 1) {
    header("Location: ../../?ses=vipComprar&msg=" . urlencode('Escolha um plano válido!') . "&");
    exit;
} else {
    $plano =$result->fetch();

    require_once('../../Includes/PagSeguro/PagSeguroLibrary.php');
    $paymentRequest = new PagSeguroPaymentRequest();
    $paymentRequest->addItem($plano['id'], 'Sugoi Game - ' . $plano['nome'], 1, $plano['valor_brl']);
    $paymentRequest->setCurrency('BRL');

    $paymentRequest->setRedirectURL('https://' . $_SERVER['HTTP_HOST'] . '/?ses=vipComprar&complete');
    $paymentRequest->addParameter('notificationURL', 'https://' . $_SERVER['HTTP_HOST'] . '/Scripts/PagSeguro/retorno.php');

    // Inserir log de compra e definir referencia para pagamento
    $connection->run("INSERT INTO tb_vip_compras (conta_id,plano_id,gateway) VALUE (?,?,?)", "iis", [
        $userDetails->conta['conta_id'],
        $plano['id'],
        'PagSeguro'
    ]);
    $paymentRequest->setReference($connection->last_id());
    
    // Finalizar carrinho e direcionar ao pagamento
    $credentials = PagSeguroConfig::getAccountCredentials();
    $checkoutUrl = $paymentRequest->register($credentials);
    exit(header("Location: " . $checkoutUrl));
}