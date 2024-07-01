<?php
namespace Regras\Combate;

class IaControleTripulacao
{

    /**
     * @var Combate
     */
    public $combate;
    /**
     * @var Tripulacao
     */
    public $tripulacao;

    public function __construct(Combate $combate, Tripulacao $tripulacao)
    {
        $this->combate = $combate;
        $this->tripulacao = $tripulacao;
    }

    public function executa_acao($moves)
    {
        $this->combate->minhaTripulacao = $this->tripulacao;
        if ($moves > 0) {
            $this->movimenta();
            return "movimento";
        } else {
            $this->ataca_inimigo_escolhido();
            return "ataque";
        }
    }


    public function movimenta()
    {
        $movimento_escolhido = $this->escolhe_movimento();

        if (! $movimento_escolhido) {
            $this->tripulacao->consome_movimentos($this->tripulacao->get_movimentos_restantes());
            return;
        }

        $atacante_escolhido = $this->escolhe_ataque();

        $alvos_proximos = $this->get_inimigos_proximos($atacante_escolhido["x"], $atacante_escolhido["y"], 1);

        if (count($alvos_proximos)) {
            $this->tripulacao->consome_movimentos($this->tripulacao->get_movimentos_restantes());
            return;
        }

        $this->combate->mover($movimento_escolhido["pers"]->estado["cod_pers"], $movimento_escolhido["x"] . "_" . $movimento_escolhido["y"]);
    }

    public function escolhe_movimento()
    {
        $movimentos = [];

        foreach ($this->tripulacao->personagens as $pers) {
            $posicao = $pers->get_posicao_tabuleiro();
            for ($x = $posicao["x"] - 1; $x <= $posicao["x"] + 1; $x++) {
                for ($y = $posicao["y"] - 1; $y <= $posicao["y"] + 1; $y++) {
                    if (($x != $posicao["x"] || $y != $posicao["y"]) && $x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
                        $quadro = $this->combate->tabuleiro->get_quadro($x . "_" . $y);
                        if (! $quadro->personagem) {
                            $movimentos[] = [
                                "pers" => $pers,
                                "quadro" => $quadro,
                                "score" => $this->calc_score_posicao($pers, $x, $y),
                                "x" => $x,
                                "y" => $y
                            ];
                        }
                    }
                }
            }
        }

        $maior_score = -9999999999;
        $movimento_escolhido = null;
        foreach ($movimentos as $movimento) {
            if ($movimento["score"] > $maior_score) {
                $maior_score = $movimento["score"];
                $movimento_escolhido = $movimento;
            }
        }
        return $movimento_escolhido;
    }

    public function ataca_inimigo_escolhido()
    {
        $atacante_escolhido = $this->escolhe_ataque();

        $alvos_proximos = $this->get_inimigos_proximos($atacante_escolhido["x"], $atacante_escolhido["y"], 10);

        if (count($alvos_proximos)) {
            $distancia_minima = 999999999999;
            $alvo_escolhido = null;
            foreach ($alvos_proximos as $alvo) {
                if (($distancia = $atacante_escolhido["quadro"]->get_distancia($alvo)) < $distancia_minima) {
                    $distancia_minima = $distancia;
                    $alvo_escolhido = $alvo;
                }
            }

            $hablidades = array_filter($atacante_escolhido["pers"]->get_habilidades(), function ($habilidade) {
                return $habilidade->estado["dano"] > 0;
            });
            $habilidade = $hablidades[array_rand($hablidades)];
            $this->combate->atacar($atacante_escolhido["pers"]->estado["cod_pers"], $habilidade->estado["cod"], $alvo_escolhido->x . "_" . $alvo_escolhido->y);
        } else {
            $this->combate->passar_vez();
        }
    }

    public function escolhe_ataque()
    {
        $posicoes = [];

        foreach ($this->tripulacao->personagens as $pers) {
            $posicao = $pers->get_posicao_tabuleiro();
            $quadro = $this->combate->tabuleiro->get_quadro($posicao["x"] . "_" . $posicao["y"]);
            $posicoes[] = [
                "pers" => $pers,
                "quadro" => $quadro,
                "score" => $this->calc_score_posicao($pers, $posicao["x"], $posicao["y"]),
                "x" => $posicao["x"],
                "y" => $posicao["y"]
            ];
        }

        $maior_score = -9999999999;
        $movimento_escolhido = null;
        foreach ($posicoes as $posicao) {
            if ($posicao["score"] > $maior_score) {
                $maior_score = $posicao["score"];
                $movimento_escolhido = $posicao;
            }
        }
        return $movimento_escolhido;
    }

    public function get_inimigo_escolhido()
    {
        $escolhido = null;
        $max_rnk = -99999999999;

        $tripulacao_inimiga = $this->tripulacao->indice == "1" ? $this->combate->tripulacoes["2"] : $this->combate->tripulacoes["1"];

        foreach ($tripulacao_inimiga->personagens as $inimigo) {
            $rnk = 1.0 - $inimigo->estado["hp"] / $inimigo->estado["hp_max"];
            if ($rnk > $max_rnk) {
                $max_rnk = $rnk;
                $escolhido = $inimigo;
            }
        }
        return $escolhido;
    }

    public function calc_score_posicao(Personagem $pers, $x, $y)
    {
        $inimigo = $this->get_inimigo_escolhido();
        $distancia = $this->combate->tabuleiro->get_quadro_personagem($inimigo)->get_distancia(new Quadro($x, $y)) * 2;
        return (-$distancia) + count($this->get_inimigos_proximos($x, $y, 1)) - count($this->get_aliados_proximos($x, $y, 1));
    }

    public function get_inimigos_proximos($x, $y, $dist = 1)
    {
        $inimigos = [];
        $proximos = $this->get_personagens_proximos($x, $y, $dist);
        foreach ($proximos as $proximo) {
            if ($proximo->personagem->tripulacao->indice != $this->tripulacao->indice) {
                $inimigos[] = $proximo;
            }
        }
        return $inimigos;
    }

    public function get_aliados_proximos($x, $y, $dist = 1)
    {
        $inimigos = [];
        $proximos = $this->get_personagens_proximos($x, $y, $dist);
        foreach ($proximos as $proximo) {
            if ($proximo->personagem->tripulacao->indice == $this->tripulacao->indice) {
                $inimigos[] = $proximo;
            }
        }
        return $inimigos;
    }

    public function get_personagens_proximos($quadro_x, $quadro_y, $dist = 1)
    {
        $quadros = [];
        for ($x = $quadro_x - $dist; $x <= $quadro_x + $dist; $x++) {
            for ($y = $quadro_y - $dist; $y <= $quadro_y + $dist; $y++) {
                if (($x != $quadro_x || $y != $quadro_y) && $x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
                    $quadro = $this->combate->tabuleiro->get_quadro($x . "_" . $y);
                    if ($quadro && $quadro->personagem) {
                        $quadros[] = $quadro;
                    }
                }
            }
        }
        return $quadros;
    }
}
