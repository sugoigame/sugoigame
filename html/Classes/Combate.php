<?php
class Combate {
	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var UserDetails
	 */
	private $userDetails;

	/**
	 * @var Protector
	 */
	private $protector;

	/**
	 * @var CombateLogger
	 */
	public $logger;

	/**
	 * Combate constructor.
	 * @param $connection mywrap_con
	 * @param $userDetails UserDetails
	 * @param $protector Protector
	 */
	public function __construct($connection, $userDetails, $protector) {
		$this->connection = $connection;
		$this->userDetails = $userDetails;
		$this->protector = $protector;
		$this->logger = new CombateLogger($connection, $userDetails);
	}

	public function get_my_id_index_in_pvp() {
		return $this->userDetails->tripulacao["id"] == $this->userDetails->combate_pvp["id_1"] ? 1 : 2;
	}

	public function get_enemy_id_index_in_pvp() {
		return $this->userDetails->tripulacao["id"] == $this->userDetails->combate_pvp["id_1"] ? 2 : 1;
	}

	public function pre_turn(&$personagem_combate = null, $habilidade = null, $cod_skil = null, $tipo_skil = null) {
		if ($personagem_combate) {
			$this->aplica_buffs($personagem_combate);
			$this->connection->run("UPDATE tb_personagens SET maestria = maestria + 1 WHERE cod = ?",
				"i", array($personagem_combate["cod"]));
		}

		$this->remove_buffs();

		$this->remove_special_effects();

		$this->remove_espera();

		if ($personagem_combate && $habilidade && $cod_skil && $tipo_skil) {
			$this->insert_espera($personagem_combate, $cod_skil, $tipo_skil, $habilidade["espera"]);

			$this->reduz_mp($personagem_combate, $habilidade["consumo"]);

			$this->aumenta_xp_profissao($personagem_combate, $tipo_skil);
		}

		$this->regen_mp();

		if ($this->userDetails->combate_pvp) {
			$this->muda_vez_pvp();
			if ($personagem_combate) {
				$this->aumenta_xp($personagem_combate);
			}
		} else if ($this->userDetails->combate_pve) {
			$this->muda_vez_pve();
		} else if ($this->userDetails->combate_bot) {
			$this->muda_vez_bot();
		}

		if ($this->has_special_effect($habilidade)) {
			if ($habilidade["special_target"] == SPECIAL_TARGET_SELF) {
				if ($habilidade["special_apply_type"] == SPECIAL_APPLY_TYPE_REMOVE) {
					$this->remove_special_effect($personagem_combate, $habilidade);
					$this->aplica_imunidade_special_effect($personagem_combate, $habilidade);
				} else {
					$this->apply_special_effect($personagem_combate, $habilidade);
				}
			}
		}
	}

	public function pos_turn() {
		$this->apply_sangramento();
		$this->apply_veneno();
		$this->aplly_fadiga();
	}

	public function aplly_fadiga() {
		if (!$this->userDetails->combate_pvp) {
			return;
		}
		$personagens = get_pers_in_combate($this->userDetails->combate_pvp["id_" . $this->get_enemy_id_index_in_pvp()]);

		if (fadiga_batalha_ativa($personagens)) {
			foreach ($personagens as $pers) {
				if (is_tanque($pers)) {
					$new_hp = max(1, $pers["hp"] - 200);
					$this->connection->run("UPDATE tb_combate_personagens SET hp = ? WHERE cod = ?",
						"ii", array($new_hp, $pers["cod"]));
				}
			}
		}
	}

	public function apply_sangramento($id = null) {
		if (!$id) {
			$id = $this->userDetails->tripulacao["id"];
		}

		$sangrando = $this->connection->run(
			"SELECT * FROM tb_combate_special_effect e
			INNER JOIN tb_combate_personagens p ON e.personagem_id = p.cod
			WHERE e.tripulacao_id = ? AND e.special_effect = ? AND p.hp > 0",
			"ii", array($id, SPECIAL_EFFECT_SANGRAMENTO)
		)->fetch_all_array();

		foreach ($sangrando as $alvo) {
			$nhp = max(1, $alvo["hp"] - ceil($alvo["hp_max"] * 0.06));
			$this->connection->run("UPDATE tb_combate_personagens SET hp = ? WHERE cod = ?",
				"ii", array($nhp, $alvo["cod"]));
		}
	}

	public function apply_veneno($id = null) {
		if (!$id) {
			$id = $this->userDetails->tripulacao["id"];
		}

		$sangrando = $this->connection->run(
			"SELECT * FROM tb_combate_special_effect e
			INNER JOIN tb_combate_personagens p ON e.personagem_id = p.cod
			WHERE e.tripulacao_id = ? AND e.special_effect = ? AND p.hp > 0",
			"ii", array($id, SPECIAL_EFFECT_VENENO)
		)->fetch_all_array();

		foreach ($sangrando as $alvo) {
			$nhp = max(1, $alvo["hp"] - ceil($alvo["hp_max"] * 0.03));
			$this->connection->run("UPDATE tb_combate_personagens SET hp = ? WHERE cod = ?",
				"ii", array($nhp, $alvo["cod"]));
		}
	}

	public function remove_special_effect($personagem_combate, $habilidade) {
		if ($personagem_combate["id"] == "bot") {
			$this->connection->run("DELETE FROM tb_combate_special_effect WHERE personagem_bot_id = ? AND special_effect = ?",
				"ii", array($personagem_combate["bot_id"], -$habilidade["special_effect"]));
			$this->connection->run("DELETE FROM tb_combate_special_effect WHERE personagem_bot_id = ? AND special_effect = ? ORDER BY duracao DESC LIMIT 1",
				"ii", array($personagem_combate["bot_id"], $habilidade["special_effect"]));
		} else {
			$this->connection->run("DELETE FROM tb_combate_special_effect WHERE personagem_id = ? AND special_effect = ?",
				"ii", array($personagem_combate["cod"], -$habilidade["special_effect"]));
			$this->connection->run("DELETE FROM tb_combate_special_effect WHERE personagem_id = ? AND special_effect = ? ORDER BY duracao DESC LIMIT 1",
				"ii", array($personagem_combate["cod"], $habilidade["special_effect"]));
		}
	}

	public function aplica_imunidade_special_effect($personagem_combate, $habilidade) {
		if ($personagem_combate["id"] == "bot") {
			$this->connection->run("INSERT INTO tb_combate_special_effect (bot_id, personagem_bot_id, special_effect, duracao) VALUE (?,?,?,?)",
				"iiii", array($this->userDetails->combate_bot["id"], $personagem_combate["bot_id"], -$habilidade["special_effect"], 1));
		} else {
			$this->connection->run("INSERT INTO tb_combate_special_effect (combate_id, tripulacao_id, personagem_id, special_effect, duracao) VALUE (?,?,?,?,?)",
				"iiiii", array($this->userDetails->combate_pvp["combate"], $this->userDetails->tripulacao["id"], $personagem_combate["cod"], -$habilidade["special_effect"], 1));
		}
	}

	public function has_special_effect($habilidade) {
		return ($this->userDetails->combate_pvp || $this->userDetails->combate_bot)
			&& isset($habilidade["special_effect"])
			&& $habilidade["special_effect"];
	}

	public function has_obstaculo() {
		return $this->userDetails->combate_pvp;
	}

	public function vale_fa($alvo) {
		return $this->userDetails->combate_pvp
			&& $this->userDetails->combate_pvp["tipo"] != TIPO_AMIGAVEL
			&& $this->userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL
			&& !isset($alvo["obstaculo"]);
	}

	public function vale_score() {
		return $this->userDetails->combate_pvp
			&& $this->userDetails->combate_pvp["tipo"] != TIPO_AMIGAVEL
			&& $this->userDetails->combate_pvp["tipo"] != TIPO_COLISEU
			&& $this->userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL;
	}

	public function ataca_quadro(&$personagem_combate, $habilidade, $tipo_skil, &$alvo) {
		$relatorio_afetado = array();
		$relatorio_afetado["acerto"] = "1";

		if (!isset($alvo["obstaculo"])) {
			$this->aplica_buffs($alvo);
		}

		$relatorio_afetado["id"] = $alvo["tripulacao_id"];
		$relatorio_afetado["cod"] = $alvo["cod"];
		$relatorio_afetado["nome"] = $alvo["nome"];
		$relatorio_afetado["img"] = $alvo["img"];
		$relatorio_afetado["skin_r"] = $alvo["skin_r"];

		if (!isset($alvo["obstaculo"]) && $this->has_special_effect($habilidade)) {
			if ($habilidade["special_target"] == SPECIAL_TARGET_TARGET) {
				if ($habilidade["special_apply_type"] == SPECIAL_APPLY_TYPE_REMOVE) {
					$this->remove_special_effect($alvo, $habilidade);
					$this->aplica_imunidade_special_effect($alvo, $habilidade);
				} else if ($habilidade["special_effect"] == SPECIAL_EFFECT_PONTO_FRACO) {
					$this->apply_special_effect($alvo, $habilidade);
				}
			}
		}

		if ($tipo_skil == 1 OR $tipo_skil == 4 OR $tipo_skil == 7) {
			$skil_dano = ($habilidade["dano"] * 10);
			$resultado = calc_dano($personagem_combate, $alvo, $skil_dano);

			$relatorio_afetado["resultado"] = $resultado;

			$relatorio_afetado["tipo"] = 0;
			if ($resultado["esquivou"]) {
				$relatorio_afetado["esq"] = 1;
				if ($this->vale_fa($alvo)) {
					$this->aumenta_fa_esq_bloq($alvo, $personagem_combate);
				}
			} else {
				if (!isset($alvo["obstaculo"]) && $this->has_special_effect($habilidade)) {
					if ($habilidade["special_target"] == SPECIAL_TARGET_TARGET) {
						if ($habilidade["special_apply_type"] == SPECIAL_APPLY_TYPE_APPLY
							&& $habilidade["special_effect"] != SPECIAL_EFFECT_PONTO_FRACO
							&& !$resultado["bloqueou"]
						) {
							$this->apply_special_effect($alvo, $habilidade);
						}
					}
				}

				if ($resultado["chance_esquiva"] - $alvo["haki_esq"] < 40 && $this->vale_fa($alvo)) {
					$this->aumenta_fa_acerto_sem_agl($personagem_combate, $alvo);
				}
				if ($resultado["bloqueou"] && $this->vale_fa($alvo)) {
					$this->aumenta_fa_esq_bloq($alvo, $personagem_combate);
				}
				if (!$resultado["bloqueou"] && $resultado["chance_bloqueio"] - $alvo["haki_cri"] < 40 && $this->vale_fa($alvo)) {
					$this->aumenta_fa_acerto_sem_agl($personagem_combate, $alvo);
				}
				if ($resultado["critou"] && $this->vale_fa($alvo)) {
					$this->aumenta_fa_crit($personagem_combate, $alvo);
				}
				if (!$resultado["critou"] && $resultado["chance_critico"] - $personagem_combate["haki_cri"] < 40 && $this->vale_fa($alvo)) {
					$this->aumenta_fa_erro_crit($alvo, $personagem_combate);
				}

				$mod_akuma = $this->get_mod_akuma($personagem_combate, $alvo);

				$dano = (int)($resultado["dano"] * $mod_akuma);

				if ($this->vale_fa($alvo)) {
					$this->aumenta_fa_dano($dano, $personagem_combate, $alvo);
					$this->aumenta_fa_absorv(($personagem_combate["atk"] > $alvo["def"] ? $alvo["def"] : $personagem_combate["atk"]) * 10, $alvo, $personagem_combate);
				}

				//novo hp do alvo
				$nhp = max(0, $alvo["hp"] - $dano);

				if (!isset($alvo["obstaculo"])) {
					if ($alvo["id"] == "bot") {
						$this->connection->run("UPDATE tb_combate_personagens_bot SET hp = ? WHERE id = ?",
							"ii", array($nhp, $alvo["bot_id"]));
					} else {
						$this->connection->run("UPDATE tb_combate_personagens SET hp = ? WHERE cod = ?",
							"ii", array($nhp, $alvo["cod"]));
					}
					if ($nhp <= 0) {
						if ($this->vale_score()) {
							$this->modifica_score($personagem_combate, $alvo);
						}
						if ($this->userDetails->combate_pvp) {
							if ($this->userDetails->combate_pvp["tipo"] != TIPO_AMIGAVEL
								&& $this->userDetails->combate_pvp["tipo"] != TIPO_LOCALIZADOR_CASUAL
								&& $this->userDetails->combate_pvp["tipo"] != TIPO_COLISEU
							) {
								$derrotados_adversario = $this->connection->run(
									"SELECT count(*) AS total FROM tb_combate_personagens WHERE id = ? AND hp <= 0",
									"i", array($alvo["id"])
								)->fetch_array()["total"];
								$this->connection->run("UPDATE tb_usuarios SET battle_points = battle_points + ? WHERE id = ?",
									"ii", array(27 - $derrotados_adversario, $this->userDetails->tripulacao["id"]));
							}

							$this->log_derrotado($alvo);
							// $this->userDetails->add_item(205, TIPO_ITEM_REAGENT, 1);
						}
					}
				} else {
					if ($nhp <= 0) {
						$this->connection->run("DELETE FROM tb_obstaculos WHERE id = ?",
							"i", array($alvo["obstaculo"]));
					} else {
						$this->connection->run("UPDATE tb_obstaculos SET hp = ? WHERE id = ?",
							"ii", array($nhp, $alvo["obstaculo"]));
					}
				}

				$relatorio_afetado["esq"] = 0;

				$relatorio_afetado["bloq"] = $resultado["bloqueou"] ? 1 : 0;
				$relatorio_afetado["cri"] = $resultado["critou"] ? 1 : 0;

				$relatorio_afetado["efeito"] = $dano;
				$relatorio_afetado["derrotado"] = $nhp ? 0 : 1;
			}
		} else if ($tipo_skil == 2 OR $tipo_skil == 5 OR $tipo_skil == 8) {
			if (!isset($alvo["obstaculo"])) {
				if ($alvo["id"] == "bot") {
					$this->connection->run("INSERT INTO tb_combate_buff_bot (id, cod, cod_buff, atr, efeito, espera) VALUES (?, ?, ?, ?, ?, ?)",
						"iiiiii", array(
							$this->userDetails->combate_bot["id"],
							$alvo["bot_id"],
							$personagem_combate["cod"],
							$habilidade["bonus_atr"],
							$habilidade["bonus_atr_qnt"],
							$habilidade["duracao"]));
				} else {
					$this->connection->run("INSERT INTO tb_combate_buff (id, cod, cod_buff, atr, efeito, espera) VALUES (?, ?, ?, ?, ?, ?)",
						"iiiiii", array(
							$alvo["id"],
							$alvo["cod"],
							$personagem_combate["cod"],
							$habilidade["bonus_atr"],
							$habilidade["bonus_atr_qnt"],
							$habilidade["duracao"]));
				}

				$relatorio_afetado["tipo"] = 1;
				$relatorio_afetado["efeito"] = $habilidade["bonus_atr_qnt"];
				$relatorio_afetado["atributo"] = $habilidade["bonus_atr"];
			} else {
				$relatorio_afetado["tipo"] = 1;
				$relatorio_afetado["efeito"] = 0;
				$relatorio_afetado["atributo"] = $habilidade["bonus_atr"];
			}
		} else if ($tipo_skil == 10) {
			if (!isset($alvo["obstaculo"])) {
				$hp_recuperado = $habilidade["hp_recuperado"] * 10;
				$alvo["hp"] = min($alvo["hp_max"], $alvo["hp"] + $hp_recuperado);

				$mp_recuperado = $habilidade["mp_recuperado"];
				$alvo["mp"] = min($alvo["mp_max"], $alvo["mp"] + $mp_recuperado);

				$relatorio_afetado["tipo"] = 2;
				$relatorio_afetado["cura_h"] = $hp_recuperado;
				$relatorio_afetado["cura_m"] = $mp_recuperado;

				if ($alvo["id"] == "bot") {
					$this->connection->run("UPDATE tb_combate_personagens_bot SET hp = ?, mp = ? WHERE id = ?",
						"iii", array($alvo["hp"], $alvo["mp"], $alvo["bot_id"]));
				} else {
					$this->connection->run("UPDATE tb_combate_personagens SET hp = ?, mp = ? WHERE cod = ?",
						"iii", array($alvo["hp"], $alvo["mp"], $alvo["cod"]));
				}
				$this->userDetails->reduz_item($habilidade["cod_remedio"], TIPO_ITEM_REMEDIO, 1);
			} else {
				$relatorio_afetado["tipo"] = 2;
				$relatorio_afetado["cura_h"] = 0;
				$relatorio_afetado["cura_m"] = 0;
			}
		}

		return $relatorio_afetado;
	}

	public function log_derrotado($pers) {
		if ($this->userDetails->combate_pvp) {
			$this->connection->run("INSERT INTO tb_combate_log_personagem_morto (combate, tripulacao_id, personagem_id) VALUE (?,?,?)",
				"iii", array($this->userDetails->combate_pvp["combate"], $pers["id"], $pers["cod"]));
		}
	}

	public function apply_special_effect(&$alvo, $habilidade) {
		if ($alvo["id"] == "bot") {
			$imune = $this->connection->run("SELECT * FROM tb_combate_special_effect WHERE personagem_bot_id = ? AND special_effect = ?",
				"ii", array($alvo["bot_id"], -$habilidade["special_effect"]));
			$ativo = $this->connection->run("SELECT * FROM tb_combate_special_effect WHERE personagem_bot_id = ? AND special_effect = ?",
				"ii", array($alvo["bot_id"], $habilidade["special_effect"]));
			if (!$imune->count() && $ativo->count() < 2) {
				if ($habilidade["special_effect"] == SPECIAL_EFFECT_PONTO_FRACO) {
					$alvo["def"] = ceil($alvo["def"] * 0.5);
				} else {
					$this->connection->run("INSERT INTO tb_combate_special_effect (bot_id, personagem_bot_id, special_effect, duracao) VALUE (?,?,?,?)",
						"iiii", array($this->userDetails->combate_bot["id"], $alvo["bot_id"], $habilidade["special_effect"], duracao_special_effect($habilidade["special_effect"])));
				}
			}
		} else {
			$imune = $this->connection->run("SELECT * FROM tb_combate_special_effect WHERE personagem_id = ? AND special_effect = ?",
				"ii", array($alvo["cod"], -$habilidade["special_effect"]));
			$ativo = $this->connection->run("SELECT * FROM tb_combate_special_effect WHERE personagem_id = ? AND special_effect = ?",
				"ii", array($alvo["cod"], $habilidade["special_effect"]));
			if (!$imune->count() && $ativo->count() < 2) {
				if ($habilidade["special_effect"] == SPECIAL_EFFECT_PONTO_FRACO) {
					$alvo["def"] = ceil($alvo["def"] * 0.5);
				} else {
					$this->connection->run("INSERT INTO tb_combate_special_effect (combate_id, tripulacao_id, personagem_id, special_effect, duracao) VALUE (?,?,?,?,?)",
						"iiiii", array($this->userDetails->combate_pvp["combate"], $alvo["id"], $alvo["cod"], $habilidade["special_effect"], duracao_special_effect($habilidade["special_effect"])));
				}
			}
		}
	}

	public function modifica_score(&$personagem_combate, &$alvo) {
		aumenta_score($personagem_combate);

		reduz_score($alvo);
	}

	public function aumenta_fa_esq_bloq(&$alvo, &$personagem_combate) {
		$this->aumenta_fa($personagem_combate["lvl"] * 1000, $alvo, $personagem_combate);
	}

	public function aumenta_fa_crit(&$personagem_combate, &$alvo) {
		$this->aumenta_fa($alvo["lvl"] * 1000, $personagem_combate, $alvo);
	}

	public function aumenta_fa_acerto_sem_agl(&$personagem_combate, &$alvo) {
		$this->aumenta_fa($alvo["lvl"] * 200, $personagem_combate, $alvo);
	}

	public function aumenta_fa_erro_crit(&$alvo, &$personagem_combate) {
		$this->aumenta_fa($personagem_combate["lvl"] * 200, $alvo, $personagem_combate);
	}

	public function aumenta_fa_dano($dano, &$personagem_combate, &$alvo) {
		$fa_ganha = floor($dano / 1000) * 100000;
		if ($fa_ganha > 0) {
			$this->aumenta_fa($fa_ganha, $personagem_combate, $alvo);
		}
	}

	public function aumenta_fa_absorv($absorv, &$alvo, &$personagem_combate) {
		$fa_ganha = floor($absorv / 1000) * 70000;
		if ($fa_ganha > 0) {
			$this->aumenta_fa($fa_ganha, $alvo, $personagem_combate);
		}
	}

	public function aumenta_fa($fa_ganha, &$personagem_combate, &$alvo) {
		$max_fa = $this->userDetails->combate_pvp["tipo"] == TIPO_COLISEU ? MAX_FA_COMBATE_COLISEU : MAX_FA_COMBATE;

		if ($personagem_combate["cod_capitao"] == $personagem_combate["cod"]) {
			$max_fa *= 2;
		}

		if ($personagem_combate["fa_ganha"] >= $max_fa || $personagem_combate["id"] == $alvo["id"]) {
			return;
		}

		if ($personagem_combate["cod_capitao"] == $personagem_combate["cod"]) {
			$fa_ganha += round($fa_ganha * 0.2);
		}

		$personagem_combate["fama_ameaca"] += $fa_ganha;
		$this->connection->run("UPDATE tb_personagens SET fama_ameaca = ?  WHERE cod = ?",
			"ii", array($personagem_combate["fama_ameaca"], $personagem_combate["cod"]));

		$personagem_combate["fa_ganha"] += $fa_ganha;
		$this->connection->run("UPDATE tb_combate_personagens SET fa_ganha = ?  WHERE cod = ?",
			"ii", array($personagem_combate["fa_ganha"], $personagem_combate["cod"]));

		$this->connection->run(
			"INSERT INTO tb_wanted_log (vencedor_cod, perdedor_cod, fa_ganha, fa_perdida, vencedor_lvl, perdedor_lvl) 
							 VALUES (?, ?, ?, ?, ?, ?)",
			"iiiiii", array($personagem_combate["cod"], $alvo["cod"], $fa_ganha, 0, $personagem_combate["lvl"], $alvo["lvl"])
		);
	}

	public function get_mod_akuma($personagem_combate, $alvo) {
		if (isset($personagem_combate["categoria_akuma"])) {
			$personagem_combate["akuma"] = true;
		}
		if (isset($alvo["categoria_akuma"])) {
			$alvo["akuma"] = true;
		}

		if (!isset($personagem_combate["akuma"])) {
			$personagem_combate["akuma"] = null;
		}
		if (!isset($alvo["akuma"])) {
			$alvo["akuma"] = null;
		}

		if (!$personagem_combate["akuma"]
			|| !$alvo["akuma"]
			|| $this->userDetails->buffs->get_efeito("anula_efeito_akuma")
			|| $this->userDetails->buffs->get_efeito_from_tripulacao("anula_efeito_akuma", $alvo["id"])
		) {
			$mod_akuma = 1;
		} else if ($personagem_combate["akuma"] && $alvo["akuma"]) {
			$categoria_atacante = isset($personagem_combate["categoria_akuma"]) ? $personagem_combate["categoria_akuma"] : get_categoria_akuma($personagem_combate["akuma"]);
			$categoria_alvo = isset($alvo["categoria_akuma"]) ? $alvo["categoria_akuma"] : get_categoria_akuma($alvo["akuma"]);
			$mod_akuma = categoria_akuma($categoria_atacante, $categoria_alvo);
		} else {
			$mod_akuma = 1;
		}
		return $mod_akuma;
	}

	public function get_npc_status() {
		$npc_stats = array(
			"atk" => $this->userDetails->combate_pve["atk_npc"],
			"def" => $this->userDetails->combate_pve["def_npc"],
			"agl" => $this->userDetails->combate_pve["agl_npc"],
			"res" => $this->userDetails->combate_pve["res_npc"],
			"pre" => $this->userDetails->combate_pve["pre_npc"],
			"dex" => $this->userDetails->combate_pve["dex_npc"],
			"con" => $this->userDetails->combate_pve["con_npc"],
			"haki_esq" => 0,
			"haki_cri" => 0,
			"classe" => 0,
			"classe_score" => 0
		);
		return $npc_stats;
	}

	public function ataca_npc(&$personagem_combate, $habilidade, $tipo_skil, &$npc_stats) {
		$relatorio_afetado = array();
		$relatorio_afetado["id"] = atual_segundo();
		$relatorio_afetado["acerto"] = "1";
		$relatorio_afetado["quadro"] = "npc";
		$relatorio_afetado["cod"] = $this->userDetails->combate_pve["zona"];
		$relatorio_afetado["nome"] = $this->userDetails->combate_pve["nome_npc"];
		$relatorio_afetado["img"] = $this->userDetails->combate_pve["img_npc"];
		$relatorio_afetado["skin_r"] = "npc";

		if ($tipo_skil == 1 OR $tipo_skil == 4 OR $tipo_skil == 7) {

			$dano_habilidade = $habilidade["dano"] * 10;

			$resultado = calc_dano($personagem_combate, $npc_stats, $dano_habilidade);

			$relatorio_afetado["tipo"] = 0;
			if ($resultado["esquivou"]) {
				$relatorio_afetado["esq"] = "1";
			} else {
				if ($this->userDetails->combate_pve["boss_id"]) {
					$resultado["dano"] = max($resultado["dano"], 2000);
				}

				if ($aumento = $this->userDetails->buffs->get_efeito("aumento_dano_causado_npc")) {
					$resultado["dano"] += round($aumento * $resultado["dano"]);
				}

				$nhp = max(0, $this->userDetails->combate_pve["hp_npc"] - $resultado["dano"]);

				if ($nhp <= 0 && !$this->userDetails->combate_pve["boss_id"]) {
					aumenta_score($personagem_combate);
				}

				if (!$this->userDetails->combate_pve["boss_id"]) {
					$this->connection->run("UPDATE tb_combate_npc SET hp_npc = ? WHERE id = ?",
						"ii", array($nhp, $this->userDetails->tripulacao["id"]));
				} else {
					$this->connection->run("UPDATE tb_boss SET hp = ? WHERE id = ?",
						"ii", array($nhp, $this->userDetails->combate_pve["boss_id"]));

					$this->registra_ataque_boss($resultado);
				}

				$relatorio_afetado["derrotado"] = $nhp ? 0 : 1;
				$relatorio_afetado["acerto"] = "1";
				$relatorio_afetado["esq"] = "0";
				$relatorio_afetado["bloq"] = $resultado["bloqueou"] ? 1 : 0;
				$relatorio_afetado["cri"] = $resultado["critou"] ? 1 : 0;
				$relatorio_afetado["efeito"] = $resultado["dano"];
				$this->userDetails->combate_pve["hp_npc"] = $nhp;
			}
		} else if ($tipo_skil == 2 OR $tipo_skil == 5 OR $tipo_skil == 8) {
			$this->connection->run("INSERT INTO tb_combate_buff_npc (tripulacao_id, atr, efeito, espera) VALUES (?, ?, ?, ?)",
				"iiii", array(
					$this->userDetails->tripulacao["id"],
					$habilidade["bonus_atr"],
					$habilidade["bonus_atr_qnt"],
					$habilidade["duracao"]));

			//relatorio buff
			$relatorio_afetado["tipo"] = 1;
			$relatorio_afetado["efeito"] = $habilidade["bonus_atr_qnt"];
			$relatorio_afetado["atributo"] = $habilidade["bonus_atr"];
		} else if ($tipo_skil == 10) {
			$bonush = $habilidade["hp_recuperado"] * 10;
			$nhp = min($this->userDetails->combate_pve["hp_npc"] + $bonush, $this->userDetails->combate_pve["hp_max_npc"]);

			$this->connection->run("UPDATE tb_combate_npc SET hp_npc = ? WHERE id= ?",
				"ii", array($nhp, $this->userDetails->tripulacao["id"]));

			$relatorio_afetado["tipo"] = 2;
			$relatorio_afetado["cura_h"] = $bonush;
			$relatorio_afetado["cura_m"] = $habilidade["mp_recuperado"];

			$this->userDetails->reduz_item($habilidade["cod_remedio"], TIPO_ITEM_REMEDIO, 1);
		}
		return $relatorio_afetado;
	}

	function registra_ataque_boss($resultado) {
		$log = $this->connection->run("SELECT * FROM tb_boss_damage WHERE tripulacao_id = ?  AND real_boss_id = ?",
			"ii", array($this->userDetails->tripulacao["id"], $this->userDetails->combate_pve["real_boss_id"]));
		if ($log->count()) {
			$this->connection->run("UPDATE tb_boss_damage SET damage = damage + ? WHERE tripulacao_id = ?  AND real_boss_id = ?",
				"iii", array($resultado["dano"], $this->userDetails->tripulacao["id"], $this->userDetails->combate_pve["real_boss_id"]));
		} else {
			$this->connection->run("INSERT INTO tb_boss_damage (tripulacao_id, damage, real_boss_id) VALUES (?, ?, ?)",
				"iii", array($this->userDetails->tripulacao["id"], $resultado["dano"], $this->userDetails->combate_pve["real_boss_id"]));
		}

		if ($this->userDetails->ally) {
			$real_boss_id = $this->connection->run("SELECT real_boss_id FROM tb_boss WHERE id = ?",
				"i", array($this->userDetails->combate_pve["boss_id"]))->fetch_array()["real_boss_id"];
			$missao_ally_result = $this->connection->run("SELECT * FROM tb_alianca_missoes WHERE cod_alianca = ? AND boss_id = ?",
				"ii", array($this->userDetails->ally["cod_alianca"], $real_boss_id));

			if ($missao_ally_result->count()) {
				$missao_ally = $missao_ally_result->fetch_array();

				if ($missao_ally["quant"] < $missao_ally["fim"]) {
					$this->connection->run("UPDATE tb_alianca_missoes SET quant = quant + ? WHERE cod_alianca = ?",
						"ii", array($resultado["dano"], $this->userDetails->ally["cod_alianca"]));
				}
			}
		}
	}

	public function processa_turno_npc($tabuleiro) {
		$npc_stats = $this->get_npc_status();
		$this->remove_buffs_npc();
		$this->aplica_buffs_npc($npc_stats);

		$relatorio = $this->processa_ataque_npc($npc_stats, $tabuleiro);

		$this->logger->registra_turno_combate_pve($relatorio);

		$mira = $this->userDetails->combate_pve["mira"];
		if (rand(1, 100) < 20) {
			$mira = $this->get_mira_adjacente($mira);
		}

		$this->connection->run("UPDATE tb_combate_npc SET  mira = ? WHERE id = ?",
			"ii", array($mira, $this->userDetails->tripulacao["id"]));
	}

	public function get_effect_random() {
		$effects = array(
			"Atingir fisicamente",
			"Efeito básico",
			"Golpe de fogo",
			"Golpe de gelo",
			"Golpe de trovão",
			"Slash físico",
			"Garra física",
			"Especial físico 1"
		);

		return $effects[array_rand($effects)];
	}

	public function processa_ataque_npc(&$npc_stats, &$tabuleiro) {
		//turno do npc
		$relatorio = array();
		$relatorio_afetado = array();
		$relatorio["nome"] = $this->userDetails->combate_pve["nome_npc"];
		$relatorio["cod"] = "npc";
		$relatorio["img"] = $this->userDetails->combate_pve["img_npc"];
		$relatorio["skin_r"] = "npc";
		$relatorio["nome_skil"] = "Ataque";
		$relatorio["img_skil"] = rand(1, 100);
		$relatorio["descricao_skil"] = "";
		$relatorio["tipo"] = 1;
		$relatorio["effect"] = $this->get_effect_random();

		//sorteia um personagem
		$alvo_mira = $this->get_alvo_npc($this->userDetails->combate_pve["mira"], $tabuleiro);
		$alvo = $alvo_mira["alvo"];

		//sorteia uma skil
		$habilidade = $this->connection->run("SELECT * FROM tb_skil_atk WHERE requisito_lvl <= ? ORDER BY RAND() LIMIT 1",
			"i", $alvo["lvl"])->fetch_array();

		$x = 0;
		$relatorio_afetado[$x] = $this->recebe_dano_npc($npc_stats, $habilidade, $alvo);
		$relatorio_afetado[$x]["quadro"] = $alvo_mira["x"] . "_" . $alvo_mira["y"];

		$relatorio["afetados"] = $relatorio_afetado;
		$relatorio["id"] = atual_segundo();

		return $relatorio;
	}

	public function get_alvo_npc($mira, $tabuleiro) {
		if ($mira < 0) {
			return $this->get_alvo_npc(0, $tabuleiro);
		} elseif ($mira > 4) {
			return $this->get_alvo_npc(4, $tabuleiro);
		}

		if (rand(1, 100) <= 90) {
			if (!isset($tabuleiro[$mira]) || !count($tabuleiro[$mira])) {
				return $this->get_alvo_npc($this->get_mira_adjacente($mira), $tabuleiro);
			}

			$y_rand = array_rand($tabuleiro[$mira]);
			return array(
				"alvo" => $tabuleiro[$mira][$y_rand],
				"x" => $mira,
				"y" => $y_rand
			);
		} else {
			return $this->get_alvo_npc($this->get_mira_adjacente($mira), $tabuleiro);
		}
	}

	public function get_mira_adjacente($mira) {
		if ($mira >= 4) {
			return 3;
		} else if ($mira <= 0) {
			return 1;
		} else {
			return rand(1, 2) == 1 ? $mira - 1 : $mira + 1;
		}
	}

	public function recebe_dano_npc($npc_stats, $habilidade, &$alvo) {
		$relatorio_afetado = array();
		$relatorio_afetado["acerto"] = "1";
		$relatorio_afetado["id"] = atual_segundo();

		$this->aplica_buffs($alvo);

		$relatorio_afetado["cod"] = $alvo["cod"];
		$relatorio_afetado["nome"] = $alvo["nome"];
		$relatorio_afetado["img"] = $alvo["img"];
		$relatorio_afetado["skin_r"] = $alvo["skin_r"];
		$relatorio_afetado["tipo"] = 0;

		$dano_habilidade = $habilidade["dano"] * 10;

		$resultado = calc_dano($npc_stats, $alvo, $dano_habilidade);

		if ($resultado["esquivou"]) {
			$relatorio_afetado["esq"] = "1";
		} else {
			if ($reducao = $this->userDetails->buffs->get_efeito("reducao_dano_recebido_npc")) {
				$resultado["dano"] = max(0, $resultado["dano"] - round($reducao * $resultado["dano"]));
			}

			$nhp = max(0, $alvo["hp"] - $resultado["dano"]);

			$this->connection->run("UPDATE tb_combate_personagens SET hp = ? WHERE cod = ?",
				"ii", array($nhp, $alvo["cod"]));

			if ($nhp <= 0 && !$this->userDetails->combate_pve["boss_id"] && !$this->userDetails->combate_pve["chefe_ilha"]) {
				if ($this->userDetails->tripulacao["missao_caca"]) {
					$missoes = DataLoader::load("missoes_caca");
					$missao = $missoes[$this->userDetails->tripulacao["missao_caca"]];
					if ($missao["objetivo"] != $this->userDetails->combate_pve["zona"]) {
						reduz_score($alvo);
					}
				} else {
					reduz_score($alvo);
				}
			}

			$relatorio_afetado["esq"] = "0";
			$relatorio_afetado["bloq"] = $resultado["bloqueou"] ? 1 : 0;
			$relatorio_afetado["cri"] = $resultado["critou"] ? 1 : 0;
			$relatorio_afetado["tipo"] = 0;
			$relatorio_afetado["efeito"] = $resultado["dano"];
			$relatorio_afetado["derrotado"] = $nhp ? 0 : 1;
		}

		return $relatorio_afetado;
	}

	public function extract_quadros($quadro) {
		$quadros = explode(";", $quadro);

		foreach ($quadros as $index => $quadro) {
			if ($quadro == "npc") {
				$quadros[$index] = array(
					"x" => "npc",
					"y" => "npc",
					"npc" => true
				);
			} else {
				$xy = explode("_", $quadro);
				$quadros[$index] = array(
					"x" => $xy[0],
					"y" => $xy[1],
					"npc" => false
				);
			}
		}
		return $quadros;
	}

	public function load_personagem_combate($cod_pers) {
		$result = $this->connection->run("SELECT * FROM tb_combate_personagens WHERE id = ? AND cod = ?",
			"ii", array($this->userDetails->tripulacao["id"], $cod_pers));

		if (!$result->count()) {
			$this->protector->exit_error("Personagem inválido");
		}

		$personagem_combate = $result->fetch_array();
		if ($personagem_combate["hp"] <= 0) {
			$this->protector->exit_error("personagem impossibilitado de lutar");
		}

		$personagem = $this->userDetails->get_pers_by_cod($cod_pers, true);

		$personagem_combate["classe"] = $personagem["classe"];
		$personagem_combate["classe_score"] = $personagem["classe_score"];
		$personagem_combate["tripulacao_id"] = $personagem["id"];
		$personagem_combate["cod_capitao"] = $this->userDetails->capitao["cod"];

		return array_merge($personagem, $personagem_combate);
	}

	public function check_and_load_habilidade($personagem_combate, $cod_skil, $tipo_skil, $quadros) {
		$this->check_espera($personagem_combate, $cod_skil, $tipo_skil);
		$habilidade = $this->load_habilidade($personagem_combate, $cod_skil, $tipo_skil);

		if ($personagem_combate["mp"] < $habilidade["consumo"]) {
			$this->protector->exit_error("Energia Insuficiente");
		}

		if (count($quadros) > $habilidade["area"]) {
			$this->protector->exit_error("Área exagerada");
		}

		return $habilidade;
	}

	public function load_habilidade($personagem, $cod_skil, $tipo_skil) {
		if ($tipo_skil != 10) {
			switch ($tipo_skil) {
				case 1:
				case 4:
					$table = "tb_skil_atk";
					break;
				case 2:
				case 5:
					$table = "tb_skil_buff";
					break;
				case 7:
					$table = "tb_akuma_skil_atk";
					break;
				case 8:
					$table = "tb_akuma_skil_buff";
					break;
				default:
					$this->protector->exit_error("Tipo de habilidade inválida");
			}

			$result = $this->connection->run(
				"SELECT * FROM tb_personagens_skil skil 
				INNER JOIN $table info ON skil.cod_skil = info.cod_skil AND skil.tipo = ?
				WHERE skil.cod_skil = ? AND skil.cod = ? AND skil.tipo = ?",
				"iiii", array($tipo_skil, $cod_skil, $personagem["cod"], $tipo_skil)
			);

			if (!$result->count()) {
				$this->protector->exit_error("Habilidade inválida");
			}

			return $result->fetch_array();
		} else {
			if ($personagem["profissao"] != PROFISSAO_MEDICO) {
				$this->protector->exit_error("este personagem nao possui profissao adequada");
			}

			$result = $this->connection->run(
				"SELECT * FROM tb_usuario_itens itn 
				 INNER JOIN tb_item_remedio rm ON itn.cod_item = rm.cod_remedio AND itn.tipo_item = ?
				 WHERE itn.id = ? AND itn.cod_item = ? AND itn.tipo_item = ?",
				"iiii", array(TIPO_ITEM_REMEDIO, $this->userDetails->tripulacao["id"], $cod_skil, TIPO_ITEM_REMEDIO)
			);

			if ($result->count()) {
				$habilidade = $result->fetch_array();
				$habilidade["consumo"] = $habilidade["hp_recuperado"] + $habilidade["mp_recuperado"];
				$habilidade["espera"] = 5;
				$habilidade["area"] = 1;
				$habilidade["alcance"] = 1;
				$habilidade["icon"] = $habilidade["img"];
				$habilidade["effect"] = "Cura 1";
				return $habilidade;
			} else {
				$this->protector->exit_error("Habilidade inválida");
			}
		}
	}

	public function check_espera($personagem, $cod_skil, $tipo_skil) {
		if ($tipo_skil == 10) {
			$result = $this->connection->run("SELECT * FROM tb_combate_skil_espera WHERE id = ? AND tipo = ?",
				"ii", array($this->userDetails->tripulacao["id"], $tipo_skil));
		} else {
			$result = $this->connection->run("SELECT * FROM tb_combate_skil_espera WHERE cod = ? AND cod_skil = ? AND tipo = ?",
				"iii", array($personagem["cod"], $cod_skil, $tipo_skil));
		}

		if ($result->count()) {
			$espera = $result->fetch_array();
			if ($espera["espera"] > 0) {
				$this->protector->exit_error("Skil em espera");
			}
		}
	}

	public function load_tabuleiro($id_1, $id_2 = null, $bot_id = null) {
		$personagens_combate = get_pers_in_combate($id_1);
		$tabuleiro = [];
		$this->_add_pers_tabuleiro($personagens_combate, $tabuleiro);
		if ($id_2) {
			$personagens_combate = get_pers_in_combate($id_2);
			$this->_add_pers_tabuleiro($personagens_combate, $tabuleiro);
		}
		if ($bot_id) {
			$personagens_combate = get_pers_bot_in_combate($bot_id);
			$this->_add_pers_tabuleiro($personagens_combate, $tabuleiro);
		}

		if ($this->has_obstaculo()) {
			$obstaculos = $this->connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 1",
				"i", array($id_1))->fetch_all_array();
			foreach ($obstaculos as $obstaculo) {
				$tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = obstaculo_para_tabuleiro($obstaculo);
			}

			$obstaculos = $this->connection->run("SELECT * FROM tb_obstaculos WHERE tripulacao_id = ? AND tipo = 2",
				"i", array($id_2))->fetch_all_array();
			foreach ($obstaculos as $obstaculo) {
				$tabuleiro[$obstaculo["x"]][$obstaculo["y"]] = obstaculo_para_tabuleiro($obstaculo);
			}
		}

		return $tabuleiro;
	}

	private function _add_pers_tabuleiro($personagens_combate, &$tabuleiro) {
		foreach ($personagens_combate as $pers) {
			if ($pers["hp"] > 0) {
				$tabuleiro[$pers["quadro_x"]][$pers["quadro_y"]] = $pers;
			}
		}
	}

	public function is_quadro_ataque_valido($personagem_combate, $quadro, $habilidade, $tabuleiro) {
		return true;
		/*
		if ($quadro["x"] >= 10 || $quadro["x"] < 0
			|| $quadro["y"] >= 20 && $quadro["y"] < 0
		) {
			return false;
		}

		return $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, -1, -1)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, -1, 0)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, -1, 1)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, 0, -1)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, 0, 1)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, 1, -1)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, 1, 0)
			|| $this->percorre_reta($personagem_combate, $quadro, $habilidade["alcance"], $tabuleiro, 1, 1);
		*/
	}

	private function percorre_reta($personagem_combate, $quadro, $alcance, $tabuleiro, $x, $y) {
		for ($i = 1; $i <= $alcance; $i++) {
			if ($quadro["x"] == $personagem_combate["quadro_x"] + ($i * $x)
				&& $quadro["y"] == $personagem_combate["quadro_y"] + ($i * $y)
			) {
				return true;
			}
			if (isset($tabuleiro[$i * $x]) && isset($tabuleiro[$i * $x][$i * $y]) && $tabuleiro[$i * $x][$i * $y]["hp"] > 0) {
				return false;
			}
		}
		return false;
	}

	public function is_area_valida($quadros) {
		for ($i = 1; $i < count($quadros); $i++) {
			$quadro = $quadros[$i];
			$quadro_anterior = $quadros[$i - 1];

			if (sqrt(pow($quadro["x"] - $quadro_anterior["x"], 2) + pow($quadro["y"] - $quadro_anterior["y"], 2)) > 1.5) {
				return false;
			}
		}
		return true;
	}

	public function perdeu_vez_pvp() {
		if ($this->userDetails->combate_pvp["vez_tempo"] < atual_segundo()) {
			$passe = "passe_" . $this->userDetails->combate_pvp["vez"];
			$this->connection->run("UPDATE tb_combate SET $passe = $passe + 1 WHERE combate = ?",
				"i", array($this->userDetails->combate_pvp["combate"]));


			$id = $this->userDetails->combate_pvp["id_" . $this->get_enemy_id_index_in_pvp()];
			$this->apply_sangramento($id);
			$this->apply_veneno($id);

			$this->muda_vez_pvp();
		}
	}

	public function muda_vez_pvp() {
		$vez = $this->userDetails->combate_pvp["vez"] == 1 ? 2 : 1;
		$tempo = atual_segundo() + ($this->userDetails->combate_pvp["passe_$vez"] >= 3 ? 30 : 90);
		$this->connection->run("UPDATE tb_combate SET vez = ?, vez_tempo = ?, move_1 = ?, move_2 = ? WHERE combate = ?",
			"iiiii", array($vez, $tempo, 5, 5, $this->userDetails->combate_pvp["combate"]));
	}

	public function muda_vez_pve() {
		$this->connection->run("UPDATE tb_combate_npc SET move = 5 WHERE id = ?",
			"i", array($this->userDetails->tripulacao["id"]));
	}

	public function muda_vez_bot() {
		$vez = $this->userDetails->combate_bot["vez"] == 1 ? 2 : 1;
		$this->connection->run("UPDATE tb_combate_bot SET vez = ?, move = ? WHERE tripulacao_id = ?",
			"iii", array($vez, 5, $this->userDetails->tripulacao["id"]));
	}

	public function remove_espera() {
		$this->connection->run("UPDATE tb_combate_skil_espera SET espera = espera - 1 WHERE id = ?",
			"i", $this->userDetails->tripulacao["id"]);

		$this->connection->run("DELETE FROM tb_combate_skil_espera WHERE espera <= 0 AND id = ?",
			"i", $this->userDetails->tripulacao["id"]);
	}

	public function insert_espera($personagem, $cod_skil, $tipo_skil, $espera) {
		if ($espera) {
			$this->connection->run("INSERT INTO tb_combate_skil_espera (id, cod, cod_skil, tipo, espera) VALUES (?, ?, ?, ?, ?)",
				"iiiii", array($this->userDetails->tripulacao["id"], $personagem["cod"], $cod_skil, $tipo_skil, $espera));
		}
	}

	public function regen_mp() {
		$this->connection->run("UPDATE tb_combate_personagens SET mp = mp + CEIL(mp * 0.02) WHERE id = ?",
			"i", $this->userDetails->tripulacao["id"]);
		$this->connection->run("UPDATE tb_combate_personagens SET mp = mp_max WHERE mp > mp_max AND id = ?",
			"i", $this->userDetails->tripulacao["id"]);
		$this->connection->run("UPDATE tb_combate_personagens SET mp = 1 WHERE mp = 0 AND id = ?",
			"i", $this->userDetails->tripulacao["id"]);
	}

	public function reduz_mp($personagem, $quant) {
		$this->connection->run("UPDATE tb_combate_personagens SET mp = mp - ? WHERE cod = ?",
			"ii", array($quant, $personagem["cod"]));
	}

	public function remove_buffs() {
		$this->connection->run("UPDATE tb_combate_buff SET espera = espera - 1 WHERE id = ?",
			"i", $this->userDetails->tripulacao["id"]);

		$this->connection->run("DELETE FROM tb_combate_buff WHERE espera <= 0 AND id = ?",
			"i", $this->userDetails->tripulacao["id"]);
	}

	public function remove_special_effects() {
		$this->connection->run("UPDATE tb_combate_special_effect SET duracao = duracao - 1 WHERE tripulacao_id = ?",
			"i", $this->userDetails->tripulacao["id"]);

		$this->connection->run("DELETE FROM tb_combate_special_effect WHERE duracao <= 0 AND tripulacao_id = ?",
			"i", $this->userDetails->tripulacao["id"]);
	}

	public function aumenta_xp_profissao($personagem, $tipo_skil) {
		if ($personagem["profissao"] == PROFISSAO_COMBATENTE
			|| $personagem["profissao"] == PROFISSAO_MUSICO
			|| $tipo_skil == 10
		) {
			$this->connection->run(
				"UPDATE tb_personagens SET profissao_xp = profissao_xp + 1 
				WHERE profissao_xp < profissao_xp_max AND cod = ?",
				"i", $personagem["cod"]
			);
		}
	}

	public function aumenta_xp($personagem) {
		$ip_1 = $this->connection->run("SELECT ip FROM tb_usuarios WHERE id = ?",
			"i", $this->userDetails->combate_pvp["id_1"])->fetch_array();
		$ip_2 = $this->connection->run("SELECT ip FROM tb_usuarios WHERE id = ?",
			"i", $this->userDetails->combate_pvp["id_2"])->fetch_array();

		$xp = $ip_1["ip"] == $ip_2["ip"] ? 10 : 40;

		$this->connection->run("UPDATE tb_personagens SET xp = xp + ? WHERE cod = ?",
			"ii", array($xp, $personagem["cod"]));
	}

	public function aplica_buffs(&$personagem) {
		$buffs = $this->connection->run("SELECT * FROM tb_combate_buff WHERE cod = ?",
			"i", $personagem["cod"])->fetch_all_array();

		foreach ($buffs as $buff) {
			$atr = nome_atributo_tabela($buff["atr"]);
			$personagem[$atr] += $buff["efeito"];
		}

		for ($i = 1; $i <= 8; $i++) {
			$atr = nome_atributo_tabela($i);
			$personagem[$atr] = max(1, $personagem[$atr]);
		}
	}

	public function aplica_buffs_npc(&$npc_atr) {
		$buffs = $this->connection->run("SELECT * FROM tb_combate_buff_npc WHERE tripulacao_id = ?",
			"i", $this->userDetails->tripulacao["id"])->fetch_all_array();

		foreach ($buffs as $buff) {
			$atr = nome_atributo_tabela($buff["atr"]);
			$npc_atr[$atr] += $buff["efeito"];
		}

		for ($i = 1; $i <= 7; $i++) {
			$atr = nome_atributo_tabela($i);
			$npc_atr[$atr] = max(1, $npc_atr[$atr]);
		}
	}

	public function remove_buffs_npc() {
		$this->connection->run("UPDATE tb_combate_buff_npc SET espera = espera - 1 WHERE tripulacao_id = ?",
			"i", $this->userDetails->tripulacao["id"]);

		$this->connection->run("DELETE FROM tb_combate_buff_npc WHERE espera <= 0 AND tripulacao_id = ?",
			"i", $this->userDetails->tripulacao["id"]);
	}
}