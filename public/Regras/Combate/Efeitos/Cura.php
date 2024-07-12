<?php
namespace Regras\Combate\Efeitos;

use Regras\Combate\Gatilhos;
use Regras\Combate\Personagem;

class Cura
{
    public function resolve(Personagem $pers, array $efeito)
    {
        $pers->estado["hp"] = min($pers->estado["hp"] + $efeito["bonus"]["valor"], $pers->estado["hp_max"]);
        $pers->combate->relatorio->registra_cura($pers, $efeito["bonus"]["valor"]);
        $pers->combate->gatilhos->dispara(Gatilhos::FOI_CURADO, $pers, $efeito["bonus"]["valor"]);
    }
}
