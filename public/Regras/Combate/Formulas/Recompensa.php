<?php
namespace Regras\Combate\Formulas;

use Regras\Combate\Personagem;

class Recompensa
{
    public static function atualiza_recompensa(Personagem $pers, Personagem $alvo, array $dano)
    {
        if (! $pers->combate->vale_quanta_recompensa()) {
            return;
        }

        if ($dano["esquivou"]) {
            self::aumenta_por_esquiva_bloqueio($pers, $alvo);
        } else {
            if ($dano["chance_esquiva"] - $alvo->get_valor_atributo("haki_esq") < 40) {
                self::aumenta_por_acerto_sem_agl($pers, $alvo);
            }
            if ($dano["bloqueou"]) {
                self::aumenta_por_esquiva_bloqueio($pers, $alvo);
            }
            if (! $dano["bloqueou"]
                && $dano["chance_bloqueio"] - $alvo->get_valor_atributo("haki_cri") < 40) {
                self::aumenta_por_acerto_sem_agl($pers, $alvo);
            }
            if ($dano["critou"]) {
                self::aumenta_por_critico($pers, $alvo);
            }
            if (! $dano["critou"]
                && $dano["chance_critico"] - $pers->get_valor_atributo("haki_cri") < 40) {
                self::aumenta_por_erro_critico($pers, $alvo);
            }

            self::aumenta_por_dano($dano, $pers, $alvo);
            self::aumenta_por_absorcao(
                ($pers->get_valor_atributo("atk") > $alvo->get_valor_atributo("def") ? $alvo->get_valor_atributo("def") : $pers->get_valor_atributo("atk")) * 10,
                $pers,
                $alvo
            );
        }
    }

    public static function aumenta_por_esquiva_bloqueio(Personagem $pers, Personagem $alvo)
    {
        self::aumenta($pers->estado["lvl"] * 1000, $alvo, $pers);
    }

    public static function aumenta_por_critico(Personagem $pers, Personagem $alvo)
    {
        self::aumenta($alvo->estado["lvl"] * 1000, $pers, $alvo);
    }

    public static function aumenta_por_acerto_sem_agl(Personagem $pers, Personagem $alvo)
    {
        self::aumenta($alvo->estado["lvl"] * 200, $pers, $alvo);
    }

    public static function aumenta_por_erro_critico(Personagem $pers, Personagem $alvo)
    {
        self::aumenta($pers->estado["lvl"] * 200, $alvo, $pers);
    }

    public static function aumenta_por_dano($dano, Personagem $pers, Personagem $alvo)
    {
        $fa_ganha = floor($dano / 1000) * 100000;
        if ($fa_ganha > 0) {
            self::aumenta($fa_ganha, $pers, $alvo);
        }
    }

    public static function aumenta_por_absorcao($absorcao, Personagem $pers, Personagem $alvo)
    {
        $fa_ganha = floor($absorcao / 1000) * 70000;
        if ($fa_ganha > 0) {
            self::aumenta($fa_ganha, $alvo, $pers);
        }
    }

    public static function aumenta($quantidade, Personagem $pers, Personagem $outro)
    {
        $max_fa = $pers->combate->vale_quanta_recompensa();

        if ($pers->estado["cod_capitao"] == $pers->estado["cod"]) {
            $max_fa *= 2;
        }

        if ($pers->estado["fa_ganha"] >= $max_fa || $pers->estado["id"] == $outro->estado["id"]) {
            return;
        }

        if ($pers->estado["cod_capitao"] == $pers->estado["cod"]) {
            $quantidade += round($quantidade * 0.2);
        }

        $bonus = $pers->tripulacao->get_efeito("aumento_ganho_fa") * $quantidade;
        $quantidade += $bonus;

        $pers->combate->connection->run(
            "INSERT INTO tb_wanted_log (vencedor_cod, perdedor_cod, fa_ganha, fa_perdida, vencedor_lvl, perdedor_lvl)
							 VALUES (?, ?, ?, ?, ?, ?)",
            "iiiiii", array($pers->estado["cod"], $outro->estado["cod"], $quantidade, 0, $pers->estado["lvl"], $outro->estado["lvl"])
        );
    }

}
