<?php
namespace Regras\Combate;

class Habilidade
{

    /**
     * @var Combate
     */
    public $combate;

    /**
     * @var Personagem
     */
    public $personagem;
    /**
     * @var array
     */
    public $estado;

    /**
     * @param Combate
     * @param Personagem
     * @param array
     */
    public function __construct($combate, $personagem, $estado)
    {
        $this->combate = $combate;
        $this->personagem = $personagem;
        $this->estado = $estado;
    }

    /**
     * @param string
     */
    public function atacar($quadros)
    {
        $quadros = $this->combate->tabuleiro->get_quadros($quadros);

        $this->pre_atacar($quadros);

        foreach ($quadros as $quadro) {

        }

        $this->pos_atacar($quadros);
    }

    /**
     * @param Quadro[]
     */
    public function pre_atacar($quadros)
    {
    }

    /**
     * @param Quadro[]
     */
    public function pos_atacar($quadros)
    {
    }
}
