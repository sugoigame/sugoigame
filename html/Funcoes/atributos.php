<?php
function nome_atributo($att) {
    switch ($att) {
        case 1:
            return "Ataque";
        case 2:
            return "Defesa";
        case 3:
            return "Agilidade";
        case 4:
            return "Resistência";
        case 5:
            return "Precisão";
        case 6:
            return "Destreza";
        case 7:
            return "Percepção";
        case 8:
            return "Vitalidade";
        default:
            return "";
    }
}

function nome_atributo_img($att) {
    switch ($att) {
        case 1:
            return "atk";
        case 2:
            return "def";
        case 3:
            return "agl";
        case 4:
            return "res";
        case 5:
            return "pre";
        case 6:
            return "dex";
        case 7:
            return "per";
        case 8:
            return "vit";
        default:
            return "";
    }
}

function nome_atributo_tabela($att) {
    switch ($att) {
        case 1:
            return "atk";
        case 2:
            return "def";
        case 3:
            return "agl";
        case 4:
            return "res";
        case 5:
            return "pre";
        case 6:
            return "dex";
        case 7:
            return "con";
        case 8:
            return "vit";
        default:
            return "";
    }
}

function cod_atributo_tabela($att) {
    switch ($att) {
        case "atk":
            return 1;
        case "def":
            return 2;
        case "agl":
            return 3;
        case "res":
            return 4;
        case "pre":
            return 5;
        case "dex":
            return 6;
        case "con":
            return 7;
        case "vit":
            return 8;
        default:
            return "";
    }
}
