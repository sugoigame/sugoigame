<?php
function calc_cotacao_dobrao()
{
    $balanco = get_value_int_variavel_global(VARIAVEL_BALANCO_VENDA_DOBRAO);
    $transacoes = abs($balanco);
    $mod = $balanco >= 0 ? 1 : -1;

    return floor(max(100000, (1000000 + ($mod * (($transacoes / 1) * 100000)))));
}
