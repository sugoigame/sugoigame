<?php
namespace Regras\Combate;

class PersonagemNpc extends Personagem
{
    /**
     * @var Habilidade[]
     */
    protected $habilidades;

    protected function init()
    {
        // do nothing
    }

    public function get_habilidades()
    {
        if (! $this->habilidades) {
            $habilidades = \Utils\Data::load("habilidades");

            $habilidades_pers = \Regras\Habilidades::habilidades_default_values($habilidades["padrao"]);

            for ($classe = 1; $classe <= 3; $classe++) {
                $habilidades_pers = array_merge($habilidades_pers,
                    \Regras\Habilidades::habilidades_default_values($habilidades["classes"]["$classe"]["habilidades"]));
            }

            $this->habilidades = [];
            foreach ($habilidades_pers as $habilidade) {
                if ($habilidade["vontade"] <= $this->tripulacao->get_vontade()) {
                    $this->habilidades[$habilidade["cod"]] = new Habilidade($this->combate, $this, $habilidade);
                }
            }
        }

        return $this->habilidades;
    }

    public function get_posicao_tabuleiro()
    {
        return [
            "x" => "npc",
            "y" => "npc"
        ];
    }

    public function get_akuma()
    {
        return null;
    }
    public function registrar_espera_habilidade(Habilidade $habilidade)
    {
        // habilidade de npc nao tem espera
    }
    public function mover(Quadro $destino)
    {
        // npc não se move
    }
}
