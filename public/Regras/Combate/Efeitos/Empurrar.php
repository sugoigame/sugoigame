<?php
namespace Regras\Combate\Efeitos;

use Regras\Combate\CombateNpc;
use Regras\Combate\Personagem;
use Regras\Combate\Quadro;
use Regras\Combate\Tabuleiro;

class Empurrar
{
    public function resolve(Personagem $pers, array $efeito)
    {
        $alvo = $pers->combate->relatorio->consequencias[0]["quadro"];
        $alvo_pers = $pers->combate->tabuleiro->get_quadro($alvo["x"] . "_" . $alvo["y"])->personagem;

        if (! $alvo_pers) {
            return;
        }

        $origem = $pers->get_posicao_tabuleiro();
        if ($origem["x"] == "npc") {
            $origem["x"] = 0;
            $origem["y"] = 0;
        }
        if ($alvo["x"] == "npc") {
            $alvo["x"] = 0;
            $alvo["y"] = 0;
        }

        $diferenca_x = $alvo["x"] - $origem["x"];
        $diferenca_y = $alvo["y"] - $origem["y"];

        $incremento_x = max(min($diferenca_x, 1), -1);
        $incremento_y = max(min($diferenca_y, 1), -1);

        $distancia_x = $incremento_x * $efeito["bonus"]["valor"];
        $distancia_y = $incremento_y * $efeito["bonus"]["valor"];

        $destino = [
            "x" => max(0, min($alvo["x"] + $distancia_x, array_key_exists("npc", $pers->combate->tripulacoes) ? floor(Tabuleiro::MAX_TABULEIRO_X / 2) : Tabuleiro::MAX_TABULEIRO_X)),
            "y" => max(0, min($alvo["y"] + $distancia_y, Tabuleiro::MAX_TABULEIRO_Y)),
        ];
        if ($pers->combate->tabuleiro->get_quadro($destino["x"] . "_" . $destino["y"])->personagem) {
            return;
        }

        $alvo_pers->mover(new Quadro($destino["x"], $destino["y"]));
    }
}
