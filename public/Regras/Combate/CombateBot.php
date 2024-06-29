<?php
namespace Regras\Combate;

class CombateBot extends Combate
{
    public function init()
    {
        $this->relatorio = new RelatorioBot($this);
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
