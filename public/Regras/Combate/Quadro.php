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
     * @var array
     */
    public $estado = ["efeitos" => []];

    public function __construct($x, $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function get_ditancia(Quadro $quadro) : float
    {
        return sqrt(pow($this->x - $quadro->x, 2) + pow($this->y - $quadro->y, 2));
    }
}
