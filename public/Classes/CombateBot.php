<?php

class CombateBot {
	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var UserDetails
	 */
	private $userDetails;

	/**
	 * @var Combate
	 */
	private $combate;

	/**
	 * @var array
	 */
	private $tabuleiro;

	/**
	 * @var array
	 */
	private $bots;

	/**
	 * CombateBot constructor.
	 * @param $connection mywrap_con
	 * @param $userDetails UserDetails
	 * @param $combate Combate
	 */
	public function __construct($connection, $userDetails, $combate) {
		$this->connection = $connection;
		$this->userDetails = $userDetails;
		$this->combate = $combate;

		$this->tabuleiro = $combate->load_tabuleiro($userDetails->tripulacao["id"], null, $userDetails->combate_bot["id"]);
		$this->bots = get_pers_bot_in_combate($userDetails->combate_bot["id"]);

		$special_effects = get_special_effects_bot($userDetails->tripulacao["id"], $userDetails->combate_bot["id"]);

		foreach ($special_effects as $cod => $effects) {
			foreach ($effects as $effect) {
				foreach ($this->bots as $bot_index => $bot) {
					if ($cod == $bot["cod"] && $effect["special_effect"] == SPECIAL_EFFECT_MACHUCADO_JOELHO) {
						$this->bots[$bot_index]["machucado_joelho"] = true;
					}
				}
			}
		}
	}

	public function executa_acao() {
		$moves = $this->userDetails->combate_bot["move"];
		if ($moves > 0) {
			if ($this->has_inimigo_proximo_bot()) {
				$this->espalha_bots();
			} else {
				$this->aproxima_bot_inimigo();
			}
		} else {
			if ($this->has_inimigo_proximo_bot()) {
				$this->ataca_inimigo_proximo();
			} else {
				$this->ataca_inimigo_distante();
			}
		}
	}

	public function pos_turn() {
		$this->apply_sangramento();
		$this->apply_veneno();
	}

	public function apply_sangramento() {
		$sangrando = $this->connection->run(
			"SELECT * FROM tb_combate_special_effect e
			INNER JOIN tb_combate_personagens_bot p ON e.personagem_bot_id = p.id
			WHERE e.bot_id = ? AND e.special_effect = ? AND p.hp > 0",
			"ii", array($this->userDetails->combate_bot["id"], SPECIAL_EFFECT_SANGRAMENTO)
		)->fetch_all_array();

		foreach ($sangrando as $alvo) {
			$nhp = max(1, $alvo["hp"] - ceil($alvo["hp_max"] * 0.06));
			$this->connection->run("UPDATE tb_combate_personagens_bot SET hp = ? WHERE id = ?",
				"ii", array($nhp, $alvo["personagem_bot_id"]));
		}
	}

	public function apply_veneno() {

		$sangrando = $this->connection->run(
			"SELECT * FROM tb_combate_special_effect e
			INNER JOIN tb_combate_personagens_bot p ON e.personagem_bot_id = p.id
			WHERE e.bot_id = ? AND e.special_effect = ? AND p.hp > 0",
			"ii", array($this->userDetails->combate_bot["id"], SPECIAL_EFFECT_VENENO)
		)->fetch_all_array();

		foreach ($sangrando as $alvo) {
			$nhp = max(1, $alvo["hp"] - ceil($alvo["hp_max"] * 0.03));
			$this->connection->run("UPDATE tb_combate_personagens_bot SET hp = ? WHERE id = ?",
				"ii", array($nhp, $alvo["personagem_bot_id"]));
		}
	}

	function get_personagens_proximos($quadro_x, $quadro_y, $dist = 1) {
		$personagens = [];
		for ($x = $quadro_x - $dist; $x <= $quadro_x + $dist; $x++) {
			for ($y = $quadro_y - $dist; $y <= $quadro_y + $dist; $y++) {
				if (($x != $quadro_x || $y != $quadro_y) && $x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
					$pers_in_coord = $this->_get_pers_in_cod($x, $y);
					if ($pers_in_coord) {
						$personagens[] = $pers_in_coord;
					}
				}
			}
		}
		return $personagens;
	}

	function get_aliados_proximos($tripulacao_id, $quadro_x, $quadro_y, $dist = 1) {
		$aliados = [];
		$proximos = $this->get_personagens_proximos($quadro_x, $quadro_y, $dist);
		foreach ($proximos as $proximo) {
			if ($proximo["tripulacao_id"] == $tripulacao_id) {
				$aliados[] = $proximo;
			}
		}
		return $aliados;
	}

	function get_inimigos_proximos($tripulacao_id, $quadro_x, $quadro_y, $dist = 1) {
		$inimigos = [];
		$proximos = $this->get_personagens_proximos($quadro_x, $quadro_y, $dist);
		foreach ($proximos as $proximo) {
			if ($proximo["tripulacao_id"] != $tripulacao_id) {
				$inimigos[] = $proximo;
			}
		}
		return $inimigos;
	}

	function get_inimigo_proximo_bot() {
		$reserva = null;
		$max_rnk = -99999999999;
		foreach ($this->bots as $bot) {
			$pers_in_coord = $this->get_inimigos_proximos($bot["tripulacao_id"], $bot["quadro_x"], $bot["quadro_y"], 4);
			if (count($pers_in_coord)) {
				foreach ($pers_in_coord as $pers) {
					$informacao = array(
						"bot" => $bot,
						"inimigo" => $pers
					);
					$rnk = ($bot["atk"] - $pers["def"]) + ($pers["hp_max"] - $pers["hp"]) / 100;
					if ($rnk > $max_rnk) {
						$max_rnk = $rnk;
						$reserva = $informacao;
					}
				}
			}
		}
		return $reserva;
	}

	function has_inimigo_proximo_bot() {
		return $this->get_inimigo_proximo_bot();
	}

	private function _has_pers_in_cod($x, $y) {
		return isset($this->tabuleiro[$x]) && isset($this->tabuleiro[$x][$y]) && $this->tabuleiro[$x][$y]["hp"] > 0;
	}

	private function _get_pers_in_cod($x, $y) {
		return $this->_has_pers_in_cod($x, $y) ? $this->tabuleiro[$x][$y] : NULL;
	}

	function espalha_bots() {
		$movimentos = array();
		$proximo = $this->get_inimigo_proximo_bot()["bot"];

		foreach ($this->bots as $bot) {
			if (isset($bot["machucado_joelho"])) {
				continue;
			}
			$movimentos_bot = [];
			$colado = false;
			for ($x = $bot["quadro_x"] - 1; $x <= $bot["quadro_x"] + 1; $x++) {
				for ($y = $bot["quadro_y"] - 1; $y <= $bot["quadro_y"] + 1; $y++) {
					if (($x != $bot["quadro_x"] || $y != $bot["quadro_y"]) && $x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
						$pers_in_coord = $this->_get_pers_in_cod($x, $y);
						if (!$pers_in_coord) {
							$movimentos_bot[] = array(
								"bot" => $bot,
								"dist_min" => $this->calc_score_movimento($bot, $x, $y),
								"x" => $x,
								"y" => $y
							);
						} elseif ($pers_in_coord["tripulacao_id"] == $bot["id"]) {
							$colado = true;
						}
					}
				}
			}
			foreach ($movimentos_bot as $movimento) {
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

		$movimento_index = array_rand($movimentos_maior_dist_min);
		if (!$movimento_index) {
			// encerra os movimentos
			$this->connection->run("UPDATE tb_combate_bot SET move = 0 WHERE id = ?",
				"i", array($this->userDetails->combate_bot["id"]));
			return;
		}

		$movimento = $movimentos_maior_dist_min[$movimento_index];

		if ($movimento["bot"]["bot_id"] == $proximo["bot_id"]) {
			$this->ataca_inimigo_proximo();
		} else {
			$this->movimenta_bot($movimento["bot"]["bot_id"], $movimento["x"], $movimento["y"]);
		}
	}

	function calc_score_movimento($bot, $x, $y) {
		$proximo = $this->get_inimigo_proximo_bot()["bot"];
		$increment_inimigo = $proximo["id"] != $bot["id"] && count($this->get_inimigos_proximos($bot["tripulacao_id"], $x, $y)) ? 1 : 0;
		return $increment_inimigo - count($this->get_aliados_proximos($bot["tripulacao_id"], $x, $y, 1));
	}

	function movimenta_bot($bot_d, $x, $y) {
		$this->connection->run("UPDATE tb_combate_personagens_bot SET quadro_x = ?, quadro_y = ? WHERE id = ?",
			"iii", array($x, $y, $bot_d));

		$this->connection->run("UPDATE tb_combate_bot SET move = move - 1 WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]));
	}

	function aproxima_bot_inimigo() {
		$moves = $this->userDetails->combate_bot["move"];

		foreach ($this->bots as $bot) {
			if (isset($bot["machucado_joelho"])) {
				continue;
			}
			for ($x = $bot["quadro_x"] - $moves; $x <= $bot["quadro_x"] + $moves; $x++) {
				for ($y = $bot["quadro_y"] - $moves; $y <= $bot["quadro_y"] + $moves; $y++) {
					if ($x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
						$pers_in_coord = $this->_get_pers_in_cod($x, $y);
						if ($pers_in_coord && $pers_in_coord["tripulacao_id"] != $bot["tripulacao_id"]) {
							$dist_x = $pers_in_coord["quadro_x"] - $bot["quadro_x"];
							$dist_y = $pers_in_coord["quadro_y"] - $bot["quadro_y"];

							$move_x = $dist_x > 0 ? 1 : ($dist_x == 0 ? 0 : -1);
							$move_y = $dist_y > 0 ? 1 : ($dist_y == 0 ? 0 : -1);

							$move_x = $bot["quadro_x"] + $move_x;
							$move_y = $bot["quadro_y"] + $move_y;
							$pers_no_caminho = $this->_get_pers_in_cod($move_x, $move_y);

							if (!$pers_no_caminho) {
								$this->movimenta_bot($bot["bot_id"], $move_x, $move_y);
								return;
							}
						}
					}
				}
			}
		}
		if (!$this->has_inimigo_proximo_bot()) {
			foreach ($this->bots as $bot) {
				if (isset($bot["machucado_joelho"])) {
					continue;
				}
				for ($x = $bot["quadro_x"] - 9; $x <= $bot["quadro_x"] + 9; $x++) {
					for ($y = $bot["quadro_y"] - 19; $y <= $bot["quadro_y"] + 19; $y++) {
						if ($x >= 0 && $x <= 9 && $y >= 0 && $y <= 19) {
							$pers_in_coord = $this->_get_pers_in_cod($x, $y);
							if ($pers_in_coord && $pers_in_coord["tripulacao_id"] != $bot["tripulacao_id"]) {
								$dist_x = $pers_in_coord["quadro_x"] - $bot["quadro_x"];
								$dist_y = $pers_in_coord["quadro_y"] - $bot["quadro_y"];

								$move_x = $dist_x > 0 ? 1 : ($dist_x == 0 ? 0 : -1);
								$move_y = $dist_y > 0 ? 1 : ($dist_y == 0 ? 0 : -1);

								$move_x = $bot["quadro_x"] + $move_x;
								$move_y = $bot["quadro_y"] + $move_y;
								$pers_no_caminho = $this->_get_pers_in_cod($move_x, $move_y);

								if (!$pers_no_caminho) {
									$this->movimenta_bot($bot["bot_id"], $move_x, $move_y);
									return;
								}
							}
						}
					}
				}
			}
		}

		$this->espalha_bots();
	}

	function get_personagem_direcao($quadro_x, $quadro_y, $range, $inc_x, $inc_y) {
		for ($i = 1; $i <= $range; $i++) {
			$x = $quadro_x + $i * $inc_x;
			$y = $quadro_y + $i * $inc_y;
			if ($x >= 0 && $x <= 9 && $y >= 0 && $y <= 19 && !($x == $quadro_x && $y == $quadro_y)) {
				$pers_in_coord = $this->_get_pers_in_cod($x, $y);
				if ($pers_in_coord) {
					return $pers_in_coord;
				}
			}
		}
		return null;
	}

	function get_personagens_range($quadro_x, $quadro_y, $range) {
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

	function get_inimigos_in_range($tripulacao_id, $quadro_x, $quadro_y, $range) {
		$inimigos = [];
		$proximos = $this->get_personagens_range($quadro_x, $quadro_y, $range);
		foreach ($proximos as $proximo) {
			if ($proximo["tripulacao_id"] != $tripulacao_id) {
				$inimigos[] = $proximo;
			}
		}
		return $inimigos;
	}

	function get_inimigo_distante() {
		$reserva = null;
		foreach ($this->bots as $bot) {
			$pers_in_coord = $this->get_inimigos_in_range($bot["tripulacao_id"], $bot["quadro_x"], $bot["quadro_y"], 10);
			if (count($pers_in_coord)) {
				foreach ($pers_in_coord as $pers) {
					$informacao = array(
						"bot" => $bot,
						"inimigo" => $pers
					);
					if ($pers["hp_max"] < $bot["hp_max"] || $pers["hp"] / $pers["hp_max"] <= 0.3) {
						return $informacao;
					} else {
						$reserva = $informacao;
					}
				}
			}
		}
		return $reserva;
	}

	function ataca_inimigo_distante() {
		$proximo = $this->get_inimigo_distante();
		if ($proximo) {
			$bot = $proximo["bot"];
			$alvo = $proximo["inimigo"];

			$this->ataca_inimigo($bot, $alvo);
		} else {
			$this->passa_vez();
		}
	}

	function ataca_inimigo_proximo() {
		$proximo = $this->get_inimigo_proximo_bot();
		$bot = $proximo["bot"];
		$alvo = $proximo["inimigo"];

		$this->ataca_inimigo($bot, $alvo);
	}

	function ataca_inimigo($bot, $alvo) {

		$habilidade = $this->get_habilidade($bot["mp"], $alvo, $bot["pack_habilidade_id"]);

		$this->pre_turn($bot, $habilidade);

		$relatorio["tipo"] = 1;
		$relatorio["nome"] = $bot["nome"];
		$relatorio["cod"] = $bot["cod"];
		$relatorio["img"] = $bot["img"];
		$relatorio["skin_r"] = $bot["skin_r"];
		$relatorio["nome_skil"] = $habilidade["nome"];
		$relatorio["img_skil"] = $habilidade["img"];
		$relatorio["descricao_skil"] = $habilidade["descricao"];
		$relatorio["effect"] = $this->combate->get_effect_random();
		$relatorio_afetado = array();

		$quadro_x = $alvo["quadro_x"];
		$quadro_y = $alvo["quadro_y"];
		$atingidos = array();
		for ($x = 0; $x < $habilidade["area"]; $x++) {
			if ($x == 0) {
				$relatorio_afetado[$x] = $this->combate->ataca_quadro($bot, $habilidade, $habilidade["tipo"], $alvo);
				$atingidos[] = $alvo["cod"];
				$relatorio_afetado[$x]["quadro"] = $quadro_x . "_" . $quadro_y;
			} else {
				$alvos = $this->get_inimigos_proximos($bot["tripulacao_id"], $quadro_x, $quadro_y);
				$atingido = false;
				foreach ($alvos as $alvo) {
					if ($alvo && !in_array($alvo["cod"], $atingidos)) {
						$relatorio_afetado[$x] = $this->combate->ataca_quadro($bot, $habilidade, $habilidade["tipo"], $alvo);
						$atingidos[] = $alvo["cod"];
						$quadro_x = $alvo["quadro_x"];
						$quadro_y = $alvo["quadro_y"];
						$relatorio_afetado[$x]["quadro"] = $quadro_x . "_" . $quadro_y;
						$atingido = true;
					}
				}
				if (!$atingido) {
					if ($quadro_x == 0) {
						$inc_x = rand(0, 1);
					} else if ($quadro_x == 9) {
						$inc_x = rand(-1, 0);
					} else {
						$inc_x = rand(-1, 1);
					}
					if ($quadro_y == 0) {
						$inc_y = rand(0, 1);
					} else if ($quadro_y == 19) {
						$inc_y = rand(-1, 0);
					} else {
						$inc_y = rand(-1, 1);
					}

					if ($inc_x == 0 && $inc_y == 0) {
						if ($quadro_x == 0) {
							$inc_x = 1;
						} else {
							$inc_x = -1;
						}
					}

					$quadro_x += $inc_x;
					$quadro_y += $inc_y;
				}
			}
		}

		$relatorio["afetados"] = $relatorio_afetado;
		$relatorio["id"] = atual_segundo();

		$this->pos_turn();

		$this->combate->logger->registra_turno_combate_bot($relatorio);
	}

	function get_habilidade($mp_max, $alvo, $pack_habilidade_id = null) {
		$soco = array(
			"dano" => 20,
			"consumo" => 0,
			"area" => 1,
			"nome" => "Soco",
			"descricao" => "Tenta acerta um soco no oponente.",
			"img" => 1,
			"tipo" => TIPO_SKILL_ATAQUE_CLASSE
		);

		$habilidades = $pack_habilidade_id
			? DataLoader::load("habilidades_bots")[$pack_habilidade_id]
			: array(
				$soco, array(
					"dano" => 40,
					"consumo" => 15,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 50,
					"consumo" => 20,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 60,
					"consumo" => 25,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 70,
					"consumo" => 30,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 50,
					"consumo" => 40,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 40,
					"consumo" => 50,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 20,
					"consumo" => 20,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 20,
					"consumo" => 20,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				), array(
					"dano" => 20,
					"consumo" => 20,
					"area" => 4,
					"tipo" => TIPO_SKILL_ATAQUE_CLASSE
				)
			);

		if (!$pack_habilidade_id) {
			$tipo = rand(1, 6) <= 5 ? TIPO_SKILL_ATAQUE_CLASSE : TIPO_SKILL_BUFF_CLASSE;
		} else {
			$tipo = TIPO_SKILL_ATAQUE_CLASSE;
		}
		if ($tipo == TIPO_SKILL_ATAQUE_CLASSE) {
			$habilidades_tipo = $habilidades;

			$habilidades_possiveis = array();

			foreach ($habilidades_tipo as $habilidade) {
				if ($habilidade["consumo"] < $mp_max) {
					$habilidades_possiveis[] = $habilidade;
				}
			}
			if (!count($habilidades_possiveis)) {
				$habilidades_possiveis = array(
					$soco
				);
			}

			$habilidade_random_index = array_rand($habilidades_possiveis);

			$habilidade = $habilidades_possiveis[$habilidade_random_index];
		} else {
			$maior_atributo = array(1, 1);
			$maior_atributo_quant = array(0, 0);

			for ($i = 1; $i <= 7; $i++) {
				if ($alvo[nome_atributo_tabela($i)] > $maior_atributo_quant[0]) {
					$maior_atributo_quant[1] = $maior_atributo_quant[0];
					$maior_atributo[1] = $maior_atributo[0];
					$maior_atributo[0] = $i;
					$maior_atributo_quant[0] = $alvo[nome_atributo_tabela($i)];
				}
			}

			$atributo = rand(0, 1);

			$habilidade = array(
				"bonus_atr" => $maior_atributo[$atributo],
				"bonus_atr_qnt" => array(-110, -60),
				"duracao" => array(3, 6),
				"area" => 1,
				"consumo" => 10,
				"tipo" => TIPO_SKILL_BUFF_CLASSE
			);
		}

		if (!isset($habilidade["nome"])) {
			$descricao = habilidade_random();
			$habilidade["nome"] = $descricao["nome"];
			$habilidade["descricao"] = $descricao["descricao"];
		}

		if (!isset($habilidade["img"])) {
			$habilidade["img"] = rand(1, SKILLS_ICONS_MAX);
		}

		if ($habilidade["tipo"] == TIPO_SKILL_BUFF_CLASSE) {
			if (is_array($habilidade["bonus_atr_qnt"])) {
				$habilidade["bonus_atr_qnt"] = rand($habilidade["bonus_atr_qnt"][0], $habilidade["bonus_atr_qnt"][1]);
			}
			if (is_array($habilidade["duracao"])) {
				$habilidade["duracao"] = rand($habilidade["duracao"][0], $habilidade["duracao"][1]);
			}
		}

		return $habilidade;
	}

	function passa_vez() {
		$this->pre_turn();
	}

	function pre_turn(&$bot = null, $habilidade = null) {
		if ($bot) {
			$this->aplica_buffs($bot);
		}

		$this->remove_buffs();

		$this->remove_special_effects();

		if ($bot && $habilidade) {
			$this->reduz_mp($bot, $habilidade["consumo"]);
		}

		$this->regen_mp();

		$this->muda_vez();
	}

	public function remove_special_effects() {
		$this->connection->run("UPDATE tb_combate_special_effect SET duracao = duracao - 1 WHERE bot_id = ?",
			"i", $this->userDetails->combate_bot["id"]);

		$this->connection->run("DELETE FROM tb_combate_special_effect WHERE duracao <= 0 AND bot_id = ?",
			"i", $this->userDetails->combate_bot["id"]);
	}

	public function aplica_buffs(&$bot) {
		$buffs = $this->connection->run("SELECT * FROM tb_combate_buff_bot WHERE cod = ?",
			"i", $bot["bot_id"])->fetch_all_array();

		foreach ($buffs as $buff) {
			$atr = nome_atributo_tabela($buff["atr"]);
			$bot[$atr] += $buff["efeito"];
		}

		for ($i = 1; $i <= 8; $i++) {
			$atr = nome_atributo_tabela($i);
			$bot[$atr] = max(1, $bot[$atr]);
		}
	}

	public function remove_buffs() {
		$this->connection->run("UPDATE tb_combate_buff_bot SET espera = espera - 1 WHERE id = ?",
			"i", $this->userDetails->combate_bot["id"]);

		$this->connection->run("DELETE FROM tb_combate_buff_bot WHERE espera <= 0 AND id = ?",
			"i", $this->userDetails->combate_bot["id"]);
	}

	public function regen_mp() {
		$this->connection->run("UPDATE tb_combate_personagens_bot SET mp = mp + CEIL(mp * 0.02) WHERE combate_bot_id = ?",
			"i", $this->userDetails->combate_bot["id"]);
		$this->connection->run("UPDATE tb_combate_personagens_bot SET mp = mp_max WHERE mp > mp_max AND combate_bot_id = ?",
			"i", $this->userDetails->combate_bot["id"]);
		$this->connection->run("UPDATE tb_combate_personagens_bot SET mp = 1 WHERE mp = 0 AND combate_bot_id = ?",
			"i", $this->userDetails->combate_bot["id"]);
	}

	public function reduz_mp($personagem, $quant) {
		$this->connection->run("UPDATE tb_combate_personagens_bot SET mp = mp - ? WHERE id = ?",
			"ii", array($quant, $personagem["bot_id"]));
	}

	function muda_vez() {
		$this->connection->run("UPDATE tb_combate_bot SET vez = 1, move = 5 WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]));
	}

}