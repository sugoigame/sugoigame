<?php
function obstaculo_para_tabuleiro($obstaculo) {
    return array(
        "obstaculo" => $obstaculo["id"],
        "cod" => "obstaculo-" . $obstaculo["id"],
        "tripulacao_id" => null,
        "hp" => $obstaculo["hp"],
        "hp_max" => OBSTACULOS_HP_INDIVIDUAL_MAX,
        "mp" => 0,
        "mp_max" => 1,
        "img" => "0000",
        "skin_r" => 0,
        "borda" => 0,
        "nome" => "Obstáculo",
        "atk" => 1,
        "def" => 1,
        "agl" => 1,
        "res" => 1,
        "pre" => 1,
        "dex" => 1,
        "per" => 1,
        "vit" => 1,
        "classe" => 0,
        "haki_esq" => 0,
        "haki_cri" => 0,
        "akuma" => null
    );
}