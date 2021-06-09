<?php
require "../../Includes/conectdb.php";

require_once('../../Includes/PagSeguro/PagSeguroLibrary.php');

$method = $_SERVER['REQUEST_METHOD'];
if ('POST' == $method) {
    $credentials = PagSeguroConfig::getAccountCredentials();

    $notificationType = $_POST['notificationType'];
    $notificationCode = $_POST['notificationCode'];

    // $notificationType = 'transaction';
    // $notificationCode = '';

    if ($notificationType === 'transaction') {
        $transaction = PagSeguroNotificationService::checkTransaction($credentials, $notificationCode);

        $pagamentoItem      = $transaction->getReference();
        $pagamentoStatus    = $transaction->getStatus()->getTypeFromValue();
        $pagamentoMetodo    = $transaction->getPaymentMethod()->getType()->getTypeFromValue();
        $pagamentoReff      = $transaction->getCode();

        $result = $connection->run("SELECT * FROM tb_vip_compras WHERE id = ? LIMIT 1", 'i', [
            $pagamentoItem
        ]);
        if ($result->count() < 1) {
            exit('Invalid buy!');
        } else {
            $compra = $result->fetch();
            $plano  = $connection->run("SELECT * FROM tb_vip_planos WHERE id = ? LIMIT 1", 'i', [
                $compra['plano_id']
            ])->fetch();

            $golds  = $plano['golds'];
            if ($plano['bonus'] > 0) {
                $golds = $plano['golds'] * (($plano['bonus'] / 100) + 1);
            }

            $is_dbl = $connection->run("SELECT `id` FROM tb_vip_dobro WHERE ? BETWEEN data_inicio AND data_fim LIMIT 1", 's', [
                $compra['criacao']
            ])->count();
            $golds    = !$is_dbl ? $golds : ($golds * 2);

            switch ($pagamentoStatus) {
                case 'PAID':
                case 'AVAILABLE':
                    if ($compra['status'] != 'PAID' || $compra['status'] != 'AVAILABLE') {
                        $connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ? LIMIT 1", 'ii', [
                            $golds,
                            $compra['conta_id']
                        ]);
                    }
                    break;
                case 'REFUNDED':
                case 'IN_DISPUTE':
                    $conta = $connection->run("SELECT email,senha,fbid,conta_id FROM tb_conta WHERE conta_id = ? LIMIT 1", 'i', [
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
            
            $connection->run("UPDATE `tb_vip_compras` SET `data_baixa` = NOW(), `status` = ?, `metodo` = ?, `referencia` = ? WHERE `id` = ? LIMIT 1", 'sssi', [
                $pagamentoStatus,
                $pagamentoMetodo,
                $pagamentoReff,
                $compra['id']
            ]);
        }
    }
    echo 'OK';
} else {
    echo 'FAIL';
}