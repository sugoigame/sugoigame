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

    abstract public function get_vontade();

    abstract public function incrementa_vontade();

    abstract public function get_efeito($efeito);

    abstract public function salvar();

    abstract public function reduzir_espera_habilidades();

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

        $this->fim_turno();
    }

    public function fim_turno()
    {
        foreach ($this->personagens as $pers) {
            if ($pers->estado["hp"] > 0) {
                $pers->resolve_efeitos();
            }
        }

        // precisa reduzir depois de resolver tudo
        foreach ($this->personagens as $pers) {
            if ($pers->estado["hp"] > 0) {
                $pers->reduz_duracao_efeitos();
            }
        }

        $this->reduzir_espera_habilidades();
    }

    public function mover()
    {
        //todo
    }
    public function perder_vez()
    {
        $this->combate->relatorio->perder_vez($this);

        $this->aplica_penalidade_perder_vez();

        $this->fim_turno();
    }

    abstract public function aplica_penalidade_perder_vez();

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
