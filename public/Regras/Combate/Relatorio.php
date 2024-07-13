<?php
namespace Regras\Combate;

abstract class Relatorio
{
    /**
     * @var Combate
     */
    public $combate;

    /**
     * @var array
     */
    public $acao = [];

    /**
     * @var array
     */
    public $consequencias = [];

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
            "id" => atual_segundo(),
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

    public function perder_vez(Tripulacao $tripulacao)
    {
        $this->acao = [
            "id" => atual_segundo(),
            "tipo" => "perder_vez",
            "tripulacao" => [
                "nome" => $tripulacao->estado["tripulacao"],
            ],
        ];
    }

    public function passar_vez(Tripulacao $tripulacao)
    {
        $this->acao = [
            "id" => atual_segundo(),
            "tipo" => "passe",
            "tripulacao" => [
                "nome" => $tripulacao->estado["tripulacao"],
            ],
        ];
    }

    public function registra_movimento(Personagem $pers, Quadro $destino)
    {
        $this->acao = [
            "id" => atual_segundo(),
            "tipo" => "movimento",
            "personagem" => [
                "nome" => $pers->estado["nome"],
                "cod" => $pers->estado["cod"],
                "tripulacao_id" => $pers->estado["tripulacao_id"],
                "img" => $pers->estado["img"],
                "skin_r" => $pers->estado["skin_r"],
            ],
            "destino" => [
                "x" => $destino->x,
                "y" => $destino->y,
            ]
        ];
    }

    abstract public function salvar();
}
