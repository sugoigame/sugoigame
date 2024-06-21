<?php
namespace Regras\Combate;

abstract class Combate
{
    /**
     * @var \mywrap_con
     */
    public $connection;

    /**
     * @var \UserDetails
     */
    public $userDetails;

    /**
     * @var \Protector
     */
    public $protector;

    /**
     * @var Tripulacao
     */
    public $minhaTripulacao;

    /**
     * @var Tripulacao[]
     */
    public $tripulacoes;

    /**
     * @var Tabuleiro
     */
    public $tabuleiro;

    /**
     * @var Relatorio
     */
    public $relatorio;

    /**
     * @var Apostas
     */
    public $apostas;

    /**
     * @var array
     */
    public $estado;

    /**
     * Combate constructor.
     * @param \mywrap_con
     * @param \UserDetails
     * @param \Protector
     * @return Combate
     */
    public static function build($connection, $userDetails, $protector)
    {
        if ($userDetails->combate_pvp) {
            $combate = new CombateJogador($connection, $userDetails, $protector, $userDetails->combate_pvp);
        } elseif ($userDetails->combate_pve) {
            $combate = new CombateNpc($connection, $userDetails, $protector, $userDetails->combate_pve);
        } elseif ($userDetails->combate_bot) {
            $combate = new CombateBot($connection, $userDetails, $protector, $userDetails->combate_bot);
        } else {
            $protector->exit_error("Não está em combate");
        }

        return $combate;
    }

    /**
     * Combate constructor.
     * @param \mywrap_con
     * @param \UserDetails
     * @param \Protector
     * @param array
     */
    private function __construct($connection, $userDetails, $protector, $estado)
    {
        $this->connection = $connection;
        $this->userDetails = $userDetails;
        $this->protector = $protector;
        $this->apostas = new Apostas($this);
        $this->relatorio = new Relatorio($this);
        $this->estado = $estado;
        $this->init();

        $this->tabuleiro = new Tabuleiro($this);
    }

    abstract protected function init();

    /**
     * @return string
     */
    abstract public function vez_de_quem();

    /**
     * @return int
     */
    abstract public function vale_quanta_recompensa();

    /**
     * @param int
     * @param int
     * @param string
     */
    public function atacar($cod_pers, $cod_habilidade, $quadros)
    {
        if ($this->minhaTripulacao->indice != $this->vez_de_quem()) {
            $this->protector->exit_error("Não é a sua vez");
        }

        $this->minhaTripulacao->atacar($cod_pers, $cod_habilidade, $quadros);


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

    public function apostar()
    {
        //todo
    }

    public function encerrar()
    {
        //todo
    }
}
