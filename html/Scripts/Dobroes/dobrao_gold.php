<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$quant = $protector->post_number_or_exit("quant");

$protector->need_dobroes_criados($quant);

$connection->run("UPDATE tb_conta SET gold = gold + ? WHERE conta_id = ?",
    "ii", array($quant, $userDetails->conta["conta_id"]));

$userDetails->reduz_dobrao_criado($quant, "dobroes_gold");

echo "Você recebeu $quant Moedas de Ouro!";
