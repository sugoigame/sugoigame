<?php
namespace Regras\Combate;

class CombateJogador extends Combate
{
    public function init()
    {
        $this->tripulacoes = [
            "1" => new TripulacaoJogador($this, $this->userDetails->tripulacoes_pvp["1"]),
            "2" => new TripulacaoJogador($this, $this->userDetails->tripulacoes_pvp["2"])
        ];

        $this->minhaTripulacaoIndex = $this->userDetails->tripulacao["id"] == $this->userDetails->combate_pvp["id_1"] ? "1" : "2";
        $this->minhaTripulacao = $this->tripulacoes[$this->minhaTripulacaoIndex];
    }

    public function vez_de_quem()
    {
        return $this->userDetails->combate_pvp["vez"];
    }
}
