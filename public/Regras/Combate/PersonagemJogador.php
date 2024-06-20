<?php
namespace Regras\Combate;

class PersonagemJogador extends Personagem
{
    protected function init()
    {
        // do nothing
    }

    protected function load_habilidades()
    {
        $habilidades = get_todas_habilidades_pers($this->estado["cod_pers"]);

        $this->habilidades = [];
        foreach ($habilidades as $habilidade) {
            $this->habilidades[$habilidade["cod"]] = new Habilidade($this->combate, $this, $habilidade);
        }
    }

    protected function get_posicao_tabuleiro()
    {
        return [
            "x" => $this->estado["quadro_x"],
            "y" => $this->estado["quadro_y"]
        ];
    }
}
