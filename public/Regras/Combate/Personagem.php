<?php
namespace Regras\Combate;

abstract class Personagem
{

    /**
     * @var Combate
     */
    public $combate;

    /**
     * @var array
     */
    public $estado;

    /**
     * @var Tripulacao
     */
    public $tripulacao;

    /**
     * @var Habilidade[]
     */
    protected $habilidades;

    /**
     * @param Combate
     * @param Tripulacao
     * @param array
     */
    public function __construct($combate, $tripulacao, $estado)
    {
        $this->combate = $combate;
        $this->tripulacao = $tripulacao;
        $this->estado = $estado;
        $this->init();
    }
    abstract protected function init();

    abstract protected function load_habilidades();

    /**
     * @return [x: int, y: int]
     */
    abstract protected function get_posicao_tabuleiro();

    /**
     * @param int
     * @param string
     */
    public function atacar($cod_habilidade, $quadros)
    {
        $this->load_habilidades();
        if (! isset($this->habilidades[$cod_habilidade])) {
            $this->combate->protector->exit_error("Habilidade inválida");
        }

        $this->habilidades[$cod_habilidade]->atacar($quadros);
    }

    public function mover()
    {
        //todo
    }
}
