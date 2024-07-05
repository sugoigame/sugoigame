<?php
namespace Regras\Combate;

use Regras\Habilidades;

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
        $this->combate->relatorio->registra_ataque($this->personagem, $this);

        $quadros = $this->combate->tabuleiro->get_quadros($quadros);

        $this->pre_atacar($quadros);

        $personagens_atingidos = 0;
        foreach ($quadros as $quadro) {
            if ($quadro->personagem) {
                $personagens_atingidos++;
            }
        }

        foreach ($quadros as $quadro) {
            if ($quadro->personagem) {
                $dano = Formulas\Ataque::aplica_dano($this->personagem, $quadro->personagem, $this, $personagens_atingidos);

                if (! $dano["esquivou"] && ! $dano["bloqueou"] && isset($this->estado["efeitos"]) && isset($this->estado["efeitos"]["acerto"])) {
                    $this->aplica_efeitos($this->estado["efeitos"]["acerto"], [$quadro]);
                }

                Formulas\Recompensa::atualiza_recompensa($this->personagem, $quadro->personagem, $dano);

                $this->combate->relatorio->registra_dano($this->personagem, $quadro, $dano);

                if ($dano["nova_hp"] <= 0) {
                    $quadro->personagem->tripulacao->incrementa_vontade();
                }
            } else {
                $this->combate->relatorio->registra_dano($this->personagem, $quadro, []);
            }
        }

        $this->pos_atacar($quadros);
    }


    /**
     * @param Quadro[]
     */
    public function pre_atacar($quadros)
    {
        if (isset($this->estado["efeitos"]) && isset($this->estado["efeitos"]["pre_ataque"])) {
            $this->aplica_efeitos($this->estado["efeitos"]["pre_ataque"], $quadros);
        }
    }

    /**
     * @param Quadro[]
     */
    public function pos_atacar($quadros)
    {
        if (isset($this->estado["efeitos"]) && isset($this->estado["efeitos"]["pos_ataque"])) {
            $this->aplica_efeitos($this->estado["efeitos"]["pos_ataque"], $quadros);
        }
    }

    /**
     * @param array
     * @param Quadro[]
     */
    public function aplica_efeitos($efeitos, $quadros)
    {
        foreach ($efeitos as $efeito) {
            $alvos = $this->resolver_alvos($efeito["tipo_alvo"], $efeito["filtro_alvo"], $quadros);
            foreach ($alvos as $alvo) {
                $alvo->estado["efeitos"][] = $efeito;
            }
        }
    }

    /**
     * @param TIPO_ALVO
     * @param FILTRO_ALVO
     * @param Quadro[]
     * @return Quadro[] | Personagem[]
     */
    public function resolver_alvos($tipo_alvo, $filtro_alvo, array $quadros)
    {
        if ($tipo_alvo == TIPO_ALVO_EFEITO_ATACANTE) {
            return $filtro_alvo == Habilidades::FILTRO_ALVO_ALIADO || $filtro_alvo == Habilidades::FILTRO_ALVO_TODOS ? [$this->personagem] : [];
        } elseif ($tipo_alvo == TIPO_ALVO_EFEITO_ALVO) {
            return array_filter(array_map(function ($quadro) use ($filtro_alvo) {
                if (! $quadro->personagem) {
                    return null;
                }

                if ($filtro_alvo == Habilidades::FILTRO_ALVO_ALIADO && $this->personagem->tripulacao->indice != $quadro->personagem->tripulacao->indice) {
                    // so pode causar dano em aliado
                    return null;
                }

                if ($filtro_alvo == Habilidades::FILTRO_ALVO_INIMIGO && $this->personagem->tripulacao->indice == $quadro->personagem->tripulacao->indice) {
                    // so pode causar dano em inimigo
                    return null;
                }

                return $quadro->personagem;
            }, $quadros), function ($pers) {
                return $pers;
            });
        } else {
            //todo implementar outros tipos
            return [];
        }
    }
}
