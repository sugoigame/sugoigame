<?php
namespace Regras\Combate\Formulas;

use Regras\Combate\Gatilhos;
use Regras\Combate\Personagem;
use Regras\Combate\Habilidade;

class Ataque
{
    public static function aplica_dano(Personagem $pers, Personagem $alvo, Habilidade $habilidade, int $personagens_atingidos)
    {
        $dano_hab = self::calc_dano_habilidade($habilidade);

        $retorno = [
            'chance_esquiva' => self::chance_esquiva($pers, $alvo),
            'dado_esquivou' => 0,
            'esquivou' => false,

            'chance_critico' => self::chance_crit($pers, $alvo),
            'dado_critou' => 0,
            'critou' => false,
            'critico' => 0,

            'chance_bloqueio' => self::chance_bloq($pers, $alvo),
            'bloqueou' => false,
            'dado_bloqueou' => 0,
            'bloqueio' => 0,

            'dano' => 0,
            'nova_hp' => $alvo->estado["hp"]
        ];

        if (! $dano_hab) {
            // dano zero nao precisa fazer nada
            return $retorno;
        }

        $retorno["dado_esquivou"] = rand(1, 1000) / 10;

        if ($retorno["dado_esquivou"] <= $retorno["chance_esquiva"]) {
            $retorno["esquivou"] = true;
            $pers->combate->gatilhos->dispara(Gatilhos::ESQUIVOU, $alvo, $pers);
        } else {
            $quadro_alvo = $pers->combate->tabuleiro->get_quadro_personagem($alvo);
            $distancia = $pers->combate->tabuleiro->get_quadro_personagem($pers)->get_ditancia($quadro_alvo);
            $reducao_distancia = 1.0 - max(0, $distancia - 1.5) * 0.03;

            $dano = ((self::get_atk_combate($pers) + $dano_hab) / $personagens_atingidos) * $reducao_distancia;
            $dano = max($dano_hab * 0.3, $dano - self::get_def_combate($alvo));

            $retorno["dado_critou"] = rand(1, 1000) / 10;
            if ($retorno["dado_critou"] <= $retorno["chance_critico"]) {
                $retorno["critou"] = true;
                $retorno["critico"] = self::dano_crit($pers, $alvo);
                $pers->combate->gatilhos->dispara(Gatilhos::CRITOU, $pers, $alvo);
            }

            $retorno["dado_bloqueou"] = rand(1, 1000) / 10;
            if ($retorno["dado_bloqueou"] <= $retorno["chance_bloqueio"]) {
                $retorno["bloqueou"] = true;
                $retorno["bloqueio"] = 0.9;
                $pers->combate->gatilhos->dispara(Gatilhos::BLOQUEOU, $alvo, $pers);
            }

            $dano_crit = $retorno["critico"] * $dano;

            $dano += $dano_crit;

            // dano bloqueado é calculado em cima do dano já critado
            $dano_bloq = $retorno["bloqueio"] * $dano;

            $retorno["dano"] = max(1, round($dano - $dano_bloq)) * self::get_mod_akuma($pers, $alvo);

            $alvo->estado["hp"] = max(0, $alvo->estado["hp"] - $dano);
            $retorno["nova_hp"] = $alvo->estado["hp"];

            $pers->combate->gatilhos->dispara(Gatilhos::ACERTOU, $pers, $alvo);

            if ($retorno["nova_hp"] <= 0) {
                $pers->combate->gatilhos->dispara(Gatilhos::MORREU, $alvo, $pers);
                $pers->combate->gatilhos->dispara(Gatilhos::MATOU, $pers, $alvo);
            }
        }

        return $retorno;
    }

    public static function get_mod_akuma(Personagem $pers, Personagem $alvo)
    {
        $pers_akuma = $pers->get_akuma();
        $alvo_akuma = $alvo->get_akuma();
        if (! $pers_akuma || ! $alvo_akuma) {
            return 1;
        }

        if ($pers->tripulacao->get_efeito("anula_efeito_akuma")
            || $alvo->tripulacao->get_efeito("anula_efeito_akuma")
        ) {
            return 1;
        }

        return $pers_akuma->get_vantagem_sobre($alvo_akuma);
    }

    public static function calc_dano_habilidade(Habilidade $habilidade)
    {
        $vontade = $habilidade->personagem->tripulacao->get_vontade();
        if ($vontade <= 40) {
            $dano = 500 + $vontade * 150;
        } else {
            $dano = 6500 * pow(1.03, $vontade - 40);
        }

        return $dano * $habilidade->estado["dano"];
    }

    public static function chance_esquiva(Personagem $pers, Personagem $alvo)
    {
        $pre = $pers->get_valor_atributo("pre");
        $agl = $alvo->get_valor_atributo("agl");

        $esquiva_haki = max(0, $alvo->get_valor_atributo("haki_esq") - $pers->get_valor_atributo("haki_esq"));

        $esquiva = \Utils\Math::min_max($agl - $pre, 0, 50) + $esquiva_haki;

        return round($esquiva);
    }

    public static function chance_crit(Personagem $pers, Personagem $alvo)
    {
        $dex = $pers->get_valor_atributo("dex");
        $con = $alvo->get_valor_atributo("con");

        $crit_haki = max(0, $pers->get_valor_atributo("haki_cri") - $alvo->get_valor_atributo("haki_cri"));

        $chance_crit = \Utils\Math::min_max($dex - $con, 0, 50) + $crit_haki;

        return round($chance_crit);
    }

    public static function dano_crit($pers, $alvo)
    {
        $dex = $pers->get_valor_atributo("dex");
        $con = $alvo->get_valor_atributo("con");

        return (float) \Utils\Math::min_max($dex - $con, 25, 90) / 100;
    }

    public static function chance_bloq($pers, $alvo)
    {
        $res = $alvo->get_valor_atributo("res");
        $per = $pers->get_valor_atributo("con");

        $bloq_haki = max(0, $alvo->get_valor_atributo("haki_cri") - $pers->get_valor_atributo("haki_cri"));

        $chance_bloq = \Utils\Math::min_max($res - $per, 0, 50) + $bloq_haki;

        return round($chance_bloq);
    }

    public static function get_atk_combate($pers)
    {
        return $pers->get_valor_atributo("atk") * 10;
    }
    public static function get_def_combate($pers)
    {
        return $pers->get_valor_atributo("def") * 10;
    }
}
