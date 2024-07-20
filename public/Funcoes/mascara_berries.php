<?php
function mascara_numeros_grandes($rec) {
    return number_format($rec, 0, ',', '.');
}

function mascara_berries($rec) {
    return mascara_numeros_grandes($rec);
}

function abrevia_numero_grande($rec) {
    $tam = strlen($rec);
    if ($tam == 4) {
        $reco = round($rec / 1000) . " mil";
    } elseif ($tam == 5) {
        $reco = round($rec / 1000) . " mil";
    } elseif ($tam == 6) {
        $reco = round($rec / 1000) . " mil";
    } elseif (round($rec / 1000000) == 1) {
        return "1 milhão";
    } elseif ($tam == 7) {
        $reco = round($rec / 1000000) . " milhões";
    } elseif ($tam == 8) {
        $reco = round($rec / 1000000) . " milhões";
    } elseif ($tam == 9) {
        $reco = round($rec / 1000000) . " milhões";
    } elseif (round($rec / 1000000000) == 1) {
        return "1 bilhão";
    } elseif ($tam == 10) {
        $reco = round($rec / 1000000000) . " bilhões";
    } else {
        $reco = $rec;
    }
    return $reco;
}
