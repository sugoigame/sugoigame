<?php
namespace Regras\Combate;

class TripulacaoNpc extends Tripulacao
{
    /**
     * @var IaControleNpc
     */
    public $controle;

    protected function init()
    {
        $this->controle = new IaControleNpc($this->combate, $this);

        $hp = $this->estado["hp_npc"];
        $hp_max = $this->estado["hp_max_npc"];

        if ($this->estado["boss_id"]) {
            $boss = $this->combate->connection->run("SELECT * FROM tb_boss WHERE id = ?",
                "i", [$this->estado["boss_id"]])->fetch_array();
            $hp = $boss["hp"];
            $hp_max = 1000000;
        }

        $this->personagens = [
            "npc" => new PersonagemNpc($this->combate, $this, [
                "cod" => "npc",
                "cod_pers" => "npc",
                "id" => "npc",
                "tripulacao_id" => "npc",
                "nome" => $this->estado["nome_npc"],
                "lvl" => 1,
                "classe" => 0,
                "classe_score" => 0,
                "haki_esq" => 0,
                "haki_cri" => 0,
                "haki_hdr" => 0,
                "fama_ameaca" => 0,
                "akuma" => null,
                "xp" => 0,
                "profissao" => 0,
                "profissao_xp" => 0,
                "profissao_xp_max" => 0,
                "quadro_x" => "npc",
                "quadro_y" => "npc",
                "hp" => $hp,
                "hp_max" => $hp_max,
                "img" => $this->estado["img_npc"],
                "skin_c" => 0,
                "skin_r" => 0,
                "borda" => 0,
                "titulo" => null,
                "atk" => $this->estado["atk_npc"],
                "def" => $this->estado["def_npc"],
                "agl" => $this->estado["agl_npc"],
                "res" => $this->estado["res_npc"],
                "pre" => $this->estado["pre_npc"],
                "dex" => $this->estado["dex_npc"],
                "con" => $this->estado["con_npc"],
                "vit" => 1,
                "fa_ganha" => 0,
                "cod_capitao" => "npc",
                "efeitos" => $this->estado["efeitos"] ? json_decode($this->estado["efeitos"], true) : []
            ])
        ];
    }

    public function get_efeito($efeito)
    {
        return 0;
    }

    public function reduzir_espera_habilidades()
    {
        // habilidades de npc não tem recarga
    }
    public function salvar()
    {
        if (! $this->estado["boss_id"]) {
            $this->combate->connection->run("UPDATE tb_combate_npc SET hp_npc = ? WHERE id = ?",
                "ii", [$this->personagens["npc"]->estado["hp"], $this->estado["id"]]);
        } else {
            $this->combate->connection->run("UPDATE tb_boss SET hp = ? WHERE id = ?",
                "ii", [$this->personagens["npc"]->estado["hp"], $this->estado["boss_id"]]);
        }
    }
    public function executa_acao()
    {
        return $this->controle->executa_acao();
    }
}
