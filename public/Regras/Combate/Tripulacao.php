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
     * @var IaControle
     */
    public $controle;


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

    public function get_vontade()
    {
        return $this->combate->get_vontade($this);
    }

    public function incrementa_vontade()
    {
        return $this->combate->incrementa_vontade($this);
    }

    public function get_movimentos_restantes($custo = 0)
    {
        return $this->combate->get_movimentos_restantes($this, $custo);
    }

    public function consome_movimentos($custo)
    {
        return $this->combate->consome_movimentos($this, $custo);
    }

    public function aplica_penalidade_perder_vez()
    {
        return $this->combate->aplica_penalidade_perder_vez($this);
    }

    abstract public function get_efeito($efeito);

    abstract public function salvar();

    abstract public function reduzir_espera_habilidades();

    public function turno_automatico()
    {
        if (! $this->controle) {
            $this->combate->protector->exit_error("Essa tripulação não pode jogar automaticamente.");
        }

        return $this->controle->executa_acao();
    }

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

    public function mover($cod_pers, array $caminho)
    {
        if (! isset($this->personagens[$cod_pers])) {
            $this->combate->protector->exit_error("Personagem invalido");
        }

        $pers = $this->personagens[$cod_pers];
        $custo = $this->combate->tabuleiro->get_custo_movimento($pers, $caminho);
        if (! $this->get_movimentos_restantes($custo - 1)) {
            $this->combate->protector->exit_error("Você não pode se movimentar tanto");
        }

        $destino = $caminho[count($caminho) - 1];
        if (! $pers->get_valor_atributo("IMOBILIZACAO")) {
            $pers->mover($destino);
        }

        $this->consome_movimentos($custo);

        $this->combate->relatorio->registra_movimento($pers, $destino);
    }
    public function perder_vez()
    {
        $this->combate->relatorio->perder_vez($this);

        $this->aplica_penalidade_perder_vez();

        $this->fim_turno();
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
