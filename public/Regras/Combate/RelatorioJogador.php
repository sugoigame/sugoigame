<?php
namespace Regras\Combate;

class RelatorioJogador extends Relatorio
{
    public function salvar()
    {
        foreach ($this->consequencias as $consequencia) {
            if (isset($consequencia["dano"]) && $consequencia["dano"]["nova_hp"] <= 0) {
                $this->combate->connection->run("INSERT INTO tb_combate_log_personagem_morto (combate, tripulacao_id, personagem_id) VALUE (?,?,?)",
                    "iii", [$this->combate->userDetails->combate_pvp["combate"], $consequencia["alvo"]["tripulacao_id"], $consequencia["alvo"]["cod"]]);
            }
        }


        $log_file = fopen(dirname(dirname(__FILE__)) . "/Logs/PvP/" . $this->combate->userDetails->combate_pvp["combate"] . ".log", "a+");

        fwrite($log_file, json_encode([
            "ataque" => $this->ataque,
            "consequencias" => $this->consequencias,
        ]) . "\n");

        fclose($log_file);
    }
}
