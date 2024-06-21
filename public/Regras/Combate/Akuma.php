<?php
namespace Regras\Combate;

class Akuma
{
    /**
     * @var Combate
     */
    protected $combate;

    /**
     * @var array
     */
    protected $estado;

    /**
     * @param Combate
     * @param array
     */
    public function __construct($combate, $estado)
    {
        $this->combate = $combate;
        $this->combateestado = $estado;
    }

    /**
     * @param Akuma
     * @return float
     */
    public function get_vantagem_sobre(Akuma $outraAkuma)
    {
        $atacante = $this->estado["categoria"];
        $alvo = $outraAkuma->estado["categoria"];
        if (($atacante == "ofensiva" && $alvo == "tatica")
            || ($atacante == "tatica" && $alvo == "defensiva")
            || ($atacante == "defensiva" && $alvo == "ofensiva")
            || ($atacante == "mitica" && $alvo == "ancestral")
            || ($atacante == "ancestral" && $alvo == "mitica")) {
            return 1.2;
        }

        if (isset($this->estado["vantagens"])) {
            foreach ($this->estado["vantagens"] as $vantagem) {
                if ($vantagem == $outraAkuma->estado["cod_akuma"]) {
                    return 1.2;
                }
            }
        }

        return 1;
    }

}
