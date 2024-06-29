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
}
