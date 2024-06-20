<?php
namespace Regras\Combate;

class TripulacaoJogador extends Tripulacao
{
    protected function init()
    {
        $estados = get_pers_in_combate($this->estado["id"]);

        $this->personagens = [];

        foreach ($estados as $estado) {
            $this->personagens[$estado["cod_pers"]] = new PersonagemJogador($this->combate, $this, $estado);
        }
    }
}
