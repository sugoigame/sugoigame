<?php
namespace Regras\Combate;

class CombateNpc extends Combate
{
    public function init()
    {
        $this->tripulacoes = [
            "1" => new TripulacaoJogador($this, $this->userDetails->tripulacao, "1"),
            "npc" => new TripulacaoNpc($this, $this->userDetails->combate_pve, "npc")
        ];

        $this->minhaTripulacao = $this->tripulacoes["1"];

        $this->relatorio = new RelatorioNpc($this);
    }

    public function vez_de_quem()
    {
        return $this->estado["vez"];
    }

    public function muda_vez()
    {
        $vez = $this->estado["vez"] == "1" ? "npc" : "1";
        $this->connection->run("UPDATE tb_combate_npc SET vez = ?, `move` = 5 WHERE id = ?",
            "si", [$vez, $this->estado["id"]]);
    }

    public function perdeu_vez()
    {
        return false;
    }

    public function vale_quanta_recompensa()
    {
        return 0;
    }

    public function get_vontade(Tripulacao $tripulacao)
    {
        return $this->estado["vontade_" . $tripulacao->indice];
    }
    public function incrementa_vontade(Tripulacao $tripulacao)
    {
        $coluna = "vontade_" . $tripulacao->indice;
        $this->connection->run("UPDATE tb_combate_npc SET $coluna = $coluna + 1 WHERE id = ?",
            "i", [$this->estado["id"]]
        );
    }

    public function get_movimentos_restantes(Tripulacao $tripulacao, $custo)
    {
        return max(0, $this->estado["move"] - $custo);
    }

    public function consome_movimentos(Tripulacao $tripulacao, $custo)
    {
        $this->estado["move"] -= $custo;
        $this->connection->run("UPDATE tb_combate_npc SET `move` = `move` - $custo WHERE id = ?",
            "i", [$this->estado["id"]]);
    }

    public function aplica_penalidade_perder_vez(Tripulacao $tripulacao)
    {
        // nao tem perder vez contra npc
    }

    /**
     * @return int|null
     */
    public function get_tempo_restante_turno()
    {
        return null;
    }
}
