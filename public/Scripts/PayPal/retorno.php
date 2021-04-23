<?php
require "../../Includes/conectdb.php";

require_once('../../Classes/PayPal.php');

$method = $_SERVER['REQUEST_METHOD'];
if ('POST' == $method) {
	$p = new PayPal();
	if ($p->verifyIPN()) {
		$paymentData = $p->ipn_data;

		$result = $connection->run("SELECT * FROM tb_vip_compras WHERE id = ? LIMIT 1", 'i', [
			$paymentData['custom']
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

			switch ($paymentData['payment_status']) {
				case 'Completed':
					if ($compra['status'] != 'Completed') {
						$connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ? LIMIT 1", 'ii', [
							$golds,
							$compra['conta_id']
						]);
					}
					break;
				/*case 'Reversed':
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

					break;*/
			}
			
			$connection->run("UPDATE `tb_vip_compras` SET `data_baixa` = NOW(), `status` = ?, `metodo` = ?, `referencia` = ? WHERE `id` = ? LIMIT 1", 'sssi', [
				$paymentData['payment_status'],
				$paymentData['payment_type'],
				$paymentData['txn_id'],
				$compra['id']
			]);
		}
	}

	header("HTTP/1.1 200 OK");
} else {
	header("HTTP/1.1 400 Bad Request");
}