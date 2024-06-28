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

    protected function get_vontade()
    {
        return $this->combate->userDetails->combate_pvp["vontade_" . $this->indice];
    }

    protected function get_efeito($efeito)
    {
        return $this->combate->userDetails->buffs->get_efeito_from_tripulacao($efeito, $this->estado["id"]);
    }

    protected function salvar()
    {

    }
}
