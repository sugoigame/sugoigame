<?php
namespace Regras\Combate;

class TripulacaoBot extends Tripulacao
{

    protected function init()
    {
        $this->controle = new IaControleTripulacao($this->combate, $this);

        $estados = get_pers_bot_in_combate($this->estado["id"]);

        $this->personagens = [];

        foreach ($estados as $estado) {
            $this->personagens[$estado["cod_pers"]] = new PersonagemBot($this->combate, $this, $estado);
        }
        $this->estado["tripulacao"] = $this->estado["tripulacao_inimiga"];
    }

    public function get_efeito($efeito)
    {
        return 0;
    }

    public function salvar()
    {
        foreach ($this->personagens as $pers) {
            $this->combate->connection->run("UPDATE tb_combate_personagens_bot SET hp = ?, hp_max = ?, quadro_x = ?, quadro_y = ?, efeitos = ? WHERE id = ?",
                "iiiisi", [
                    $pers->estado["hp"],
                    $pers->estado["hp_max"],
                    $pers->estado["quadro_x"],
                    $pers->estado["quadro_y"],
                    json_encode($pers->estado["efeitos"]),
                    $pers->estado["bot_id"],
                ]);
        }
    }
    public function reduzir_espera_habilidades()
    {
        // habilidades de bot não tem recarga
    }
}
