<?php
namespace Regras\Combate;

class RelatorioBot extends Relatorio
{
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

        $this->combate->connection->run("UPDATE tb_combate_bot SET relatorio = ? WHERE tripulacao_id = ?",
            "si", [$novo_relatorio ? $novo_relatorio : "", $this->combate->userDetails->tripulacao["id"]]);
    }
}
