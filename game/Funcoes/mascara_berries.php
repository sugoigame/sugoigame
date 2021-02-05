<?php
function mascara_numeros_grandes($rec) {
    $tam = strlen($rec);
    if ($tam == 4) {
        $reco = mascara_string("#.###", $rec);
    } else if ($tam == 5) {
        $reco = mascara_string("##.###", $rec);
    } else if ($tam == 6) {
        $reco = mascara_string("###.###", $rec);
    } else if ($tam == 7) {
        $reco = mascara_string("#.###.###", $rec);
    } else if ($tam == 8) {
        $reco = mascara_string("##.###.###", $rec);
    } else if ($tam == 9) {
        $reco = mascara_string("###.###.###", $rec);
    } else if ($tam == 10) {
        $reco = mascara_string("#.###.###.###", $rec);
    } else {
        $reco = $rec;
    }
    return $reco;
}

function mascara_berries($rec) {
    return mascara_numeros_grandes($rec);
}

function abrevia_numero_grande($rec) {
    $tam = strlen($rec);
    if ($tam == 4) {
        $reco = round($rec / 1000) . " mil";
    } else if ($tam == 5) {
        $reco = round($rec / 1000) . " mil";
    } else if ($tam == 6) {
        $reco = round($rec / 1000) . " mil";
    } else if (round($rec / 1000000) == 1) {
        return "1 milhão";
    } else if ($tam == 7) {
        $reco = round($rec / 1000000) . " milhões";
    } else if ($tam == 8) {
        $reco = round($rec / 1000000) . " milhões";
    } else if ($tam == 9) {
        $reco = round($rec / 1000000) . " milhões";
    } else if (round($rec / 1000000000) == 1) {
        return "1 bilhão";
    } else if ($tam == 10) {
        $reco = round($rec / 1000000000) . " bilhões";
    } else {
        $reco = $rec;
    }
    return $reco;
}