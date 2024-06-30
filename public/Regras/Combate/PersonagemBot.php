<?php
namespace Regras\Combate;

class PersonagemBot extends Personagem
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

            if ($this->estado["classe"]) {
                $habilidades_pers = array_merge($habilidades_pers,
                    \Regras\Habilidades::habilidades_default_values($habilidades["classes"][$this->estado["classe"]]["habilidades"]));
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
            "x" => $this->estado["quadro_x"],
            "y" => $this->estado["quadro_y"]
        ];
    }

    public function get_akuma()
    {
        return null;
    }
    public function registrar_espera_habilidade(Habilidade $habilidade)
    {
        // habilidades de bot não tem recarga
    }
    public function mover(Quadro $destino)
    {
        $this->estado["quadro_x"] = $destino->x;
        $this->estado["quadro_y"] = $destino->y;
        $this->combate->connection->run("UPDATE tb_combate_personagens_bot SET quadro_x = ?, quadro_y = ? WHERE id = ?",
            "iii", [
                $this->estado["quadro_x"],
                $this->estado["quadro_y"],
                $this->estado["bot_id"],
            ]);
    }
}
