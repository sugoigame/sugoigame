<?php
namespace Regras\Combate;

class TripulacaoJogador extends Tripulacao
{
    protected function init()
    {
        $estados = get_pers_in_combate($this->estado["id"]);

        $this->personagens = [];

        foreach ($estados as $estado) {
            $this->personagens[$estado["cod_pers"]] = new PersonagemJogador($this->combate, $this, $estado);
        }
    }

    public function get_vontade()
    {
        return $this->combate->userDetails->combate_pvp["vontade_" . $this->indice];
    }

    public function incrementa_vontade()
    {
        $this->combate->connection->run("UPDATE tb_combate SET vontade_" . $this->indice . " = vontade_" . $this->indice . " + 1 WHERE combate = ?",
            "i", [$this->combate->userDetails->combate_pvp["combate"]]
        );
    }

    public function get_efeito($efeito)
    {
        return $this->combate->userDetails->buffs->get_efeito_from_tripulacao($efeito, $this->estado["id"]);
    }
    public function reduzir_espera_habilidades()
    {
        $this->combate->connection->run("DELETE FROM tb_combate_skil_espera WHERE id = ? AND espera <= 0",
            "i", [$this->estado["id"]]
        );

        $this->combate->connection->run("UPDATE tb_combate_skil_espera SET espera = espera - 1 WHERE id = ?",
            "i", [$this->estado["id"]]
        );
    }

    public function aplica_penalidade_perder_vez()
    {
        $passe = "passe_" . $this->combate->userDetails->combate_pvp["vez"];
        $this->combate->connection->run("UPDATE tb_combate SET $passe = $passe + 1 WHERE combate = ?",
            "i", [$this->combate->userDetails->combate_pvp["combate"]]);
    }

    public function salvar()
    {
        foreach ($this->personagens as $pers) {
            $this->combate->connection->run("UPDATE tb_combate_personagens SET hp = ?, hp_max = ?, quadro_x = ?, quadro_y = ?, fa_ganha = ?, efeitos = ? WHERE cod = ?",
                "iiiiisi", [
                    $pers->estado["hp"],
                    $pers->estado["hp_max"],
                    $pers->estado["quadro_x"],
                    $pers->estado["quadro_y"],
                    $pers->estado["fa_ganha"],
                    json_encode($pers->estado["efeitos"]),
                    $pers->estado["cod"],
                ]);

            $this->combate->connection->run("UPDATE tb_personagens SET fama_ameaca = ?, xp = ?, profissao_xp = ? WHERE cod = ?",
                "iiii", [
                    $pers->estado["fama_ameaca"],
                    $pers->estado["xp"],
                    $pers->estado["profissao_xp"],
                    $pers->estado["cod"]
                ]);
        }
    }
}
