<?php
namespace Regras\Combate;

class CombateNpc extends Combate
{
    public function init()
    {
        $this->relatorio = new RelatorioNpc($this);
    }

    public function vez_de_quem()
    {

    }
    public function muda_vez()
    {

    }

    public function perdeu_vez()
    {
    }
    public function vale_quanta_recompensa()
    {
        return 0;
    }

    public function get_vontade(Tripulacao $tripulacao)
    {

    }
    public function incrementa_vontade(Tripulacao $tripulacao)
    {
    }

    public function get_movimentos_restantes(Tripulacao $tripulacao, $custo)
    {

    }

    public function consome_movimentos(Tripulacao $tripulacao, $custo)
    {

    }

    public function aplica_penalidade_perder_vez(Tripulacao $tripulacao)
    {
    }
}
