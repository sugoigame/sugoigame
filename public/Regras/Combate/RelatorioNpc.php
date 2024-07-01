<?php
namespace Regras\Combate;

class RelatorioNpc extends Relatorio
{

    public function registra_dano(Personagem $atacante, Quadro $quadro, $dano)
    {
        parent::registra_dano($atacante, $quadro, $dano);

        if ($quadro->personagem && $quadro->personagem->estado["cod"] == "npc" && $this->combate->estado["boss_id"]) {
            $real_boss_id = $this->combate->connection->run("SELECT real_boss_id FROM tb_boss WHERE id = ?",
                "i", [$this->combate->estado["boss_id"]])->fetch_array()["real_boss_id"];

            $log = $this->combate->connection->run("SELECT * FROM tb_boss_damage WHERE tripulacao_id = ?  AND real_boss_id = ?",
                "ii", [$this->combate->userDetails->tripulacao["id"], $real_boss_id]);
            if ($log->count()) {
                $this->combate->connection->run("UPDATE tb_boss_damage SET damage = damage + ? WHERE tripulacao_id = ?  AND real_boss_id = ?",
                    "iii", [$dano["dano"], $this->combate->userDetails->tripulacao["id"], $real_boss_id]);
            } else {
                $this->combate->connection->run("INSERT INTO tb_boss_damage (tripulacao_id, damage, real_boss_id) VALUES (?, ?, ?)",
                    "iii", [$this->combate->userDetails->tripulacao["id"], $dano["dano"], $real_boss_id]);
            }

            if ($this->combate->userDetails->ally) {
                $missao_ally_result = $this->combate->connection->run("SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ? AND boss_id = ?",
                    "ii", [$this->combate->userDetails->ally["cod_alianca"], $real_boss_id]);

                if ($missao_ally_result->count()) {
                    $missao_ally = $missao_ally_result->fetch_array();

                    if ($missao_ally["quant"] < $missao_ally["fim"]) {
                        $this->combate->connection->run("UPDATE tb_alianca_missoes SET quant = quant + ? WHERE cod_alianca = ?",
                            "ii", [$dano["dano"], $this->combate->userDetails->ally["cod_alianca"]]);
                    }
                }
            }
        }
    }

    public function salvar()
    {
        $acao = $this->acao;
        $acao["consequencias"] = $this->consequencias;

        $relatorio_antigo = $this->combate->estado["relatorio"] && strlen($this->combate->estado["relatorio"])
            ? json_decode($this->combate->estado["relatorio"], true)
            : array();

        $relatorio_antigo = array_slice($relatorio_antigo, 0, 10);
        array_unshift($relatorio_antigo, $acao);
        $novo_relatorio = json_encode($relatorio_antigo);

        $this->combate->connection->run("UPDATE tb_combate_npc SET relatorio = ? WHERE id = ?",
            "si", [$novo_relatorio ? $novo_relatorio : "", $this->combate->estado["id"]]);

    }
}
