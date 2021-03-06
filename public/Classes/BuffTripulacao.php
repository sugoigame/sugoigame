<?php

class BuffTripulacao {
	/**
	 * @var UserDetails
	 */
	private $userDetails;
	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var array
	 */
	private $buffs_spec;

	/**
	 * @var array
	 */
	public $buffs_ativos;

	public function __construct($userDetails, $connection) {
		$this->userDetails = $userDetails;
		$this->connection = $connection;
		$this->buffs_spec = DataLoader::load("buffs_tripulacao");

		$this->_expira_buffs();

		$buffs = $this->connection->run("SELECT * FROM tb_tripulacao_buff WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]))->fetch_all_array();

		$this->buffs_ativos = array();
		foreach ($buffs as $buff) {
			$this->buffs_ativos[] = array_merge($buff, $this->buffs_spec[$buff["buff_id"]]);
		}

		$buffs = $this->connection->run("SELECT * FROM tb_buff_global WHERE expiracao > ?",
			"i", array(atual_segundo()))->fetch_all_array();

		foreach ($buffs as $buff) {
			$this->buffs_ativos[] = array_merge($buff, $this->buffs_spec[$buff["buff_id"]]);
		}

		$this->connection->run("DELETE FROM tb_ilha_bonus_ativo WHERE expiracao < unix_timestamp()");

		$buffs = $this->connection->run("SELECT * FROM tb_ilha_bonus_ativo WHERE x >= ? AND x <= ? AND y >= ? AND y <= ?",
			"iiii", array(
				$this->userDetails->tripulacao["x"] - ALCANCE_BONUS_ILHA,
				$this->userDetails->tripulacao["x"] + ALCANCE_BONUS_ILHA,
				$this->userDetails->tripulacao["y"] - ALCANCE_BONUS_ILHA,
				$this->userDetails->tripulacao["y"] + ALCANCE_BONUS_ILHA,
			))->fetch_all_array();

		foreach ($buffs as $buff) {
			$this->buffs_ativos[] = array_merge($buff, $this->buffs_spec[$buff["buff_id"]]);
		}
	}

	private function _expira_buffs() {
		$this->connection->run("DELETE FROM tb_tripulacao_buff WHERE tripulacao_id = ? AND expiracao < ?",
			"ii", array($this->userDetails->tripulacao["id"], atual_segundo()));
	}

	/**
	 * @param $efeito
	 * @return number|bool
	 */
	public function get_efeito($efeito) {
		$acumulado = 0;
		foreach ($this->buffs_ativos as $buff) {
			if (isset($buff[$efeito])) {
				if (isset($buff["nao_acumulativo"])) {
					$acumulado = $buff[$efeito];
				} else {
					$acumulado += $buff[$efeito];
				}
			}
		}
		return $acumulado == 0 ? FALSE : $acumulado;
	}

	public function get_efeito_from_tripulacao($efeito, $tripulacao_id) {
		$buffs = $this->connection->run("SELECT * FROM tb_tripulacao_buff WHERE tripulacao_id = ?", "i", $tripulacao_id)->fetch_all_array();
		foreach ($buffs as $buff) {
			$spec = $this->buffs_spec[$buff["buff_id"]];
			if (isset($spec[$efeito])) {
				return $spec[$efeito];
			}
		}
		return false;
	}

	public function has_buff($buff_id) {
		foreach ($this->buffs_ativos as $buff) {
			if ($buff["buff_id"] == $buff_id) {
				return true;
			}
		}
		return false;
	}

	public function add_buff($buff_id, $duracao) {
		$this->connection->run("INSERT INTO tb_tripulacao_buff (tripulacao_id, buff_id, expiracao) VALUE (?,?,?)",
			"iii", array($this->userDetails->tripulacao["id"], $buff_id, atual_segundo() + $duracao));
	}

	function destroy() {
		$this->userDetails = null;
		$this->connection = null;
		$this->buffs_ativos = null;
		$this->buffs_spec = null;
	}
}