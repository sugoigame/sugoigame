<?php
namespace Regras;

class Influencia
{
    public static function get_requisitos($influencia)
    {
        if ($influencia < 100) {
            return [["faccao" => 1, "nivel" => $influencia]];
        }

        $requisitos = [];
        $faccoes = \Utils\Data::load('mundo')['faccoes'];
        foreach ($faccoes as $faccao) {
            if (! isset($faccao["evolui_outros"]) || ! $faccao["evolui_outros"]) {
                $requisitos[] = ["faccao" => $faccao["cod"], "nivel" => $influencia];
            }
        }
        return $requisitos;
    }
    public static function get_bonus_faccao($nivel)
    {
        return pow($nivel, 1.1);
    }

    public static function get_reputacao_necessaria($nivel)
    {
        $necessario = 0;

        for ($lvl = 0; $lvl <= $nivel; $lvl++) {
            $necessario += ($lvl + 1) * 15;
        }

        return $necessario;
    }

    public static function get_reputacao_produzida($producao)
    {
        $now = atual_segundo();
        $total = 0;
        foreach ($producao as $produzido) {
            $segundos = $now - $produzido["inicio"];
            $total += floor(($segundos / 60.0 / 60.0) * $produzido["quantidade"]);
        }
        return $total;
    }

    public static function get_limite_confrontos($influencia)
    {
        return $influencia * 5;
    }
}
