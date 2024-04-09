<?php
// webhook.php
//
// Use this sample code to handle webhook events in your integration.
//
// 1) Paste this code into a new file (webhook.php)
//
// 2) Install dependencies
//   composer require stripe/stripe-php
//
// 3) Run the server on http://localhost:4242
//   php -S localhost:4242

require "../../Includes/conectdb.php";
require "../../Includes/stripe/init.php";

// The library needs to be configured with your account's secret key.
// Ensure the key is kept out of any version control system you might be using.
$stripe = new \Stripe\StripeClient(STRIPE_TOKEN_SECRET);

// This is your Stripe CLI webhook secret for testing your endpoint locally.
$endpoint_secret = STRIPE_CLI_WEBHOOK;

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
$event = null;

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    // Invalid payload
    http_response_code(400);
    exit($e->getMessage());
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    // Invalid signature
    http_response_code(400);
    exit($e->getMessage());
}
$pagamentoItem = $event->data->object->client_reference_id;
$pagamentoStatus = $event->data->object->payment_status;
$pagamentoMetodo = ""; //$event->data->object->payment_method_types[0];
$pagamentoReff = $event->data->object->payment_intent;

$result = $connection->run("SELECT * FROM tb_vip_compras WHERE id = ? LIMIT 1", 'i', [
    $pagamentoItem
]);

if (! $result->count()) {
    http_response_code(400);
    exit('Invalid buy!');
}

$compra = $result->fetch();
$plano = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ? LIMIT 1", 'i', [
    $compra['plano_id']
])->fetch();

$golds = $plano['golds'];
if ($plano['bonus'] > 0) {
    $golds = $plano['golds'] * (($plano['bonus'] / 100) + 1);
}

// Handle the event
switch ($event->type) {
    case 'checkout.session.async_payment_succeeded':
    case 'checkout.session.completed':
        if ($compra['status'] != 'paid') {
            $connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ? LIMIT 1", 'ii', [
                $golds,
                $compra['conta_id']
            ]);
        }
    case 'refund.created':
        $connection->run("UPDATE tb_conta SET gold = gold - ? WHERE conta_id = ? LIMIT 1", 'ii', [
            $golds,
            $compra['conta_id']
        ]);
    default:
        echo 'Received unknown event type ' . $event->type;
}

$connection->run("UPDATE `tb_vip_compras` SET `data_baixa` = NOW(), `status` = ?, `metodo` = ?, `referencia` = ? WHERE `id` = ? LIMIT 1", 'sssi', [
    $pagamentoStatus,
    $pagamentoMetodo,
    $pagamentoReff,
    $compra['id']
]);

http_response_code(200);
