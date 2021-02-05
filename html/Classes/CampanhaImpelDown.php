<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27/08/2017
 * Time: 15:06
 */
class CampanhaImpelDown extends Campanha {

	public function get_current_stage_number() {
		return $this->userDetails->tripulacao["campanha_impel_down"];
	}

	public function progresso_etapa_1() {
		return $this->_progresso_in_coord(305, 109);
	}

	public function finaliza_etapa_1() {
		return $this->_finaliza_etapa_simples(1);
	}

	public function progresso_etapa_2() {
		return $this->_progresso_derrotar_rdm(79, 1);
	}

	public function finaliza_etapa_2() {
		$random_pers = $this->connection->run("SELECT * FROM tb_personagens WHERE id = ? AND cod <> ? ORDER BY ativo DESC, hp, RAND() LIMIT 1",
			"ii", array($this->userDetails->tripulacao["id"], $this->userDetails->capitao["cod"]))->fetch_array();

		$this->connection->run("UPDATE tb_personagens SET ativo = 0, preso = 1 WHERE cod = ?",
			"i", array($random_pers["cod"]));

		return $this->_finaliza_etapa_simples(2);
	}

	public function progresso_etapa_3() {
		return $this->_progresso_in_ilha(42);
	}

	public function finaliza_etapa_3() {
		return $this->_finaliza_etapa_simples(3);
	}

	public function progresso_etapa_4() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_4() {
		return $this->_finaliza_etapa_simples(4);
	}

	public function progresso_etapa_5() {
		return $this->_progresso_item(54, 1);
	}

	public function finaliza_etapa_5() {
		$this->userDetails->reduz_item(54, TIPO_ITEM_REAGENT, 1);
		return $this->_finaliza_etapa_simples(5);
	}

	public function progresso_etapa_6() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_6() {
		return $this->_finaliza_etapa_simples(6);
	}

	public function progresso_etapa_7() {
		return array(
			"progresso_atual" => $this->userDetails->can_access_inpel_down() ? 1 : 0,
			"progresso_total" => 1
		);
	}

	public function finaliza_etapa_7() {
		return $this->_finaliza_etapa_simples(7);
	}

	public function progresso_etapa_8() {
		return $this->_progresso_in_ilha(101);
	}

	public function finaliza_etapa_8() {
		return $this->_finaliza_etapa_simples(8);
	}

	public function progresso_etapa_9() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_9() {
		return $this->_finaliza_etapa_simples(9);
	}

	public function progresso_etapa_10() {
		return $this->_progresso_item(165, 3);
	}

	public function finaliza_etapa_10() {
		$this->userDetails->reduz_item(165, TIPO_ITEM_REAGENT, 3);
		return $this->_finaliza_etapa_simples(10);
	}

	public function progresso_etapa_11() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_11() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_12() {
		return $this->_progresso_derrotar_rdm(81, 1);
	}

	public function finaliza_etapa_12() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_13() {
		return $this->_progresso_derrotar_rdm(82, 1);
	}

	public function finaliza_etapa_13() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_14() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_14() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_15() {
		return $this->_progresso_derrotar_rdm(83, 1);
	}

	public function finaliza_etapa_15() {
		$this->connection->run("UPDATE tb_personagens SET preso = 0 WHERE id= ?",
			"i", array($this->userDetails->tripulacao["id"]));
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_16() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_16() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_17() {
		return $this->_progresso_incursao(101, 9);
	}

	public function finaliza_etapa_17() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_18() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_18() {
		$this->connection->run("UPDATE tb_personagens SET hp = 1 WHERE id = ? AND ativo = 1 AND hp > 0",
			"i", array($this->userDetails->tripulacao["id"]));
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_19() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_19() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_20() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_20() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_21() {
		return $this->_progresso_item(179, 2);
	}

	public function finaliza_etapa_21() {
		$this->userDetails->reduz_item(179, TIPO_ITEM_REAGENT, 2);
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_22() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_22() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_23() {
		return $this->_progresso_incursao(101, 15);
	}

	public function finaliza_etapa_23() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_24() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_24() {
		return $this->_finaliza_atual_etapa_simples();
	}

	public function progresso_etapa_25() {
		return $this->_progresso_fake();
	}

	public function finaliza_etapa_25() {
		$this->_finaliza_atual_etapa_simples();
		return "%oceano";
	}

	protected function _para_proxima_etapa() {
		$etapa = $this->get_current_stage();
		$this->connection->run("UPDATE tb_usuarios SET campanha_impel_down = ? WHERE id = ?",
			"ii", array($etapa["next"], $this->userDetails->tripulacao["id"]));
	}
}