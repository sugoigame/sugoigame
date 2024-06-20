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
     * @param Combate
     */
    public function __construct($combate, $estado)
    {
        $this->combate = $combate;
        $this->estado = $estado;
        $this->init();
    }

    abstract protected function init();

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
