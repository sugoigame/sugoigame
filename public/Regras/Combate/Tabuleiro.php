<?php
namespace Regras\Combate;

class Tabuleiro
{

    /**
     * @var Combate
     */
    public $combate;

    /**
     * @var Personagem[]
     */
    public $personagens;

    /**
     * @var Quadro[][]
     */
    public $quadros;

    /**
     * @param Combate
     */
    public function __construct($combate)
    {
        $this->combate = $combate;

        $this->quadros["npc"]["npc"] = new Quadro("npc", "npc");
        for ($x = 0; $x < 10; $x++) {
            for ($y = 0; $y < 20; $y++) {
                $this->quadros[$x][$y] = new Quadro($x, $y);
            }
        }

        $this->personagens = [];
        foreach ($this->combate->tripulacoes as $tripulacao) {
            foreach ($tripulacao->personagens as $personagem) {
                if ($personagem->estado["hp"] > 0) {
                    $this->personagens[] = $personagem;
                    $posicao = $personagem->get_posicao_tabuleiro();
                    $this->quadros[$posicao["x"]][$posicao["y"]]->personagem = $personagem;
                }
            }
        }
    }

    /**
     * @param string
     * @return Quadro
     */
    public function get_quadro($quadro)
    {
        return $this->get_quadros($quadro)[0];
    }

    /**
     * @param string
     * @return Quadro[]
     */
    public function get_quadros($quadros)
    {
        $quadros = explode(";", $quadros);

        foreach ($quadros as $index => $quadro) {
            if ($quadro == "npc") {
                $quadros[$index] = $this->quadros["npc"]["npc"];
            } else {
                $xy = explode("_", $quadro);
                $quadros[$index] = $this->quadros[$xy[0]][$xy[1]];
            }
        }

        if (! $this->is_area_valida($quadros)) {
            $this->combate->protector->exit_error("Área inválida");
        }

        return $quadros;
    }

    public function get_quadro_personagem(Personagem $personagem) : Quadro
    {
        $posicao = $personagem->get_posicao_tabuleiro();
        return $this->quadros[$posicao["x"]][$posicao["y"]];
    }

    public function get_personagens_linha(int $x) : array
    {
        $personagens = [];
        foreach ($this->quadros[$x] as $quadro) {
            if ($quadro->personagem) {
                $personagens[] = $quadro->personagem;
            }
        }
        return $personagens;
    }

    /**
     * @param Quadro[]
     */
    public function is_area_valida($quadros)
    {
        for ($i = 1; $i < count($quadros); $i++) {
            $quadro = $quadros[$i];
            $quadro_anterior = $quadros[$i - 1];

            if (($quadro->x === "npc" && $quadro_anterior->x != 0) || $quadro->get_distancia($quadro_anterior) > 1.9) {
                return false;
            }
        }
        return true;
    }


    public function get_custo_movimento(Personagem $pers, Quadro $destino) : int
    {
        $origem = $pers->get_posicao_tabuleiro();
        return max(abs($origem["x"] - $destino->x), abs($origem["y"] - $destino->y));
    }


    public function resolve_efeitos()
    {

    }

    public function reduz_duracao_efeitos()
    {

    }

}
