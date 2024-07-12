<?php
namespace Regras\Combate\Efeitos;

use Regras\Combate\Personagem;

class Veneno
{
    public function resolve(Personagem $pers, array $efeito)
    {
        $pers->estado["hp"] = max(1, $pers->estado["hp"] - $efeito["bonus"]["valor"]);
    }
}
