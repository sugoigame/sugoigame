<?php
function get_total_atributos($pers)
{
    $max = 1;
    for ($x = 1; $x <= 8; $x++) {
        $abr = nome_atributo_tabela($x);
        $total = round($pers[$abr]);
        if ($total > $max) {
            $max = $total;
        }
    }

    return $max + $pers["pts"];
}
function nome_atributo($att)
{
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

function nome_atributo_img($att)
{
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

function nome_atributo_tabela($att)
{
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

function descricao_atributo($att)
{
    switch ($att) {
        case 1:
            return "Cada ponto aumenta o dano causado pelo personagem em 10.";
        case 2:
            return "Cada ponto diminui o dano sofrido pelo personagem em 10.";
        case 3:
            return "Cada ponto aumenta sua chance de se esquivar do ataque inimigo em 1%.<br><b>obs: A porcentagem de chance máxima de se esquivar é de 50%;</b>";
        case 4:
            return "Cada ponto aumenta sua chance de bloquear o ataque inimgo em 1%. Um bloqueio bem sucedido reduz o dano sofrido em 90%.<br><b>obs:A porcentagem de chance máxima de bloqueio é de 50%.</b>";
        case 5:
            return "Cada ponto reduz a chance do inimigo se esquivar seu ataque em 1%.";
        case 6:
            return "Cada ponto aumenta sua chance de acertar um ataque crítico em 1% e o dano causado por ataques críticos em 1%.<br><b>obs: A porcentagem de chance máxima de acertar um ataque crítico é de 50%, e o dano máximo causado por ataque crítico é de 90%.</b>";
        case 7:
            return "Cada ponto reduz a chance do inimigo te acertar um ataque crítico em 1%, reduz o dano causado por ataques críticos em 1% e também reduz a chance do inimigo bloquear seu ataque em 1%.";
        case 8:
            return "Cada ponto aumenta seu HP em 50 pontos.<br><b>obs: O bonus de HP ganho por acréximo de vitalidade por meio de itens ou habilidades só é calculado durante combates.</b>";
        default:
            return "";
    }
}

function cod_atributo_tabela($att)
{
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
