<?php
namespace Regras\Combate;

class CombateJogador extends Combate
{
    public function init()
    {
        $this->tripulacoes = [
            "1" => new TripulacaoJogador($this, $this->userDetails->tripulacoes_pvp["1"], "1"),
            "2" => new TripulacaoJogador($this, $this->userDetails->tripulacoes_pvp["2"], "2")
        ];

        $minha_tripulacao_index = $this->userDetails->tripulacao["id"] == $this->userDetails->combate_pvp["id_1"] ? "1" : "2";
        $this->minhaTripulacao = $this->tripulacoes[$minha_tripulacao_index];

        $this->relatorio = new RelatorioJogador($this);
    }

    public function vez_de_quem()
    {
        return $this->userDetails->combate_pvp["vez"];
    }

    public function perdeu_vez()
    {
        return $this->userDetails->combate_pvp["vez_tempo"] < atual_segundo();
    }

    public function muda_vez()
    {
        $vez = $this->userDetails->combate_pvp["vez"] == 1 ? 2 : 1;
        $tempo = atual_segundo() + ($this->userDetails->combate_pvp["passe_$vez"] >= 3 ? 30 : 90);
        $this->connection->run("UPDATE tb_combate SET vez = ?, vez_tempo = ?, move_1 = ?, move_2 = ? WHERE combate = ?",
            "iiiii", array($vez, $tempo, 5, 5, $this->userDetails->combate_pvp["combate"]));
    }

    public function vale_quanta_recompensa()
    {
        if ($this->userDetails->combate_pvp
            && $this->userDetails->combate_pvp["tipo"] != TIPO_AMIGAVEL
            && $this->userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL) {
            return $this->userDetails->combate_pvp["tipo"] == TIPO_COLISEU ? MAX_FA_COMBATE_COLISEU : MAX_FA_COMBATE;
        }

        return 0;
    }
}
