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
     * @var Gatilhos
     */
    public $gatilhos;

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
        if ($userDetails->combate_bot) {
            $combate = new CombateBot($connection, $userDetails, $protector, $userDetails->combate_bot);
        } elseif ($userDetails->combate_pve) {
            $combate = new CombateNpc($connection, $userDetails, $protector, $userDetails->combate_pve);
        } elseif ($userDetails->combate_pvp) {
            $combate = new CombateJogador($connection, $userDetails, $protector, $userDetails->combate_pvp);
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
        $this->gatilhos = new Gatilhos($this);
        $this->estado = $estado;
        $this->init();

        $this->tabuleiro = new Tabuleiro($this);
    }

    abstract protected function init();

    /**
     * @return string
     */
    abstract public function vez_de_quem();

    abstract public function muda_vez();

    /**
     * @return bool
     */
    abstract public function perdeu_vez();

    /**
     * @return int
     */
    abstract public function vale_quanta_recompensa();


    abstract public function get_vontade(Tripulacao $tripulacao);

    abstract public function incrementa_vontade(Tripulacao $tripulacao);

    abstract public function get_movimentos_restantes(Tripulacao $tripulacao, $custo);

    abstract public function consome_movimentos(Tripulacao $tripulacao, $custo);

    abstract public function aplica_penalidade_perder_vez(Tripulacao $tripulacao);

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

        $this->fim_turno();
    }

    public function fim_turno()
    {
        $this->tabuleiro->resolve_efeitos();
        $this->tabuleiro->reduz_duracao_efeitos();

        $this->relatorio->salvar();

        foreach ($this->tripulacoes as $tripulacao) {
            $tripulacao->incrementa_vontade();
            $tripulacao->salvar();
        }

        $this->muda_vez();

        $this->apostas->checa_fim_apostas();
    }

    public function mover($cod_pers, $destino)
    {
        if ($this->minhaTripulacao->indice != $this->vez_de_quem()) {
            $this->protector->exit_error("Não é a sua vez");
        }

        $destino = $this->tabuleiro->get_quadro($destino);
        if ($destino->personagem) {
            $this->protector->exit_error("Quadro ocupado");
        }

        $this->minhaTripulacao->mover($cod_pers, $destino);

        $this->relatorio->salvar();
    }

    public function perder_vez()
    {
        if ($this->perdeu_vez()) {
            $this->tripulacoes[$this->vez_de_quem()]->perder_vez();

            $this->fim_turno();
        }
    }

    public function passar_vez()
    {
        if ($this->minhaTripulacao->indice != $this->vez_de_quem()) {
            $this->protector->exit_error("Não é a sua vez");
        }

        $this->relatorio->passar_vez($this->minhaTripulacao);

        $this->fim_turno();
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
