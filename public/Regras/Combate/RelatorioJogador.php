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

        $fixed_dir = str_replace('/', DIRECTORY_SEPARATOR, str_replace('\\', DIRECTORY_SEPARATOR, __DIR__));
        $search = str_replace('/', DIRECTORY_SEPARATOR, "Regras/Combate");
        $sub_path = str_replace('/', DIRECTORY_SEPARATOR, "Logs/PvP/");

        $log_file = @fopen(str_replace($search, "", $fixed_dir) . $sub_path . $this->combate->userDetails->combate_pvp["combate"] . ".log", "a+");

        $acao = $this->acao;
        $acao["consequencias"] = $this->consequencias;
        fwrite($log_file, json_encode($acao) . "\n");

        fclose($log_file);
    }
}
