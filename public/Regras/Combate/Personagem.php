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

    abstract protected function registrar_espera_habilidade(Habilidade $habilidade);

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

        $this->registrar_espera_habilidade($habilidades[$cod_habilidade]);
    }

    public function resolve_efeitos()
    {
        foreach ($this->estado["efeitos"] as $efeito) {
            $atributo = $efeito["bonus"]["atr"];
            $classe = "\\Regras\\Combate\\Efeitos\\$atributo";
            if (class_exists($classe)) {
                $resolvedor = new $classe();
                $resolvedor->resolve($this, $efeito);
            }
        }
    }

    public function reduz_duracao_efeitos()
    {
        $novos_efeitos = [];
        foreach ($this->estado["efeitos"] as $efeito) {
            if ($efeito["duracao"] > 0) {
                $efeito["duracao"]--;
                $novos_efeitos[] = $efeito;
            }
        }
        $this->estado["efeitos"] = $novos_efeitos;
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
        $atributos_base = ["atk", "def", "agl", "res", "pre", "dex", "con", "vit"];

        if (in_array($atributo, $atributos_base)) {
            $efeitos = $this->get_soma_atributo_efeitos($atributo);
            $valor = $this->estado[$atributo] + $efeitos;

            $bonus_porcentagem = $this->get_soma_atributo_efeitos($atributo . "_porcentagem");
            $bonus = $valor * $bonus_porcentagem;

            return max(0, $valor + $bonus);
        } else {
            return $this->get_soma_atributo_efeitos($atributo);
        }
    }

    protected function get_soma_atributo_efeitos($atributo)
    {
        $valor = 0;

        foreach ($this->estado["efeitos"] as $efeito) {
            if ($efeito["bonus"]["atr"] == $atributo) {
                if (is_numeric($efeito["bonus"]["valor"])) {

                    $valor += $efeito["bonus"]["valor"];
                } else {
                    return $efeito["bonus"]["valor"];
                }
            }
        }
        return $valor;
    }
}
