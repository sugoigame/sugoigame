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

    abstract protected function get_habilidades();

    /**
     * @return [x: int, y: int]
     */
    abstract protected function get_posicao_tabuleiro();

    /**
     * @return Akuma | null
     */
    abstract protected function get_akuma();

    /**
     * @param int
     * @param string
     */
    public function atacar($cod_habilidade, $quadros)
    {
        $habilidades = $this->get_habilidades();
        if (! isset($habilidades[$cod_habilidade])) {
            $this->combate->protector->exit_error("Habilidade inválida");
        }

        $habilidades[$cod_habilidade]->atacar($quadros);
    }

    public function resolve_efeitos()
    {

    }

    public function reduz_duracao_efeitos()
    {

    }

    public function mover()
    {
        //todo
    }

    /**
     * @return mixed
     */
    public function get_valor_atributo($atributo)
    {
        return 1;
    }
}
