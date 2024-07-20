<?php
namespace Regras\Combate;

class IaControleNpc extends IaControle
{

    /**
     * @var Combate
     */
    public $combate;
    /**
     * @var Tripulacao
     */
    public $tripulacao;

    public function __construct(Combate $combate, Tripulacao $tripulacao)
    {
        $this->combate = $combate;
        $this->tripulacao = $tripulacao;
    }

    public function executa_acao()
    {
        $this->combate->minhaTripulacao = $this->tripulacao;

        $this->ataca_inimigo_escolhido();

        $this->combate->connection->run("UPDATE tb_combate_npc SET mira = ? WHERE id = ?",
            "ii", [$this->get_mira_adjacente(), $this->tripulacao->estado["id"]]);

        return "ataque";
    }

    public function ataca_inimigo_escolhido()
    {
        $alvo = $this->escolhe_alvo($this->tripulacao->estado["mira"]);

        $hablidades = array_filter($this->tripulacao->personagens["npc"]->get_habilidades(), function ($habilidade) {
            return $habilidade->estado["dano"] > 0;
        });
        $habilidade = $hablidades[array_rand($hablidades)];
        $posicao_alvo = $alvo->get_posicao_tabuleiro();
        $this->combate->atacar("npc", $habilidade->estado["cod"], $posicao_alvo["x"] . "_" . $posicao_alvo["y"]);
    }

    public function escolhe_alvo($mira, $count = 0)
    {
        if ($count > 5) {
            foreach ($this->combate->tripulacoes['1']->personagens as $pers) {
                if ($pers->estado["hp"] > 0) {
                    return $pers;
                }
            }
        }

        if ($mira < 0) {
            return $this->escolhe_alvo(0, ++$count);
        } elseif ($mira > 4) {
            return $this->escolhe_alvo(4, ++$count);
        }

        $personagens = $this->combate->tabuleiro->get_personagens_linha($mira);
        if (count($personagens)) {
            shuffle($personagens);
            return $personagens[0];
        } else {
            return $this->escolhe_alvo($this->get_mira_adjacente($mira), ++$count);
        }
    }

    public function get_mira_adjacente($mira = null)
    {
        if (! $mira) {
            $mira = $this->tripulacao->estado["mira"];
        }

        if ($mira >= 4) {
            return 3;
        } elseif ($mira <= 0) {
            return 1;
        } else {
            return rand(1, 2) == 1 ? $mira - 1 : $mira + 1;
        }
    }
}
