<?php
namespace Regras\Combate;

class PersonagemJogador extends Personagem
{

    /**
     * @var Habilidade[]
     */
    protected $habilidades;

    /**
     * @var Akuma
     */
    protected $akuma;

    protected function init()
    {
        // do nothing
    }

    public function atacar($cod_habilidade, $quadros)
    {
        parent::atacar($cod_habilidade, $quadros);

        $this->estado["xp"] += 40;

        if (($this->estado["profissao"] == PROFISSAO_COMBATENTE || $this->estado["profissao"] == PROFISSAO_MUSICO)
            && $this->estado["profissao_xp"] < $this->estado["profissao_xp_max"]) {
            $this->estado["profissao_xp"] += 1;
        }
    }

    public function registrar_espera_habilidade(Habilidade $habilidade)
    {
        if ($habilidade->estado["recarga"]) {
            $this->combate->connection->run("INSERT INTO tb_combate_skil_espera (id, cod, cod_skil, espera) VALUES (?,?,?,?)", "iiii", [
                $this->tripulacao->estado["id"],
                $this->estado["cod"],
                $habilidade->estado["cod"],
                $habilidade->estado["recarga"]
            ]);
        }
    }

    public function get_habilidades()
    {
        if (! $this->habilidades) {
            $habilidades = \Regras\Habilidades::get_todas_habilidades_pers($this->estado);

            $habilidades_recarga = $this->combate->connection->run("SELECT * FROM tb_combate_skil_espera WHERE id = ?",
                "i", [$this->tripulacao->estado["id"]]
            )->fetch_all_array();

            $this->habilidades = [];
            foreach ($habilidades as $habilidade) {
                if ($habilidade["vontade"] <= $this->tripulacao->get_vontade()) {
                    $filtro = ["cod_skil" => $habilidade["cod"]];
                    if (! $habilidade["recarga_universal"]) {
                        $filtro["cod"] = $this->estado["cod"];
                    }
                    if (! array_find($habilidades_recarga, $filtro)) {
                        $this->habilidades[$habilidade["cod"]] = new Habilidade($this->combate, $this, $habilidade);
                    }
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
        if (! $this->estado["akuma"]) {
            return null;
        }

        if (! $this->akuma) {
            $this->akuma = new Akuma($this->combate, \Utils\Data::find("akumas", ["cod_akuma" => $this->estado["akuma"]]));
        }
        return $this->akuma;
    }


    public function mover(Quadro $destino)
    {
        $this->estado["quadro_x"] = $destino->x;
        $this->estado["quadro_y"] = $destino->y;
        $this->combate->connection->run("UPDATE tb_combate_personagens SET quadro_x = ?, quadro_y = ? WHERE cod = ?",
            "iii", [
                $this->estado["quadro_x"],
                $this->estado["quadro_y"],
                $this->estado["cod"],
            ]);
    }
}
