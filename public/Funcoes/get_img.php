<?php
function getImg($pers, $tipo) {
    return get_img($pers, $tipo);
}

function get_img($pers, $tipo) {
    return sprintf("%04d", $pers["img"]) . "(" . $pers["skin_" . $tipo] . ")";
}

function big_pers_skin($img, $skin, $borda = 0, $class = "", $attr = "") {
    return '<div class="big-pers-skin">'
        . ($borda ? '<img class="big-pers-borda" src="Imagens/Personagens/Bordas/' . $borda . '.png" ' . $attr . '/>' : "")
        . '<img class="' . $class . '" src="Imagens/Personagens/Big/' . sprintf("%04d", $img) . '(' . $skin . ').jpg" ' . $attr . ' />'
        . '</div>';
}

function icon_pers_skin($img, $skin, $class = "", $attr = "") {
    return '<img class="' . $class . '" src="Imagens/Personagens/Icons/' . sprintf("%04d", $img) . '(' . $skin . ').jpg" ' . $attr . ' />';
}