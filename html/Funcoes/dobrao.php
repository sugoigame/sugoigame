<?php
function calc_cotacao_dobrao() {
    global $connection;
    $negociacoes = $connection->run("SELECT * FROM tb_variavel_global WHERE variavel = ?",
        "s", array(VARIAVEL_BALANCO_VENDA_DOBRAO))->fetch_array();

    $transacoes = abs($negociacoes["valor_int"]);
    $mod = $negociacoes["valor_int"] >= 0 ? 1 : -1;

    return floor(max(100000, (200000 + ($mod * (($transacoes / 500) * 100000)))));
}