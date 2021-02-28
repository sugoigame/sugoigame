<?php
require "../../Includes/conectdb.php";

require_once('../../Includes/PagSeguro/PagSeguroLibrary.php');

$method = $_SERVER['REQUEST_METHOD'];
if('POST' == $method) {
    $credentials = PagSeguroConfig::getAccountCredentials();

    $notificationType = $_POST['notificationType'];
    $notificationCode = $_POST['notificationCode'];

    // $notificationType = 'transaction';
    // $notificationCode = '5FA9BB0173B473B41B9664D3DF8550AE76A0';
    if($notificationType === 'transaction') {
        $transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);

        $pagamentoItem      = $transaction->getReference();
        $pagamentoStatus    = $transaction->getStatus()->getTypeFromValue();
        $pagamentoMetodo    = $transaction->getPaymentMethod()->getType()->getTypeFromValue();
        $pagamentoReff      = $transaction->getCode();

        $result = $connection->run("SELECT * FROM tb_vip_compras WHERE id = ? LIMIT 1", 'i', [
            $pagamentoItem
        ]);
        if ($result->count() < 1)
            exit('Invalid buy!');
        else {
            $compra = $result->fetch();
            $plano  = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ? LIMIT 1", 'i', [
                $compra['plano_id']
            ])->fetch();

            $golds  = $plano['golds'];
            if ($plano['bonus'] > 0)
                $golds = $plano['golds'] * (($plano['bonus'] / 100) + 1);

            if ($compra['criacao'] <= '2021-02-19 23:59:59')
                $golds *= 1.5;

            switch ($pagamentoStatus) {
                case 'PAID':
                case 'AVAILABLE':
                    if ($compra['status'] != 'PAID')
                        $connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ? LIMIT 1", 'ii', [
                            $golds,
                            $compra['conta_id']
                        ]);
                    break;
                case 'REFUNDED':
                case 'IN_DISPUTE':
                    $conta = $connection->run("SELECT * FROM tb_conta WHERE conta_id = ? LIMIT 1", 'i', [
                        $compra['conta_id']
                    ])->fetch();

                    $connection->run("UPDATE tb_conta SET email = ?, senha = ?, fbid = ?, cookie = NULL, gold = gold - ? WHERE conta_id = ? LIMIT 1", 'sssii', [
                        'banned-' . $conta['email'],
                        'banned-' . $conta['senha'],
                        'banned-' . $conta['fbid'],
                        $golds,
                        $conta['conta_id']
                    ]);

                    break;
            }
            
            $connection->run("UPDATE `tb_vip_compras` SET `data_baixa` = NOW(), `status` = ?, `metodo` = ?, `referencia` = ? WHERE `id` = ? LIMIT 1", 'sssi', [$pagamentoStatus, $pagamentoMetodo, $pagamentoReff, $compra['id']]);
        }
    }
    echo 'OK';
} else {
    echo 'FAIL';
}