<?php
namespace Regras\Combate\Efeitos;

use Regras\Combate\Personagem;
use Regras\Combate\Quadro;
use \Regras\Combate\Tabuleiro;

class AtravessaAlvo
{
    public function resolve(Personagem $pers, array $efeito)
    {
        $max_tabuleiro_x = array_key_exists("npc", $pers->combate->tripulacoes) ? floor(Tabuleiro::MAX_TABULEIRO_X / 2) : Tabuleiro::MAX_TABULEIRO_X;

        $alvo = $pers->combate->relatorio->consequencias[0]["quadro"];
        $origem = $pers->get_posicao_tabuleiro();

        if ($alvo["x"] == "npc" || $origem["x"] == "npc") {
            return;
        }

        $diferenca_x = $alvo["x"] - $origem["x"];
        $diferenca_y = $alvo["y"] - $origem["y"];

        $incremento_x = max(min($diferenca_x, 1), -1);
        $incremento_y = max(min($diferenca_y, 1), -1);

        $distancia_x = $incremento_x * $efeito["bonus"]["valor"];
        $distancia_y = $incremento_y * $efeito["bonus"]["valor"];

        $destino = [
            "x" => $alvo["x"] + $distancia_x,
            "y" => $alvo["y"] + $distancia_y,
        ];
        while ($pers->combate->tabuleiro->get_quadro($destino["x"] . "_" . $destino["y"])->personagem) {
            $destino["x"] += $incremento_x;
            $destino["y"] += $incremento_y;
        }

        if ($destino["x"] > $max_tabuleiro_x || $destino["y"] > Tabuleiro::MAX_TABULEIRO_Y
            || $destino["x"] < 0 || $destino["y"] < 0) {

            $destino = [
                "x" => $alvo["x"] - $distancia_x,
                "y" => $alvo["y"] - $distancia_y,
            ];
            while ($pers->combate->tabuleiro->get_quadro($destino["x"] . "_" . $destino["y"])->personagem) {
                $destino["x"] -= $incremento_x;
                $destino["y"] -= $incremento_y;
            }
        }

        if ($destino["x"] > $max_tabuleiro_x || $destino["y"] > Tabuleiro::MAX_TABULEIRO_Y
            || $destino["x"] < 0 || $destino["y"] < 0) {
            return;
        }

        $pers->mover(new Quadro($destino["x"], $destino["y"]));
    }
}
