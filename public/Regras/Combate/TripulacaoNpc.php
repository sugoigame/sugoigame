<?php
namespace Regras\Combate;

class TripulacaoNpc extends Tripulacao
{

    protected function init()
    {
    }

    public function get_vontade()
    {

    }
    public function incrementa_vontade()
    {
    }

    public function get_efeito($efeito)
    {

    }

    public function aplica_penalidade_perder_vez()
    {
        $this->fim_turno();
    }

    public function reduzir_espera_habilidades()
    {

    }
    public function salvar()
    {

    }
}
