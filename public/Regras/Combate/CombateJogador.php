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
    }

    public function vez_de_quem()
    {
        return $this->userDetails->combate_pvp["vez"];
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
