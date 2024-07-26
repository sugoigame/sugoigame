<?php
namespace Regras;

class Builds
{
    public static function build_4_atributos(&$pers, $atr1, $atr2, $atr3, $atr4)
    {
        $total_para_distribuir = (($pers["lvl"] - 1) * PONTOS_POR_NIVEL) + PONTOS_INICIAIS;
        $resto = 0;
        for ($i = 1; $i <= 8; $i++) {
            $atri = nome_atributo_tabela($i);
            $pers[$atri] = 1;
            if ($atri === $atr1) {
                $quant = $total_para_distribuir * 0.4;
                $resto += $quant - floor($quant);
                $pers[$atri] += floor($quant);
            }
            if ($atri === $atr2) {
                $quant = $total_para_distribuir * 0.3;
                $resto += $quant - floor($quant);
                $pers[$atri] += floor($quant);
            }
            if ($atri === $atr3) {
                $quant = $total_para_distribuir * 0.2;
                $resto += $quant - floor($quant);
                $pers[$atri] += floor($quant);
            }
            if ($atri === $atr4) {
                $quant = $total_para_distribuir * 0.1;
                $resto += $quant - floor($quant);
                $pers[$atri] += floor($quant);
            }
        }
        $pers[$atr1] += $resto;
    }

    public static function build_simples(&$pers, $atr)
    {
        switch ($pers["classe"]) {
            case 2:
                self::build_4_atributos($pers, $atr, 'agl', 'def', 'atk');
                break;
            case 3:
                self::build_4_atributos($pers, $atr, 'pre', 'atk', 'dex');
                break;
            default:
                self::build_4_atributos($pers, $atr, 'atk', 'dex', 'pre');
                break;
        }
    }

}
