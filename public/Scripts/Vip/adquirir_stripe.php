<?php
require "../../Includes/conectdb.php";

$protector->need_conta();
$protector->must_be_gm();

$idPlano = $protector->get_number_or_exit('plano');
$currency = $protector->get_enum_or_exit('currency', ["brl", "usd", "eur"]);

$result = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ?", 'i', [$idPlano]);
if ($result->count() < 1) {
    header("Location: ../../?ses=vipComprar&msg=" . urlencode('Escolha um plano válido!') . "&");
    exit;
} else {
    $plano = $result->fetch();

    // Inserir log de compra e definir referencia para pagamento
    $connection->run("INSERT INTO tb_vip_compras (conta_id,plano_id,gateway) VALUE (?,?,?)", "iis", [
        $userDetails->conta['conta_id'],
        $plano['id'],
        "Stripe_$currency"
    ]);

    $reference = $connection->last_id();

    exit(header("Location: " . $plano["stripe_checkout_url_" . $currency] . "?client_reference_id=" . $reference . "&prefilled_email=" . $userDetails->conta["email"]));
}
