<?php
require "../../Includes/conectdb.php";

$protector->need_tripulacao();

$quant = $protector->post_number_or_exit("quant");

$protector->need_gold($quant);

$valor_dobrao = calc_cotacao_dobrao();

$berries = $quant * $valor_dobrao;

if ($valor_dobrao > 100000) {
    $connection->run("UPDATE tb_variavel_global SET valor_int = valor_int - ? WHERE variavel = ?",
        "is", array(round($quant * 0.8), VARIAVEL_BALANCO_VENDA_DOBRAO));
}

$connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
    "ii", array($berries, $userDetails->tripulacao["id"]));

$userDetails->reduz_gold($quant, "gold_por_berries");

$connection->run("INSERT INTO tb_dobroes_leilao_log (vendedor_id, quant, preco_unitario) VALUES (?, ?, ?)",
    "iii", array($userDetails->tripulacao["id"], $quant, $valor_dobrao));

echo "Você adquiriu " . mascara_berries($berries) . " Berries!";