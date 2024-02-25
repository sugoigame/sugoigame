<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$quant = $protector->post_number_or_exit("quant");

$protector->need_gold($quant);

$valor_dobrao = calc_cotacao_dobrao();

$berries = $quant * $valor_dobrao;

increment_value_int_variavel_global(VARIAVEL_BALANCO_VENDA_DOBRAO, -$quant);

$connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
    "ii", array($berries, $userDetails->tripulacao["id"]));

$userDetails->reduz_gold($quant, "gold_por_berries");

$connection->run("INSERT INTO tb_dobroes_leilao_log (vendedor_id, quant, preco_unitario) VALUES (?, ?, ?)",
    "iii", array($userDetails->tripulacao["id"], $quant, $valor_dobrao));

echo "Você adquiriu " . mascara_berries($berries) . " Berries!";
