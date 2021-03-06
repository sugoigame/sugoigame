<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 08/08/2017
 * Time: 12:21
 */
class CombateLogger {
	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var UserDetails
	 */
	private $userDetails;

	/**
	 * Combate constructor.
	 * @param $connection mywrap_con
	 * @param $userDetails UserDetails
	 */
	public function __construct($connection, $userDetails) {
		$this->connection = $connection;
		$this->userDetails = $userDetails;
	}

	public function get_relatorio_combate_bot() {
		return $this->userDetails->combate_bot["relatorio"] && strlen($this->userDetails->combate_bot["relatorio"])
			? json_decode($this->userDetails->combate_bot["relatorio"], true)
			: array();
	}

	public function get_relatorio_combate_pve() {
		return $this->userDetails->combate_pve["relatorio"] && strlen($this->userDetails->combate_pve["relatorio"])
			? json_decode($this->userDetails->combate_pve["relatorio"], true)
			: array();
	}

	public function get_relatorio_combate_pvp($combate) {
		$log_file = @fopen(dirname(dirname(__FILE__)) . "/Logs/PvP/" . $combate . ".log", "r");

		$logs = array();
		if ($log_file) {
			while ($line = fgets($log_file)) {
				array_unshift($logs, json_decode($line, true));

				if (count($logs) > 10) {
					array_pop($logs);
				}
			}

			fclose($log_file);
		}
		return $logs;
	}

	public function registra_turno_combate_bot($relatorio) {
		$relatorio_antigo = $this->userDetails->combate_bot["relatorio"] && strlen($this->userDetails->combate_bot["relatorio"])
			? json_decode($this->userDetails->combate_bot["relatorio"], true)
			: array();

		$relatorio_antigo = array_slice($relatorio_antigo, 0, 10);
		array_unshift($relatorio_antigo, $relatorio);
		$novo_relatorio = json_encode($relatorio_antigo);

		$this->connection->run("UPDATE tb_combate_bot SET relatorio = ? WHERE tripulacao_id = ?",
			"si", array($novo_relatorio ? $novo_relatorio : "", $this->userDetails->tripulacao["id"]));
	}

	public function registra_turno_combate_pve($relatorio) {
		$relatorio_antigo = $this->userDetails->combate_pve["relatorio"] && strlen($this->userDetails->combate_pve["relatorio"])
			? json_decode($this->userDetails->combate_pve["relatorio"], true)
			: array();

		$relatorio_antigo = array_slice($relatorio_antigo, 0, 10);
		if (!$relatorio_antigo) {
			$relatorio_antigo = array();
		}
		array_unshift($relatorio_antigo, $relatorio);
		$novo_relatorio = json_encode($relatorio_antigo);

		$this->connection->run("UPDATE tb_combate_npc SET relatorio = ? WHERE id = ?",
			"si", array($novo_relatorio ? $novo_relatorio : "", $this->userDetails->tripulacao["id"]));

		$this->userDetails->combate_pve["relatorio"] = $novo_relatorio;
	}

	public function registra_turno_combate_pvp($relatorio) {
		$log_file = fopen(dirname(dirname(__FILE__)) . "/Logs/PvP/" . $this->userDetails->combate_pvp["combate"] . ".log", "a+");

		fwrite($log_file, json_encode($relatorio) . "\n");

		fclose($log_file);
	}
}