<?php
namespace Regras\Combate;

abstract class Relatorio
{
    /**
     * @var Combate
     */
    protected $combate;

    /**
     * @var array
     */
    protected $acao = [];

    /**
     * @var array
     */
    protected $consequencias = [];

    /**
     * @param Combate
     */
    public function __construct($combate)
    {
        $this->combate = $combate;
    }

    public function registra_ataque(Personagem $atacante, Habilidade $habilidade)
    {
        $this->acao = [
            "tipo" => "ataque",
            "personagem" => [
                "nome" => $atacante->estado["nome"],
                "cod" => $atacante->estado["cod"],
                "tripulacao_id" => $atacante->estado["tripulacao_id"],
                "img" => $atacante->estado["img"],
                "skin_r" => $atacante->estado["skin_r"],
            ],
            "habilidade" => $habilidade->estado
        ];
    }

    public function registra_dano(Personagem $atacante, Quadro $quadro, $dano)
    {
        $this->consequencias[] = [
            "alvo" => $quadro->personagem ? [
                "nome" => $quadro->personagem->estado["nome"],
                "cod" => $quadro->personagem->estado["cod"],
                "tripulacao_id" => $quadro->personagem->estado["tripulacao_id"],
                "img" => $quadro->personagem->estado["img"],
                "skin_r" => $quadro->personagem->estado["skin_r"],
            ] : null,
            "quadro" => [
                "x" => $quadro->x,
                "y" => $quadro->y
            ],
            "dano" => $dano
        ];
    }

    public function registra_cura(Personagem $pers, $quantidade)
    {
        $this->consequencias[] = [
            "alvo" => [
                "nome" => $pers->estado["nome"],
                "cod" => $pers->estado["cod"],
                "img" => $pers->estado["img"],
                "skin_r" => $pers->estado["skin_r"],
            ],
            "quadro" => $pers->get_posicao_tabuleiro(),
            "cura" => $quantidade
        ];
    }

    abstract public function salvar();
}
