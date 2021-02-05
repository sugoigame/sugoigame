<?php
function calc_ofensiva_mestre_treino_haki($pers, $ontem) {
    return $pers['haki_ultimo_dia_treino'] == $ontem ? $pers['haki_count_dias_treino'] : 0;
}

function calc_pts_mestre_treino_haki_today($pers, $ontem) {
    $ofensiva = calc_ofensiva_mestre_treino_haki($pers, $ontem);
    return 1000 + $ofensiva * 250;
}