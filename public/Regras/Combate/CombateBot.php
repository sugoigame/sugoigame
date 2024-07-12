<?php
namespace Regras\Combate;

class CombateBot extends Combate
{
    public function init()
    {
        $this->tripulacoes = [
            "1" => new TripulacaoJogador($this, $this->userDetails->tripulacao, "1"),
            "2" => new TripulacaoBot($this, $this->userDetails->combate_bot, "2")
        ];

        $this->minhaTripulacao = $this->tripulacoes["1"];

        $this->relatorio = new RelatorioBot($this);
    }

    public function vez_de_quem()
    {
        return $this->estado["vez"];
    }

    public function muda_vez()
    {
        $vez = $this->estado["vez"] == 1 ? 2 : 1;
        $this->connection->run("UPDATE tb_combate_bot SET vez = ?, `move` = 5 WHERE tripulacao_id = ?",
            "ii", [$vez, $this->userDetails->tripulacao["id"]]);
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
        $this->connection->run("UPDATE tb_combate_bot SET $coluna = $coluna + 1 WHERE id = ?",
            "i", [$this->estado["id"]]
        );
    }

    public function get_movimentos_restantes(Tripulacao $tripulacao, $custo)
    {
        return max(0, $this->estado["move"] - $custo);
    }

    public function consome_movimentos(Tripulacao $tripulacao, $custo)
    {
        $this->connection->run("UPDATE tb_combate_bot SET `move` = `move` - $custo WHERE id = ?",
            "i", [$this->estado["id"]]);
    }

    public function aplica_penalidade_perder_vez(Tripulacao $tripulacao)
    {
        // nao tem perder vez contra bot
    }


    public function turno_bot()
    {
        if ($this->vez_de_quem() != "2") {
            $this->protector->exit_error("Não é a vez do bot");
        }

        /** @var TripulacaoBot */
        $tripulacao_bot = $this->tripulacoes["2"];

        $tripulacao_bot->executa_acao();
    }

}
