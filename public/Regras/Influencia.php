<?php
namespace Regras;

class Influencia
{
    public static function get_requisitos($influencia)
    {
        if ($influencia < 10) {
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

    public static function get_relacoes()
    {
        global $connection;
        global $userDetails;

        return $connection
            ->run('SELECT * FROM tb_tripulacao_faccao WHERE tripulacao_id = ?', 'i', [$userDetails->tripulacao['id']])
            ->fetch_all_array();
    }

    public static function get_bonus_todas_faccoes($relacoes = null)
    {
        if ($relacoes == null) {
            $relacoes = self::get_relacoes();
        }

        $faccoes = \Utils\Data::load('mundo')['faccoes'];
        $faccao_base = $faccoes[0];
        $relacao_base = array_find($relacoes, ["faccao_id" => $faccao_base["cod"]]);
        $nivel_base = $relacao_base ? $relacao_base['nivel'] : 0;

        $bonus = [];
        foreach ($faccoes as $faccao) {
            if (isset($faccao['bonus'])) {
                $relacao = array_find($relacoes, ['faccao_id' => $faccao['cod']]);
                foreach ($faccao['bonus'] as $atr) {
                    if (! isset($bonus[$atr])) {
                        $bonus[$atr] = 0;
                    }
                    $bonus[$atr] += self::get_bonus_faccao(($relacao['nivel'] ?: 0) + $nivel_base);
                }
            }
        }
        return $bonus;
    }

    public static function get_reputacao_necessaria($nivel)
    {
        $necessario = 0;

        for ($lvl = 0; $lvl <= $nivel; $lvl++) {
            $necessario += ($lvl + 1) * 1500;
        }

        return $necessario;
    }

    public static function get_reputacao_produzida($producao)
    {
        $now = atual_segundo();
        $total = 0;
        foreach ($producao as $produzido) {
            $segundos = min($now - $produzido["inicio"], 24 * 60 * 60);
            $total += floor(($segundos / 60.0 / 60.0) * $produzido["quantidade"] * 100);
        }
        return $total;
    }

    public static function get_limite_confrontos($influencia)
    {
        return max(0, ($influencia - 10) * 2);
    }

    public static function generate_confronto($nivel)
    {
        mt_srand($nivel);
        $confronto = ["tripulacao" => [
            "tripulacao" => "Confronto $nivel",
            "faccao" => rand(0, 1),
            "bandeira" => "010113046758010128123542010115204020",
            "battle_back" => rand(1, 64)
        ], "personagens" => []];

        $quant_personagens = min(10, ceil($nivel / 5));
        $alcunhas = \Utils\Data::load("titulos");
        $dificultador = self::get_bonus_faccao($nivel) - 50;

        for ($x = 0; $x < $quant_personagens; $x++) {
            $lvl = min(50, ceil($nivel / 2));
            $hp = (HP_INICIAL + HP_POR_NIVEL * $lvl) * (($dificultador / 100) + 1);

            $pers = [
                "img" => rand(1, PERSONAGENS_MAX),
                "skin_r" => 0,
                "skin_c" => 0,
                "borda" => 0,
                "nome" => "Adversário $x",
                "fama_ameaca" => rand($nivel * 1000000, $nivel * 10000000) + ($x ? 0 : 10000000),
                "titulo" => $alcunhas[array_rand($alcunhas)]["cod_titulo"],
                "lvl" => $lvl,
                "hp" => $hp,
                "hp_max" => $hp,
                "classe" => rand(1, 4),
                "haki_esq" => 0,
                "haki_cri" => 0,
                "efeitos" => [[
                    "duracao" => 1000000,
                    "explicacao" => "Dano de habilidade aumentado em " . abrevia_numero_grande(round($dificultador)) . "%",
                    "bonus" => [
                        "atr" => "dano_habilidade",
                        "valor" => $dificultador / 100
                    ]
                ]]
            ];

            \Regras\Builds::build_simples($pers, nome_atributo_tabela(rand(1, 8)));

            for ($i = 1; $i <= 8; $i++) {
                $atri = nome_atributo_tabela($i);
                $pers[$atri] += round($pers[$atri] * ($dificultador / 100));
                if ($atri == "hp") {
                    $pers["hp_max"] += round($pers["hp_max"] * ($dificultador / 100));
                }
            }

            $confronto["personagens"][] = $pers;
        }

        mt_srand();
        return $confronto;
    }

    public static function generate_recompensas($nivel)
    {
        return [
            [
                'tipo' => 'berries',
                'quant' => $nivel * 1000,
            ],
            [
                'tipo' => 'xp',
                'quant' => 1000,
            ],
            [
                'tipo' => 'reputacao',
                'faccao' => 1,
                'quant' => 10000,
            ]
        ];
    }
}
