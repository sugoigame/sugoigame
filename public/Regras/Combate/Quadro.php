<?php
namespace Regras\Combate;

class Quadro
{
    /**
     * @var int
     */
    public $x;

    /**
     * @var int
     */
    public $y;

    /**
     * @var Personagem | null
     */
    public $personagem;

    /**
     * @var Efeito[]
     */
    public $efeitos = [];

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }
}
