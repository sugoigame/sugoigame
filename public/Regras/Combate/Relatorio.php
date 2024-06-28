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
    protected $ataque = [];

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
        $this->ataque = [
            "atacante" => [
                "nome" => $atacante->estado["nome"],
                "cod" => $atacante->estado["cod"],
                "tripulacao_id" => $atacante->estado["tripulacao_id"],
                "img" => $atacante->estado["img"],
                "skin_r" => $atacante->estado["skin_r"],
            ],
            "habilidade" => $habilidade->estado
        ];
    }

    public function registra_dano(Personagem $atacante, Personagem $defensor, $dano)
    {
        $this->consequencias[] = [
            "alvo" => [
                "nome" => $defensor->estado["nome"],
                "cod" => $defensor->estado["cod"],
                "tripulacao_id" => $defensor->estado["tripulacao_id"],
                "img" => $defensor->estado["img"],
                "skin_r" => $defensor->estado["skin_r"],
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
            "cura" => $quantidade
        ];
    }

    abstract public function salvar();
}
