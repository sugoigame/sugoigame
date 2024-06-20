<?php
namespace Regras\Combate;

class Apostas
{
    /**
     * @var Combate
     */
    protected $combate;

    /**
     * @param Combate
     */
    public function __construct($combate)
    {
        $this->combate = $combate;
    }

    public function apostar()
    {
        //todo
    }

}
