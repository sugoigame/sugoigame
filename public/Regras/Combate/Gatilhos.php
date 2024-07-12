<?php
namespace Regras\Combate;

class Gatilhos
{
    public const ACERTOU = "ACERTOU";
    public const CRITOU = "CRITOU";
    public const ESQUIVOU = "ESQUIVOU";
    public const BLOQUEOU = "BLOQUEOU";
    public const MATOU = "MATOU";
    public const MORREU = "MORREU";
    public const FOI_CURADO = "FOI_CURADO";

    /**
     * @var Combate
     */
    protected $combate;

    /**
     * @param Combate
     */
    public function __construct($combate)
    {
        $this->combate = $combate;
    }

    public function dispara($evento, $acionador, $params)
    {
        // todo implementar quando for necessário
        return null;
    }
}
