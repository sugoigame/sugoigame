<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$quant = $protector->post_number_or_exit("quant");

$valor_dobrao = calc_cotacao_dobrao();

$preco = $quant * $valor_dobrao;

$protector->need_berries($preco);

$connection->run("UPDATE tb_variavel_global SET valor_int = valor_int + ? WHERE variavel = ?",
    "is", array(round($quant * 1.2), VARIAVEL_BALANCO_VENDA_DOBRAO));

$connection->run("UPDATE tb_conta SET dobroes = dobroes + ? WHERE conta_id = ?",
    "ii", array($quant, $userDetails->conta["conta_id"]));

$connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
    "ii", array($preco, $userDetails->tripulacao["id"]));

$connection->run("INSERT INTO tb_dobroes_leilao_log (comprador_id, quant, preco_unitario) VALUES (?, ?, ?)",
    "iii", array($userDetails->tripulacao["id"], $quant, $valor_dobrao));


echo "Você adquiriu $quant Dobrões de Ouro!";