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
            if ($this->has_inimigo_proximo()) {
                $this->espalha();
            } else {
                $this->aproxima_inimigo();
            }
            return "movimento";
        } else {
            if ($this->has_inimigo_proximo()) {
                $this->ataca_inimigo_proximo();
            } else {
                $this->ataca_inimigo_distante();
            }
            return "ataque";
        }
    }

    public function has_inimigo_proximo()
    {
        return $this->get_inimigo_proximo();
    }

    public function get_inimigo_proximo()
    {
        $reserva = null;
        $max_rnk = -99999999999;
        foreach ($this->tripulacao->personagens as $pers) {
            $pers_in_coord = $this->get_inimigos_proximos($pers->estado["tripulacao_id"], $pers->estado["quadro_x"], $pers->estado["quadro_y"], 4);
            if (count($pers_in_coord)) {
                foreach ($pers_in_coord as $alvo) {
                    $informacao = array(
                        "pers" => $pers,
                        "inimigo" => $alvo
                    );
                    $rnk = $alvo->estado["hp_max"] - $alvo->estado["hp"];
                    if ($rnk > $max_rnk) {
                        $max_rnk = $rnk;
                        $reserva = $informacao;
                    }
                }
            }
        }
        return $reserva;
    }

    public function get_inimigos_proximos($tripulacao_id, $quadro_x, $quadro_y, $dist = 1)
    {
        $inimigos = [];
        $proximos = $this->get_personagens_proximos($quadro_x, $quadro_y, $dist);
        foreach ($proximos as $proximo) {
            if ($proximo->estado["tripulacao_id"] != $tripulacao_id) {
                $inimigos[] = $proximo;
            }
        }
        return $inimigos;
    }

    public function get_personagens_proximos($quadro_x, $quadro_y, $dist = 1)
    {
        $personagens = [];
        for ($x = $quadro_x - $dist; $x <= $quadro_x + $dist; $x++) {
            for ($y = $quadro_y - $dist; $y <= $quadro_y + $dist; $y++) {
                if (($x != $quadro_x || $y != $quadro_y) && $x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
                    $pers_in_coord = $this->get_pers_in_cod($x, $y);
                    if ($pers_in_coord) {
                        $personagens[] = $pers_in_coord;
                    }
                }
            }
        }
        return $personagens;
    }

    public function get_pers_in_cod($x, $y)
    {
        return $this->combate->tabuleiro->get_quadro($x . "_" . $y)->personagem;
    }

    public function espalha()
    {
        $movimentos = array();
        $proximo = $this->get_inimigo_proximo()["pers"];

        foreach ($this->tripulacao->personagens as $pers) {
            $movimentos_pers = [];
            $colado = false;
            for ($x = $pers->estado["quadro_x"] - 1; $x <= $pers->estado["quadro_x"] + 1; $x++) {
                for ($y = $pers->estado["quadro_y"] - 1; $y <= $pers->estado["quadro_y"] + 1; $y++) {
                    if (($x != $pers->estado["quadro_x"] || $y != $pers->estado["quadro_y"]) && $x >= 0 && $x <= Tabuleiro::MAX_TABULEIRO_X && $y >= 0 && $y <= Tabuleiro::MAX_TABULEIRO_Y) {
                        $pers_in_coord = $this->get_pers_in_cod($x, $y);
                        if (! $pers_in_coord) {
                            $movimentos_pers[] = array(
                                "pers" => $pers,
                                "dist_min" => $this->calc_score_movimento($pers, $x, $y),
                                "x" => $x,
                                "y" => $y
                            );
                        } elseif ($pers_in_coord->estado["tripulacao_id"] == $pers->estado["id"]) {
                            $colado = true;
                        }
                    }
                }
            }
            foreach ($movimentos_pers as $movimento) {
                if ($colado) {
                    $movimento["dist_min"] += 1;
                }
                $movimentos[] = $movimento;
            }
        }

        $maior_dist_min = -100000;
        foreach ($movimentos as $movimento) {
            if ($movimento["dist_min"] > $maior_dist_min) {
                $maior_dist_min = $movimento["dist_min"];
            }
        }

        $movimentos_maior_dist_min = array();
        foreach ($movimentos as $movimento) {
            if ($movimento["dist_min"] == $maior_dist_min) {
                $movimentos_maior_dist_min[] = $movimento;
            }
        }

        if (! count($movimentos_maior_dist_min) || ! ($movimento_index = array_rand($movimentos_maior_dist_min))) {
            $this->tripulacao->consome_movimentos($this->tripulacao->get_movimentos_restantes());
            return;
        }

        $movimento = $movimentos_maior_dist_min[$movimento_index];

        if ($movimento["pers"]->estado["cod"] == $proximo->estado["cod"]) {
            $this->ataca_inimigo_proximo();
        } else {
            $this->combate->mover($movimento["pers"]->estado["cod_pers"], $movimento["x"] . "_" . $movimento["y"]);
        }
    }

    public function aproxima_inimigo()
    {
        $moves = $this->tripulacao->get_movimentos_restantes();

        foreach ($this->tripulacao->personagens as $pers) {
            for ($x = $pers->estado["quadro_x"] - $moves; $x <= $pers->estado["quadro_x"] + $moves; $x++) {
                for ($y = $pers->estado["quadro_y"] - $moves; $y <= $pers->estado["quadro_y"] + $moves; $y++) {
                    if ($x >= 0 && $x <= Tabuleiro::MAX_TABULEIRO_X && $y >= 0 && $y <= Tabuleiro::MAX_TABULEIRO_Y) {
                        $pers_in_coord = $this->get_pers_in_cod($x, $y);
                        if ($pers_in_coord && $pers_in_coord->estado["tripulacao_id"] != $pers->estado["tripulacao_id"]) {
                            $dist_x = $pers_in_coord->estado["quadro_x"] - $pers->estado["quadro_x"];
                            $dist_y = $pers_in_coord->estado["quadro_y"] - $pers->estado["quadro_y"];

                            $move_x = max(min($dist_x, 1), -1);
                            $move_y = max(min($dist_y, 1), -1);

                            $move_x = $pers->estado["quadro_x"] + $move_x;
                            $move_y = $pers->estado["quadro_y"] + $move_y;
                            $pers_no_caminho = $this->get_pers_in_cod($move_x, $move_y);

                            if (! $pers_no_caminho) {
                                $this->combate->mover($pers->estado["cod_pers"], $move_x . "_" . $move_y);
                                return;
                            }
                        }
                    }
                }
            }
        }
        if (! $this->has_inimigo_proximo()) {
            foreach ($this->tripulacao->personagens as $pers) {
                for ($x = $pers->estado["quadro_x"] - Tabuleiro::MAX_TABULEIRO_X; $x <= $pers->estado["quadro_x"] + Tabuleiro::MAX_TABULEIRO_X; $x++) {
                    for ($y = $pers->estado["quadro_y"] - Tabuleiro::MAX_TABULEIRO_Y; $y <= $pers->estado["quadro_y"] + Tabuleiro::MAX_TABULEIRO_Y; $y++) {
                        if ($x >= 0 && $x <= Tabuleiro::MAX_TABULEIRO_X && $y >= 0 && $y <= Tabuleiro::MAX_TABULEIRO_Y) {
                            $pers_in_coord = $this->get_pers_in_cod($x, $y);
                            if ($pers_in_coord && $pers_in_coord->estado["tripulacao_id"] != $pers->estado["tripulacao_id"]) {
                                $dist_x = $pers_in_coord->estado["quadro_x"] - $pers->estado["quadro_x"];
                                $dist_y = $pers_in_coord->estado["quadro_y"] - $pers->estado["quadro_y"];

                                $move_x = max(min($dist_x, 1), -1);
                                $move_y = max(min($dist_y, 1), -1);

                                $move_x = $pers->estado["quadro_x"] + $move_x;
                                $move_y = $pers->estado["quadro_y"] + $move_y;
                                $pers_no_caminho = $this->get_pers_in_cod($move_x, $move_y);

                                if (! $pers_no_caminho) {
                                    $this->combate->mover($pers->estado["cod_pers"], $move_x . "_" . $move_y);
                                    return;
                                }
                            }
                        }
                    }
                }
            }
        }

        $this->espalha();
    }

    public function ataca_inimigo_proximo()
    {
        $proximo = $this->get_inimigo_proximo();
        $pers = $proximo["pers"];
        $alvo = $proximo["inimigo"];
        $habilidade = $this->escolhe_habilidade($pers);

        $this->combate->atacar($pers->estado["cod_pers"], $habilidade->estado["cod"], $alvo->estado["quadro_x"] . "_" . $alvo->estado["quadro_y"]);
    }

    public function ataca_inimigo_distante()
    {
        $proximo = $this->get_inimigo_distante();
        if ($proximo) {
            $pers = $proximo["pers"];
            $alvo = $proximo["inimigo"];
            $habilidade = $this->escolhe_habilidade($pers);

            $this->combate->atacar($pers->estado["cod_pers"], $habilidade->estado["cod"], $alvo->estado["quadro_x"] . "_" . $alvo->estado["quadro_y"]);
        } else {
            $this->combate->passar_vez();
        }
    }

    public function get_inimigo_distante()
    {
        $reserva = null;
        foreach ($this->tripulacao->personagens as $pers) {
            $pers_in_coord = $this->get_inimigos_in_range($pers->estado["tripulacao_id"], $pers->estado["quadro_x"], $pers->estado["quadro_y"], 10);
            if (count($pers_in_coord)) {
                foreach ($pers_in_coord as $alvo) {
                    $informacao = array(
                        "pers" => $pers,
                        "inimigo" => $alvo
                    );
                    if ($alvo->estado["hp_max"] < $pers->estado["hp_max"] || $alvo->estado["hp"] / $alvo->estado["hp_max"] <= 0.3) {
                        return $informacao;
                    } else {
                        $reserva = $informacao;
                    }
                }
            }
        }
        return $reserva;
    }

    public function get_inimigos_in_range($tripulacao_id, $quadro_x, $quadro_y, $range)
    {
        $inimigos = [];
        $proximos = $this->get_personagens_range($quadro_x, $quadro_y, $range);
        foreach ($proximos as $proximo) {
            if ($proximo->estado["tripulacao_id"] != $tripulacao_id) {
                $inimigos[] = $proximo;
            }
        }
        return $inimigos;
    }

    public function get_personagens_range($quadro_x, $quadro_y, $range)
    {
        $personagens = [];

        for ($x = -1; $x <= 1; $x++) {
            for ($y = -1; $y <= 1; $y++) {
                $in_range = $this->get_personagem_direcao($quadro_x, $quadro_y, $range, $x, $y);
                if ($in_range) {
                    $personagens[] = $in_range;
                }
            }
        }

        return $personagens;
    }

    public function get_personagem_direcao($quadro_x, $quadro_y, $range, $inc_x, $inc_y)
    {
        for ($i = 1; $i <= $range; $i++) {
            $x = $quadro_x + $i * $inc_x;
            $y = $quadro_y + $i * $inc_y;
            if ($x >= 0 && $x <= 9 && $y >= 0 && $y <= 19 && ! ($x == $quadro_x && $y == $quadro_y)) {
                $pers_in_coord = $this->get_pers_in_cod($x, $y);
                if ($pers_in_coord) {
                    return $pers_in_coord;
                }
            }
        }
        return null;
    }

    public function calc_score_movimento($pers, $x, $y)
    {
        $proximo = $this->get_inimigo_proximo()["pers"];
        $increment_inimigo = $proximo->estado["id"] != $pers->estado["id"] && count($this->get_inimigos_proximos($pers->estado["tripulacao_id"], $x, $y)) ? 1 : 0;
        return $increment_inimigo - count($this->get_aliados_proximos($pers->estado["tripulacao_id"], $x, $y, 1));
    }

    public function get_aliados_proximos($tripulacao_id, $quadro_x, $quadro_y, $dist = 1)
    {
        $aliados = [];
        $proximos = $this->get_personagens_proximos($quadro_x, $quadro_y, $dist);
        foreach ($proximos as $proximo) {
            if ($proximo->estado["tripulacao_id"] == $tripulacao_id) {
                $aliados[] = $proximo;
            }
        }
        return $aliados;
    }

    public function escolhe_habilidade($pers)
    {
        $hablidades = array_filter($pers->get_habilidades(), function ($habilidade) {
            return $habilidade->estado["dano"] > 0;
        });
        return $hablidades[array_rand($hablidades)];
    }
}
