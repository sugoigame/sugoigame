<?php
namespace Regras\Combate;

abstract class Tripulacao
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
     * @var Personagem[]
     */
    public $personagens;

    /**
     * @var string
     */
    public $indice;

    /**
     * @param Combate
     */
    public function __construct($combate, $estado, $indice)
    {
        $this->combate = $combate;
        $this->estado = $estado;
        $this->indice = $indice;
        $this->init();
    }

    abstract protected function init();

    abstract protected function get_vontade();

    abstract protected function get_efeito($efeito);

    /**
     * @param int
     * @param int
     * @param string
     */
    public function atacar($cod_pers, $cod_habilidade, $quadros)
    {
        if (! isset($this->personagens[$cod_pers])) {
            $this->combate->protector->exit_error("Personagem invalido");
        }

        $this->personagens[$cod_pers]->atacar($cod_habilidade, $quadros);
    }

    public function mover()
    {
        //todo
    }

    public function perder_vez()
    {
        //todo
    }

    public function passar_vez()
    {
        //todo
    }

    public function desistir()
    {
        //todo
    }

    public function permitir_relatorio_avancado()
    {
        //todo
    }
}
