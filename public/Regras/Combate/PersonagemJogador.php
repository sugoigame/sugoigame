<?php
namespace Regras\Combate;

class PersonagemJogador extends Personagem
{

    /**
     * @var Habilidade[]
     */
    protected $habilidades;

    /**
     * @var Akuma
     */
    protected $akuma;

    protected function init()
    {
        // do nothing
    }

    protected function get_habilidades()
    {
        if (! $this->habilidades) {
            $habilidades = \Regras\Habilidades::get_todas_habilidades_pers($this->estado["cod_pers"]);

            $this->habilidades = [];
            foreach ($habilidades as $habilidade) {
                $this->habilidades[$habilidade["cod"]] = new Habilidade($this->combate, $this, $habilidade);
            }
        }

        return $this->habilidades;
    }

    protected function get_posicao_tabuleiro()
    {
        return [
            "x" => $this->estado["quadro_x"],
            "y" => $this->estado["quadro_y"]
        ];
    }

    protected function get_akuma()
    {
        if (! $this->estado["akuma"]) {
            return null;
        }

        if (! $this->akuma) {
            $this->akuma = new Akuma($this->combate, \Utils\Data::find("akumas", ["cod_akuma" => $this->estado["akuma"]]));
        }
        return $this->akuma;
    }
}
