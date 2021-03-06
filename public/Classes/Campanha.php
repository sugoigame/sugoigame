<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05/09/2017
 * Time: 00:00
 */
abstract class Campanha {
	/**
	 * @var mywrap_con
	 */
	protected $connection;

	/**
	 * @var UserDetails
	 */
	protected $userDetails;

	/**
	 * @var Protector
	 */
	protected $protector;

	protected $campanha;

	/**
	 * Combate constructor.
	 * @param $connection mywrap_con
	 * @param $userDetails UserDetails
	 * @param $protector Protector
	 */
	public function __construct($campanha, $connection, $userDetails, $protector) {
		$this->connection = $connection;
		$this->userDetails = $userDetails;
		$this->protector = $protector;
		$this->campanha = $campanha;
	}

	public abstract function get_current_stage_number();

	protected abstract function _para_proxima_etapa();

	public function get_current_stage() {
		return $this->campanha[$this->get_current_stage_number()];
	}

	public function get_current_progress() {
		$method = "progresso_etapa_" . $this->get_current_stage_number();
		return $this->$method();
	}

	public function finaliza_etapa_atual() {
		$method = "finaliza_etapa_" . $this->get_current_stage_number();
		return $this->$method();
	}

	protected function _finaliza_atual_etapa_simples() {
		return $this->_finaliza_etapa_simples($this->get_current_stage_number());
	}

	protected function _finaliza_etapa_simples($etapa_id) {
		$recompensas_recebidas = $this->_entrega_recompensas($etapa_id);

		$this->_para_proxima_etapa();

		return implode("<br/>", $recompensas_recebidas);
	}

	protected function _entrega_recompensas($etapa_id) {
		$recompensas_recebidas = [];
		foreach ($this->campanha[$etapa_id]["recompensas"] as $recompensa) {
			$recompensas_recebidas[] = recebe_recompensa($recompensa);
		}
		return $recompensas_recebidas;
	}

	protected function _progresso_fake() {
		return array(
			"progresso_atual" => 0,
			"progresso_total" => 1
		);
	}

	protected function _progresso_in_coord($x, $y) {
		return array(
			"progresso_atual" =>
				$this->userDetails->tripulacao["x"] == $x
				&& $this->userDetails->tripulacao["y"] == $y
					? 1
					: 0,
			"progresso_total" => 1
		);
	}

	protected function _progresso_in_ilha($ilha) {
		return array(
			"progresso_atual" =>
				$this->userDetails->ilha["ilha"] == $ilha
					? 1
					: 0,
			"progresso_total" => 1
		);
	}

	protected function _progresso_derrotar_rdm($rdm_id, $quant = 1) {
		$result = $this->connection->run("SELECT * FROM tb_pve WHERE id = ? AND zona = ?",
			"ii", array($this->userDetails->tripulacao["id"], $rdm_id));

		$progresso = $result->count() ? $result->fetch_array()["quant"] : 0;

		return array(
			"progresso_atual" => $progresso,
			"progresso_total" => $quant
		);
	}

	protected function _progresso_item($cod_reagent, $quant = 1) {
		$result = $this->userDetails->get_item($cod_reagent, TIPO_ITEM_REAGENT);

		$progresso = $result ? $result["quant"] : 0;

		return array(
			"progresso_atual" => $progresso,
			"progresso_total" => $quant
		);
	}

	protected function _progresso_incursao($ilha, $adversario) {
		$result = $this->connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ? AND ilha = ?",
			"ii", array($this->userDetails->tripulacao["id"], $ilha));

		$progresso = $result->count() ? $result->fetch_array()["progresso"] - 1 : 0;

		return array(
			"progresso_atual" => $progresso,
			"progresso_total" => $adversario
		);
	}
}