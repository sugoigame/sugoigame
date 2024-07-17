<?php
namespace Regras;

class Habilidades
{
    public const FILTRO_ALVO_ALIADO = "ALIADO";
    public const FILTRO_ALVO_INIMIGO = "INIMIGO";
    public const FILTRO_ALVO_TODOS = "TODOS";

    public static function get_todas_habilidades()
    {
        $habilidades = \Utils\Data::load("habilidades");
        $todas = array_merge($habilidades["padrao"], $habilidades["haki"]);

        foreach ($habilidades["classes"] as $classe) {
            $todas = array_merge($todas, $classe["habilidades"]);
        }
        foreach ($habilidades["profissoes"] as $profissao) {
            $todas = array_merge($todas, $profissao["habilidades"]);
        }
        foreach ($habilidades["akumas"] as $akuma) {
            $todas = array_merge($todas, $akuma["habilidades"]);
        }
        $todas = array_merge($todas, $habilidades["itens"]);

        return self::habilidades_default_values($todas);
    }


    public static function get_todas_habilidades_pers($pers)
    {
        global $connection;
        global $COD_HAOSHOKU_LVL;
        $habilidades_db = $connection->run(
            "SELECT * FROM tb_personagens_skil WHERE cod_pers = ?",
            "i", [$pers["cod"]]
        )->fetch_all_array();

        $habilidades = \Utils\Data::load("habilidades");

        $habilidades_pers = self::habilidades_default_values($habilidades["padrao"]);

        if ($pers["classe"]) {
            $habilidades_pers = array_merge($habilidades_pers, self::habilidades_default_values(
                array_filter($habilidades["classes"][$pers["classe"]]["habilidades"], function ($habilidade) use ($pers) {
                    return $habilidade["requisito_lvl"] <= $pers["lvl"];
                })));
        }
        if ($pers["haki_hdr"]) {
            $habilidades_pers[] = self::habilidade_default_values(array_find($habilidades["haki"], ["cod" => $COD_HAOSHOKU_LVL[$pers["haki_hdr"]]]));
        }
        if ($pers["akuma"]) {
            $habilidades_pers = array_merge($habilidades_pers, array_filter(self::get_todas_habilidades_akuma($pers["akuma"]), function ($habilidade) use ($pers) {
                return $habilidade["requisito_lvl"] <= $pers["lvl"];
            }));
        }
        if ($pers["profissao"]) {
            $habilidades_pers = array_merge($habilidades_pers, array_filter(self::get_todas_habilidades_profisao($pers["profissao"]), function ($habilidade) use ($pers) {
                return $habilidade["requisito_lvl"] <= $pers["profissao_lvl"];
            }));

            if ($pers["profissao"] == PROFISSAO_MEDICO) {
                $remedios = $connection->run(
                    "SELECT * FROM tb_usuario_itens WHERE id=? AND tipo_item=?",
                    "ii", [$pers["id"], TIPO_ITEM_REMEDIO])->fetch_all_array();

                foreach ($remedios as $remedio) {
                    $remedio_details = \Utils\Data::find("remedios", ["cod_remedio" => $remedio["cod_item"]]);
                    $habilidade = self::get_habilidade_by_cod($remedio_details["cod_habilidade"]);
                    $habilidade["quantidade"] = $remedio["quant"];
                    if ($habilidade && $habilidade["requisito_lvl"] <= $pers["profissao_lvl"]) {
                        $habilidades_pers[] = $habilidade;
                    }
                }
            }
        }

        foreach ($habilidades_pers as $key => $habilidade) {
            $habilidades_pers[$key] = array_merge($habilidade, array_find($habilidades_db, ["cod_skil" => $habilidade["cod"]]) ?: []);
        }

        usort($habilidades_pers, function ($a, $b) {
            return $a["vontade"] - $b["vontade"];
        });

        return $habilidades_pers;
    }

    public static function get_todas_habilidades_akuma($cod_akuma)
    {
        $habilidades = \Utils\Data::load("habilidades");
        return self::habilidades_default_values($habilidades["akumas"][$cod_akuma]["habilidades"]);
    }

    public static function get_todas_habilidades_profisao($cod_profissao)
    {
        $habilidades = \Utils\Data::load("habilidades");
        if ($cod_profissao && isset($habilidades["profissoes"][$cod_profissao]) && isset($habilidades["profissoes"][$cod_profissao]["habilidades"])) {
            return self::habilidades_default_values($habilidades["profissoes"][$cod_profissao]["habilidades"]);
        } else {
            return [];
        }
    }

    public static function get_habilidade_by_cod($cod)
    {
        return array_find(self::get_todas_habilidades(), ["cod" => $cod]);
    }

    public static function habilidades_default_values($habilidades)
    {
        foreach ($habilidades as $key => $habilidade) {
            $habilidades[$key] = self::habilidade_default_values($habilidade);
        }
        return $habilidades;
    }
    public static function habilidade_default_values($habilidade)
    {
        $habilidade["icone"] = $habilidade["icone"] ?: 1;
        $habilidade["animacao"] = $habilidade["animacao"] ?: "Atingir fisicamente";
        $habilidade["dano"] = isset($habilidade["dano"]) ? $habilidade["dano"] : 1;
        $habilidade["filtro_dano"] = $habilidade["filtro_dano"] ?: self::FILTRO_ALVO_INIMIGO;
        $habilidade["alcance"] = $habilidade["alcance"] ?: 1;
        $habilidade["area"] = $habilidade["area"] ?: 1;
        $habilidade["vontade"] = $habilidade["vontade"] ?: 1;
        $habilidade["recarga"] = $habilidade["recarga"] ?: 0;
        $habilidade["recarga_universal"] = $habilidade["recarga_universal"] ?: false;
        $habilidade["requisito_lvl"] = $habilidade["requisito_lvl"] ?: 1;


        if (isset($habilidade["efeitos"])) {
            if (isset($habilidade["efeitos"]["pre_ataque"])) {
                foreach ($habilidade["efeitos"]["pre_ataque"] as $index => $efeito) {
                    $habilidade["efeitos"]["pre_ataque"][$index] = self::efeito_default_values($habilidade, $efeito);
                }
            }
            if (isset($habilidade["efeitos"]["acerto"])) {
                foreach ($habilidade["efeitos"]["acerto"] as $index => $efeito) {
                    $habilidade["efeitos"]["acerto"][$index] = self::efeito_default_values($habilidade, $efeito, TIPO_ALVO_EFEITO_ALVO);
                }
            }
            if (isset($habilidade["efeitos"]["pos_ataque"])) {
                foreach ($habilidade["efeitos"]["pos_ataque"] as $index => $efeito) {
                    $habilidade["efeitos"]["pos_ataque"][$index] = self::efeito_default_values($habilidade, $efeito);
                }
            }
            if (isset($habilidade["efeitos"]["passivos"])) {
                foreach ($habilidade["efeitos"]["passivos"] as $index => $efeito) {
                    $habilidade["efeitos"]["passivos"][$index] = self::efeito_default_values($habilidade, $efeito);
                }
            }
        }
        return $habilidade;
    }

    public static function efeito_default_values($habilidade, $efeito, $tipo_alvo_padrao = TIPO_ALVO_EFEITO_ATACANTE)
    {
        $efeito["tipo"] = $efeito["tipo"] ?: TIPO_EFEITO_POSITIVO;
        $efeito["filtro_alvo"] = $efeito["filtro_alvo"] ?: self::FILTRO_ALVO_TODOS;
        $efeito["tipo_alvo"] = $efeito["tipo_alvo"] ?: $tipo_alvo_padrao;
        $efeito["quant_alvo"] = $efeito["quant_alvo"] ?: 1;
        $efeito["explicacao"] = $efeito["explicacao"] ?: $habilidade["explicacao"];

        $cores_efeitos = \Utils\Data::load("habilidades")["cores-efeitos"];
        $efeito["bonus"]["cor"] = $cores_efeitos[$efeito["bonus"]["atr"]];

        if (self::is_efeito_valor_habilidade($efeito["bonus"]["atr"])) {
            $efeito["bonus"]["valor"] = self::habilidade_default_values($efeito["bonus"]["valor"]);
        }
        return $efeito;
    }

    public static function is_efeito_valor_habilidade($atributo)
    {
        if ($atributo == ATRIBUTO_ATACANTE_ACERTO_CRITICO) {
            return true;
        }
        return false;
    }

    public static function is_usavel_batalha($habilidade)
    {
        return $habilidade["dano"] != 0 || isset($habilidade["efeitos"]["pre_ataque"]) || isset($habilidade["efeitos"]["acerto"]) || isset($habilidade["efeitos"]["pos_ataque"]);
    }

    public static function is_editavel($skill)
    {
        global $COD_HAOSHOKU_LVL;
        return $skill["cod_skil"] != 1 && ! in_array($skill["cod_skil"], $COD_HAOSHOKU_LVL);
    }

    public static function get_habilidade_aleatoria_nivel($lvl)
    {
        $habilidades_validas = array_filter(self::get_todas_habilidades(), function ($habilidade) use ($lvl) {
            return $habilidade['requisito_lvl'] <= $lvl;
        });
        return $habilidades_validas[rand(0, (sizeof($habilidades_validas) - 1))];
    }

}
