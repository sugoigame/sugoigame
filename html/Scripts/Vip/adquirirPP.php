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
    $p = new PayPal();             # initiate an instance of the class
    $p->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';   // testing paypal url

    // Inserir log de compra e definir referencia para pagamento
    $connection->run("INSERT INTO tb_vip_compras (conta_id,plano_id,gateway) VALUE (?,?,?)", "iis", [$userDetails->conta['conta_id'], $plano['id'], 'PayPal']);

    $p->add_field('business',       'medeiros.dev@gmail.com');
    $p->add_field('return',         'https://' . $_SERVER['HTTP_HOST'] . '/?ses=vipComprar&success');
    $p->add_field('cancel_return',  'https://' . $_SERVER['HTTP_HOST'] . '/?ses=vipComprar&cancel');
    $p->add_field('notify_url',     'https://' . $_SERVER['HTTP_HOST'] . '/Scripts/PayPal/retorno.php');
    $p->add_field('item_name',      $plano['nome']);
    $p->add_field('currency_code',  'BRL');
    $p->add_field('amount',         $plano['valor']);
    $p->add_field('reference',      $connection->last_id());

    $p->submit_paypal_post();   # submit the fields to paypal
    // $p->dump_fields();          # for debugging, output a table of all the fields
}