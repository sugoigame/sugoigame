<?php
namespace Regras\Combate;

class Relatorio
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

    public function registra_dano(Personagem $atacante, Personagem $defensor, $dano)
    {
        return null;
    }

    public function salvar()
    {

    }
}
