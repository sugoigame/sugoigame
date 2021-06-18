<?php

define('COD_BAU_INICIANTE_5', 123);
define('COD_BAU_INICIANTE_10', 124);
define('COD_BAU_INICIANTE_15', 125);
define('COD_BAU_INICIANTE_20', 126);
define('COD_BAU_INICIANTE_25', 127);
define('COD_BAU_INICIANTE_30', 128);
define('COD_BAU_INICIANTE_35', 129);
define('COD_BAU_INICIANTE_40', 130);
define('COD_BAU_INICIANTE_45', 131);
define('COD_BAU_INICIANTE_50', 132);

class ItemUsavel {
	/**
	 * @var UserDetails
	 */
	private $userDetails;

	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var Protector
	 */
	private $protector;

	/**
	 * @var Response
	 */
	private $response;

	/**
	 * ItemUsavel constructor.
	 * @param UserDetails $userDetails
	 * @param mywrap_con $connection
	 * @param Protector $protector
	 */
	public function __construct($userDetails, $connection, $protector, $response) {
		$this->userDetails = $userDetails;
		$this->connection = $connection;
		$this->protector = $protector;
		$this->response = $response;
	}

	public function abre_bau_equipamentos_grandes_poderes() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 2 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamentos_cinza() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 1 AND lvl <= ? ORDER BY ceil(lvl / 10) DESC, RAND() LIMIT 1",
			"i", array($this->userDetails->lvl_mais_forte)
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamentos_branco() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 2 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamentos_verdes() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 3 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamentos_azuis() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 4 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamento_preto() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 5 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_bau_equipamento_dourado() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$equipamento = $this->connection->run(
			"SELECT * FROM tb_equipamentos WHERE categoria = 6 AND lvl >= 50 ORDER BY RAND() LIMIT 1"
		)->fetch_array();

		$this->userDetails->add_equipamento($equipamento);

		return "Você recebeu " . $equipamento["nome"];
	}

	public function abre_pacote_joias_grandes_poderes() {
		if (!$this->userDetails->can_add_item(7)) {
			$this->protector->exit_error("Seu inventário está cheio. Você precisa de 7 espaços livres para receber a recompensa");
		}

		$this->userDetails->add_item(1, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(3, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(5, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(7, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(9, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(11, TIPO_ITEM_REAGENT, rand(1, 3));
		$this->userDetails->add_item(13, TIPO_ITEM_REAGENT, rand(1, 3));

		return "Você recebeu várias joias diferentes";

		$response->loot($recompensas, "Você Recebeu " . implode(", ", $recompensas));
	}

	public function akuma_aleatoria() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Seu inventário está cheio, libere espaço para receber a recompensa");
		}

		$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

		return "Você recebeu uma Akuma no Mi";
	}

	public function abre_pacote_iniciante_5() {
		if ($this->userDetails->capitao["lvl"] < 5) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 5");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(121, TIPO_ITEM_ACESSORIO, 1, true);

		$this->userDetails->xp_for_all(300);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_10, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu um acessório de Ataque e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_10() {
		if ($this->userDetails->capitao["lvl"] < 10) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 10");
		}

		if (!$this->userDetails->can_add_item(10)) {
			$this->protector->exit_error("Você precisa de 10 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_equipamento_by_cod(22);
		$this->userDetails->add_equipamento_by_cod(23);
		$this->userDetails->add_equipamento_by_cod(24);
		$this->userDetails->add_equipamento_by_cod(25);
		$this->userDetails->add_equipamento_by_cod(26);
		$this->userDetails->add_equipamento_by_cod(27);
		$this->userDetails->add_equipamento_by_cod(28);
		$this->userDetails->add_equipamento_by_cod(29);
		$this->userDetails->add_equipamento_by_cod(30);

		$this->userDetails->xp_for_all(400);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_15, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu equipamentos de nível 10 e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_15() {
		if ($this->userDetails->capitao["lvl"] < 15) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 15");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(51, TIPO_ITEM_REAGENT, 50);

		$this->userDetails->xp_for_all(500);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_20, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu 50 Ferros e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_20() {
		if ($this->userDetails->capitao["lvl"] < 20) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 20");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(75, TIPO_ITEM_REAGENT, 50);

		$this->userDetails->xp_for_all(600);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_25, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu 50 Troncos de Madeira Verde e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_25() {
		if ($this->userDetails->capitao["lvl"] < 25) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 25");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(62, TIPO_ITEM_REAGENT, 50);

		$this->userDetails->xp_for_all(700);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_30, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu 50 Estanhos e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_30() {
		if ($this->userDetails->capitao["lvl"] < 30) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 30");
		}

		if (!$this->userDetails->can_add_item(6)) {
			$this->protector->exit_error("Você precisa de 6 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_equipamento_by_cod(52);
		$this->userDetails->add_equipamento_by_cod(53);
		$this->userDetails->add_equipamento_by_cod(54);
		$this->userDetails->add_equipamento_by_cod(55);
		$this->userDetails->add_equipamento_by_cod(56);

		$this->userDetails->xp_for_all(800);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_35, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu equipamentos e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_35() {
		if ($this->userDetails->capitao["lvl"] < 35) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 35");
		}

		if (!$this->userDetails->can_add_item(9)) {
			$this->protector->exit_error("Você precisa de 9 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_equipamento_by_cod(57);
		$this->userDetails->add_equipamento_by_cod(58);
		$this->userDetails->add_equipamento_by_cod(59);
		$this->userDetails->add_equipamento_by_cod(60);
		$this->userDetails->add_equipamento_by_cod(61);
		$this->userDetails->add_equipamento_by_cod(62);
		$this->userDetails->add_equipamento_by_cod(63);
		$this->userDetails->add_equipamento_by_cod(64);

		$this->userDetails->xp_for_all(900);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_40, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu equipamentos e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_40() {
		if ($this->userDetails->capitao["lvl"] < 40) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 40");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(123, TIPO_ITEM_ACESSORIO, 1, true);

		$this->userDetails->xp_for_all(1000);

		$this->userDetails->add_berries(1000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_45, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu um acessório de ataque e 1 milhão de Berries!";
	}

	public function abre_pacote_iniciante_45() {
		if ($this->userDetails->capitao["lvl"] < 45) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 45");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}
		$this->userDetails->xp_for_all(1000);

		$this->userDetails->add_berries(5000000);

		$this->userDetails->add_item(COD_BAU_INICIANTE_50, TIPO_ITEM_REAGENT, 1);

		return "Você recebeu 5 milhões de Berries!";
	}

	public function abre_pacote_iniciante_50() {
		if ($this->userDetails->capitao["lvl"] < 50) {
			$this->protector->exit_error("Seu capitão precisa estar no nível 50");
		}

		if (!$this->userDetails->can_add_item(1)) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

		$this->userDetails->xp_for_all(1000);

		$this->userDetails->add_berries(1000000);

		return "Você recebeu uma Akuma no Mi e 1 milhão de Berries!";
	}
	public function abre_pacote_torneio() {
		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaço vazio no seu inventário para receber a recopensa");
		}
		$this->userDetails->add_item(rand(100, 110), 8, 1, true);
		$this->userDetails->add_equipamento_by_cod(491);
		return "Você recebeu uma Akuma no Mi e uma Katana da sonoplastia inadequada!!";
	}


	public function abre_mensagem_garrafa() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$coordenada = get_random_coord_navegavel($this->userDetails->ilha["mar"]);

		$descricao = "Este mapa diz que existe um tesouro escondido em " . get_human_location($coordenada["x"], $coordenada["y"])
			. ".<br/> Navegue até lá e clique em Usar quando chegar a essa coordenada.";

		$result = $this->connection->run(
			"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method) 
			  VALUE (326, 'jpg', 'Mapa do Tesouro', ?, ?, ?, ?, 'completa_missao')",
			"siii", array($descricao, TIPO_MISSAO_POR_ITEM_NAVEGAR, $coordenada["x"], $coordenada["y"])
		);

		$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);

		return "Você recebeu um Mapa do Tesouro!";
	}

	public function completa_missao($item) {
		if ($item["tipo_missao"] == TIPO_MISSAO_POR_ITEM_NAVEGAR) {
			if ($this->userDetails->tripulacao["x"] != $item["x"] || $this->userDetails->tripulacao["y"] != $item["y"]) {
				$this->protector->exit_error("Você não chegou a coordenada correta.");
			}
			if (!$this->userDetails->can_add_item()) {
				$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
			}

			$this->userDetails->add_item(134, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu um baú do tesouro!";
		} else if ($item["tipo_missao"] == TIPO_MISSAO_POR_ITEM_LUTAR) {
			if ($this->userDetails->tripulacao["x"] != $item["x"] || $this->userDetails->tripulacao["y"] != $item["y"]) {
				$this->protector->exit_error("Você não chegou a coordenada correta.");
			}

			atacar_rdm($item["rdm_id"]);
			return '%combate';
		}

		return "";
	}

	public function abre_bau_tesouro() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->xp_for_all(200);

		$rand = rand(1, 100);

		if ($rand <= 30) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mergulho > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);

			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(1, 10);
			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);

			return "Você recebeu $quant " . $recompensa["nome"];
		} else if ($rand <= 40) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 1 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];
		} else if ($rand <= 50) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 2 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];
		} else/* if ($rand <= 99)*/ {
			$recompensa = rand(100000, 500000);

			$this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
				"ii", array($recompensa, $this->userDetails->tripulacao["id"]));

			return "Você recebeu " . mascara_berries($recompensa) . " Berries";
		}/* else {
			$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

			return "Você recebeu uma Akuma no Mi";
		}*/
	}
	public function abre_ovo_carrot() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->xp_for_all(1000);

		$rand = rand(1, 100);

		if ($rand <= 1) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mergulho > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);

			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(1, 10);
			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);
			
			return "Você recebeu " . $recompensa["nome"];

		} 
		 else if ($rand <= 20) {
			$recompensa = rand(500000, 1000000);

			$this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
				"ii", array($recompensa, $this->userDetails->tripulacao["id"]));

			return "Você recebeu " . mascara_berries($recompensa) . " Berries";
}
			else if ($rand <= 60) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 2 AND lvl >= 50 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];
		}
		else if ($rand >= 61) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 3 AND lvl >= 50 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];}
		else if ($rand >= 89){
				$this->userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);

				return "Você recebeu uma Akuma no Mi";	
			}}

	public function obter_animacao_skill($item, $params) {
		$effect = $params[1];

		$this->userDetails->add_effect($effect);

		return "Você recebeu uma animação de habilidade";
	}

	public function juntar_pedaco_mapa_moby_dick() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}
		$item = $this->userDetails->get_item(145, TIPO_ITEM_REAGENT);

		if ($item["quant"] < 4) {
			$this->protector->exit_error("Você precisa de 4 pedaços de mapa para formar um mapa do tesouro.");
		}

		$this->userDetails->reduz_item(145, TIPO_ITEM_REAGENT, 3);

		$coordenada = get_random_coord_navegavel($this->userDetails->ilha["mar"]);

		$descricao = "Este mapa foi encontrado dentro do Moby Dick e diz que existe um tesouro escondido em " . get_human_location($coordenada["x"], $coordenada["y"])
			. ".<br/> Navegue até lá e clique em Usar quando chegar a essa coordenada.";

		$result = $this->connection->run(
			"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method, rdm_id) 
			  VALUE (326, 'jpg', 'Mapa do Tesouro', ?, ?, ?, ?, 'completa_missao', ?)",
			"siiii", array($descricao, TIPO_MISSAO_POR_ITEM_LUTAR, $coordenada["x"], $coordenada["y"], 76)
		);

		$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);

		return "Você recebeu um Mapa do Tesouro!";
	}

	public function abre_bau_tesouro_especial() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->xp_for_all(200);

		$rand = rand(1, 100);

		if ($rand <= 20) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mergulho > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);
			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(5, 15);

			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);

			return "Você recebeu $quant " . $recompensa["nome"];
		} else if ($rand <= 30) {
			$recompensa = rand(5000, 10000);

			$this->userDetails->haki_for_all($recompensa);

			return "Você recebeu $recompensa pontos de Haki para distribuir";
		} else if ($rand <= 40) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 1 AND lvl > 40 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];
		} else if ($rand <= 50) {
			$recompensa = $this->connection->run("SELECT * FROM tb_equipamentos WHERE categoria = 2 AND lvl > 30 ORDER BY RAND() LIMIT 1")->fetch_array();

			$this->userDetails->add_equipamento($recompensa);

			return "Você recebeu " . $recompensa["nome"];
		} else/* if ($rand <= 99)*/ {
			// $recompensa = rand(5000000, 10000000);
			$recompensa = rand(100000, 500000);

			$this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
				"ii", array($recompensa, $this->userDetails->tripulacao["id"]));

			return "Você recebeu " . mascara_berries($recompensa) . " Berries";
		}/* else {
			$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

			return "Você recebeu uma Akuma no Mi";
		}*/
	}

	public function abre_bau_tesouro_excepcional() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->xp_for_all(200);

		$rand = rand(1, 140);

		if ($rand <= 15) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mining > 0 OR madeira > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);
			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(20, 40);

			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);

			return "Você recebeu $quant " . $recompensa["nome"];
		} else if ($rand <= 30) {
			$recompensa = rand(10000, 20000);

			$this->userDetails->haki_for_all($recompensa);

			return "Você recebeu $recompensa pontos de Haki para distribuir";
		} else if ($rand <= 50) {
			$recompensa = rand(10000, 15000);

			$this->userDetails->xp_for_all($recompensa);

			return "Você recebeu $recompensa pontos de Experiência para toda a tripulação.";
		} else if ($rand <= 70) {
			$this->userDetails->add_item(180, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Brancos";
		} else if ($rand <= 75) {
			$this->userDetails->add_item(155, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Essência Verde";
		} else if ($rand <= 95) {
			$recompensa = rand(20000000, 30000000);

			$this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
				"ii", array($recompensa, $this->userDetails->tripulacao["id"]));

			return "Você recebeu " . mascara_berries($recompensa) . " Berries";
		} else if ($rand < 135) {
			$this->userDetails->add_item(192, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu um Pacote de Bordas de Personagem";
		} else {
			$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function abre_pedido_ajuda() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		if ($this->userDetails->ilha["mar"] <= 5) {
			$ilha = ($this->userDetails->ilha["mar"] - 1) * 7 + 1;
		} else {
			$ilha = 44;
		}

		$coordenada = $this->connection->run("SELECT * FROM tb_mapa WHERE ilha = ? LIMIT 1",
			"i", array($ilha))->fetch_array();

		$descricao = "<i>\"A quem estiver lendo essa mensagem, por favor me ajude! Estou em " . get_human_location($coordenada["x"], $coordenada["y"])
			. " lhe darei detalhes assim que chegar\"</i>"
			. ".<br/> Navegue até o lugar descrito na mensagem e clique em Usar quando chegar.";

		$result = $this->connection->run(
			"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method) 
			  VALUE (22, 'png', 'Pedido de Ajuda', ?, ?, ?, ?, 'completa_pedido_ajuda')",
			"siii", array($descricao, TIPO_MISSAO_POR_ITEM_NAVEGAR, $coordenada["x"], $coordenada["y"])
		);

		$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);

		return "Você recebeu um Pedido de Ajuda!";
	}

	public function completa_pedido_ajuda($item) {
		if ($this->userDetails->tripulacao["x"] != $item["x"] || $this->userDetails->tripulacao["y"] != $item["y"]) {
			$this->protector->exit_error("Você não chegou a coordenada correta.");
		}
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}
		$this->userDetails->add_item(149, TIPO_ITEM_REAGENT, 1);

		$this->userDetails->xp_for_all(500);

		return "Você recebeu um Pedido de Ajuda e 500 pontos de experiência para toda tripulação!";
	}

	public function inicia_missao_pedido_ajuda() {
		if (!$this->userDetails->can_add_item(4)) {
			$this->protector->exit_error("Você precisa de 4 espaços vazios no seu inventário para receber a recopensa");
		}

		for ($i = 0; $i < 4; $i++) {
			$coordenada = get_random_coord_navegavel($this->userDetails->ilha["mar"]);
			$descricao = "Existe um material em " . get_human_location($coordenada["x"], $coordenada["y"])
				. ".<br/> Navegue até lá e clique em Usar quando chegar a essa coordenada.";

			$result = $this->connection->run(
				"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method, rdm_id) 
			  VALUE (326, 'jpg', 'Mapa de instrução', ?, ?, ?, ?, 'completa_missao', ?)",
				"siiii", array($descricao, TIPO_MISSAO_POR_ITEM_LUTAR, $coordenada["x"], $coordenada["y"], 77)
			);

			$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);
		}

		return "Você recebeu um Pedido de Ajuda!";
	}

	public function finaliza_missao_pedido_ajuda() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}
		$item = $this->userDetails->get_item(150, TIPO_ITEM_REAGENT);

		if ($item["quant"] < 4) {
			$this->protector->exit_error("Você precisa de 4 materiais de reparo para obter o próximo passo.");
		}

		$this->userDetails->reduz_item(150, TIPO_ITEM_REAGENT, 3);

		if ($this->userDetails->ilha["mar"] <= 5) {
			$ilha = ($this->userDetails->ilha["mar"] - 1) * 7 + 1;
		} else {
			$ilha = 44;
		}

		$coordenada = $this->connection->run("SELECT * FROM tb_mapa WHERE ilha = ? LIMIT 1",
			"i", array($ilha))->fetch_array();

		$descricao = "Leve os materiais de volta a " . get_human_location($coordenada["x"], $coordenada["y"])
			. " e clique em Usar quando chegar.";

		$result = $this->connection->run(
			"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method) 
			  VALUE (97, 'jpg', 'Pacote de materiais de reparo', ?, ?, ?, ?, 'recompensa_pedido_ajuda')",
			"siii", array($descricao, TIPO_MISSAO_POR_ITEM_NAVEGAR, $coordenada["x"], $coordenada["y"])
		);

		$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);

		$this->userDetails->xp_for_all(500);

		return "Você recebeu um Pacote de materiais de reparo!";
	}

	public function recompensa_pedido_ajuda($item) {
		if ($this->userDetails->tripulacao["x"] != $item["x"] || $this->userDetails->tripulacao["y"] != $item["y"]) {
			$this->protector->exit_error("Você não chegou a coordenada correta.");
		}
		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->add_item(146, TIPO_ITEM_REAGENT, 1);
		$this->userDetails->add_item(151, TIPO_ITEM_REAGENT, 1);
		$this->userDetails->xp_for_all(500);

		return "Você recebeu um baú do tesouro especial e um encolhedor de navio!";
	}

	public function encolher_navio($item, $params) {

		$this->userDetails->buffs->add_buff(25, $params[1] * 60 * 60);

		return array(
			"message" => "Seu navio foi encolhido",
			"prevent_remove" => true
		);
	}

	public function iniciar_campanha_impel_down() {
		if (!$this->userDetails->tripulacao["campanha_impel_down"]) {
			$this->connection->run("UPDATE tb_usuarios SET campanha_impel_down = 1 WHERE id = ?",
				"i", array($this->userDetails->tripulacao["id"]));
		}
		return "Você iniciou a Campanha de Impel Down!";
	}

	public function iniciar_campanha_enies_lobby() {
		if (!$this->userDetails->tripulacao["campanha_enies_lobby"]) {
			$this->connection->run("UPDATE tb_usuarios SET campanha_enies_lobby = 1 WHERE id = ?",
				"i", array($this->userDetails->tripulacao["id"]));
		}
		return "Você iniciou a Campanha de Enies Lobby!";
	}

	public function acesso_impel_down() {
		if (!$this->userDetails->can_access_inpel_down()) {
			$acesso = get_value_variavel_global(VARIAVEL_IDS_ACESSO_IMPEL_DOWN);
			$ids = explode(",", $acesso["valor_varchar"]);
			$ids[] = $this->userDetails->tripulacao["id"];
			$this->connection->run("UPDATE tb_variavel_global SET valor_varchar = ? WHERE variavel = ?",
				"ss", array(implode(",", $ids), VARIAVEL_IDS_ACESSO_IMPEL_DOWN));
		}
		return "Você conseguiu o acesso à Impel Down!";
	}


	public function multiplicador_xp($item, $params) {
		if ($this->userDetails->buffs->get_efeito("multiplicador_xp_lvl_max")) {
			$this->protector->exit_error("Você já tem um efeito similar ativo");
		}

		$this->userDetails->buffs->add_buff(26, $params[1] * 60 * 60);

		return array(
			"message" => "Bônus ativo!"
		);
	}

	public function aprende_receita_forja_random() {
		$receita = $this->connection->run(
			"SELECT * FROM tb_combinacoes_forja c
			 LEFT JOIN tb_combinacoes_forja_conhecidas con ON c.cod_receita = con.combinacao_id AND con.tripulacao_id = ?
			 WHERE c.visivel = 0 AND con.id IS NULL
			 ORDER BY rand() LIMIT 1",
			"i", array($this->userDetails->tripulacao["id"])
		);

		if (!$receita->count()) {
			$this->protector->exit_error("Você já aprendeu todas as receitas disponíveis no momento");
		}

		$receita = $receita->fetch_array();

		$this->connection->run("INSERT INTO tb_combinacoes_forja_conhecidas (tripulacao_id, combinacao_id) VALUE (?, ?)",
			"ii", array($this->userDetails->tripulacao["id"], $receita["cod_receita"]));

		return "Você aprendeu uma nova receita para a Forja do Navio!";
	}

	public function aprende_receita_oficina_random() {
		$receita = $this->connection->run(
			"SELECT * FROM tb_combinacoes_artesao c
			 LEFT JOIN tb_combinacoes_artesao_conhecidas con ON c.cod_receita = con.combinacao_id AND con.tripulacao_id = ?
			 WHERE c.visivel = 0 AND con.id IS NULL
			 ORDER BY rand() LIMIT 1",
			"i", array($this->userDetails->tripulacao["id"])
		);

		if (!$receita->count()) {
			$this->protector->exit_error("Você já aprendeu todas as receitas disponíveis no momento");
		}

		$receita = $receita->fetch_array();

		$this->connection->run("INSERT INTO tb_combinacoes_artesao_conhecidas (tripulacao_id, combinacao_id) VALUE (?, ?)",
			"ii", array($this->userDetails->tripulacao["id"], $receita["cod_receita"]));

		return "Você aprendeu uma nova receita para a Oficina do Navio!";
	}

	public function inicia_evento_fuga_kiritsugu() {
		$this->connection->run(
			"INSERT INTO tb_mapa_contem (x, y, nps_id)
			(SELECT mapa.x, mapa.y, 10 FROM tb_mapa mapa WHERE (mar = 5 OR mar = 6) AND navegavel = 1 ORDER BY RAND() LIMIT 10);");

		$this->connection->run("INSERT INTO tb_mensagens_globais (assunto, mensagem) VALUE 
			('Evento dos fugitivos de Impel Down', 'Um jogador concluiu a Campanha de Impel Down e ajudou um fugitivo muito perigoso a escapar. Depois do Governo movimentar suas tropas para recapturar os fugitivos, alguns Navios de fugitivos de Impel Down foram vistos navegando pelo oceano. Os primeiros jogadores que encontrarem e derrotarem esses navios receberão um baú de recompensas que pode conter muitos pontos de Haki, Fragmentos de Essência Verde e Akumas no Mi.')");

		return "Você iniciou o Evento dos Fugitivos de Impel Down!";
	}

	public function abre_bau_tesouro_impel_down() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$this->userDetails->xp_for_all(1000);

		$rand = rand(1, 100);

		if ($rand <= 34) {
			$this->userDetails->add_item(154, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Fragmento de Essência Verde";
		} else if ($rand <= 67) {
			$this->userDetails->haki_for_all(20000);

			return "Você recebeu 20.000 pontos de Haki para distribuir";
		} else {
			$this->userDetails->add_item(rand(100, 110), rand(8, 10), 1, true);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function abre_bau_aparencia() {
		$aparencias = DataLoader::load("skins");

		do {
			do {
				$img = array_rand($aparencias);

				$tentativas = 0;
				do {
					$skin = array_rand($aparencias[$img]);
					$preco = $aparencias[$img][$skin];
					$tentativas++;
				} while (substr($preco, 0, 2) == "ID" && $tentativas <= 3);

			} while ($tentativas > 3);

			$exists = $this->connection->run("SELECT * FROM tb_tripulacao_skins WHERE tripulacao_id = ? AND img = ?  AND skin = ?",
				"iii", array($this->userDetails->tripulacao["id"], $img, $skin))->count();
		} while ($exists);

		$this->connection->run("INSERT INTO tb_tripulacao_skins (tripulacao_id, img, skin, conta_id) VALUE (?,?,?,?)",
			"iiii", array($this->userDetails->tripulacao["id"], $img, $skin, $this->userDetails->tripulacao["conta_id"]));

		echo icon_pers_skin($img, $skin) . "<br/>";
		echo big_pers_skin($img, $skin) . "<br/>";

		return "Você recebeu uma Aparência de Personagem!";
	}

	public function abre_bau_alcunha() {
		$alcunhas = array(141, 142, 143, 144, 145, 146, 147, 148, 149, 150, 96, 97, 98, 104, 105, 106, 111, 112,
			113, 114, 115, 130, 131, 133, 134, 132, 135, 138);

		$alcunha = $this->connection->run(
			"SELECT * FROM tb_titulos t
			LEFT JOIN tb_personagem_titulo p ON p.titulo = t.cod_titulo AND p.cod = ?
			WHERE t.cod_titulo IN (" . implode(",", $alcunhas) . ") AND p.cod IS NULL
			ORDER BY RAND() LIMIT 1",
			"i", array($this->userDetails->capitao["cod"])
		);

		if (!$alcunha->count()) {
			$this->protector->exit_error("Você já adquiriu todas as Alcunhas disponíveis.");
		}

		$alcunha = $alcunha->fetch_array();

		$this->connection->run("INSERT INTO tb_personagem_titulo (cod, titulo) VALUE (?,?)",
			"ii", array($this->userDetails->capitao["cod"], $alcunha["cod_titulo"]));

		return "Você recebeu a alcunha \"" . $alcunha["nome"] . "\"";
	}

	public function abre_bau_nivel_batalha() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$rand = rand(1, 230);

		if ($rand <= 40) {
			$this->userDetails->add_item(185, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Passe Livre do Estilista";
		} else if ($rand <= 80) {
			$this->userDetails->add_item(184, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Carta de Honra ao Mérito";
		} else if ($rand <= 90) {
			$this->userDetails->add_item(180, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Brancos";
		} else if ($rand <= 95) {
			$this->userDetails->add_item(188, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Verdes";
		} else if ($rand <= 107) {
			$this->userDetails->add_item(183, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Azuis";
		} else if ($rand <= 115) {
			$this->userDetails->add_item(155, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Essência Verde";
		} else if ($rand <= 125) {
			$this->userDetails->add_berries(5000000);

			return "Você recebeu 5 milhões Berries";
		} else if ($rand <= 135) {
			$this->userDetails->xp_for_all(10000);

			return "Você recebeu 10 mil pontos de Experiência para toda tripulação.";
		} else if ($rand <= 145) {
			$this->userDetails->haki_for_all(10000);

			return "Você recebeu 10 mil pontos de Haki para distribuir.";
		} else if ($rand < 185) {
			$this->userDetails->add_item(192, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu um Pacote de Bordas de Personagem";
		} else if ($rand <= 225) {
			$this->userDetails->add_item(200, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Instrução de Combate";
		} else {
			$this->userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function invoca_nps($item, $params) {
		$nps = $params[1];

		$x = $this->userDetails->tripulacao["x"];
		$y = $this->userDetails->tripulacao["y"];
		$x_rand = rand($x - 2, $x + 2);
		$y_rand = rand($y - 2, $y + 2);

		$this->connection->run("INSERT INTO tb_mapa_contem (x, y, nps_id) VALUE (?,?,?)",
			"iii", array($x_rand, $y_rand, $nps));

		return "Um bando de Piratas Ladrões de Tesouros apareceu nas redondezas.";
	}

	public function abre_bau_coliseu() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$rand = rand(1, 205);

		if ($rand <= 40) {
			$this->userDetails->add_item(185, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Passe Livre do Estilista";
		} else if ($rand <= 80) {
			$this->userDetails->add_item(184, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Carta de Honra ao Mérito";
		} else if ($rand <= 90) {
			$this->userDetails->add_item(180, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Brancos";
		} else if ($rand <= 100) {
			$this->userDetails->add_berries(10000000);

			return "Você recebeu 10 milhões Berries";
		} else if ($rand <= 110) {
			$this->userDetails->xp_for_all(10000);

			return "Você recebeu 10 mil pontos de Experiência para toda tripulação.";
		} else if ($rand <= 120) {
			$this->userDetails->haki_for_all(10000);

			return "Você recebeu 10 mil pontos de Haki para distribuir.";
		} else if ($rand < 160) {
			$this->userDetails->add_item(192, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu um Pacote de Bordas de Personagem";
		} else if ($rand <= 200) {
			$this->userDetails->add_item(200, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Instrução de Combate";
		} else {
			$this->userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function abre_decorativo() {
		$bordas = DataLoader::load("bordas");

		$minhas_bordas = $this->connection->run("SELECT * FROM tb_tripulacao_bordas WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]))->fetch_all_array();

		$bordas_validas = array();
		foreach ($bordas as $id => $borda) {
			if ($borda["sorteavel"]) {
				$found = FALSE;
				foreach ($minhas_bordas as $minha_borda) {
					if ($minha_borda["borda"] == $id) {
						$found = TRUE;
						break;
					}
				}

				if (!$found) {
					$bordas_validas[] = $id;
				}
			}
		}

		if (!count($bordas_validas)) {
			$this->protector->exit_error("Você já recebeu todas as bordas. Aguarde até novas sejam adicionadas para tentar novamente.");
		}
		$borda = $bordas_validas[array_rand($bordas_validas)];

		$this->connection->run("INSERT INTO tb_tripulacao_bordas (tripulacao_id, borda) VALUE (?,?)",
			"ii", array($this->userDetails->tripulacao["id"], $borda));

		echo "<img src=\"Imagens/Personagens/Bordas/$borda.png\" />";

		return "Você recebeu uma Borda de Personagem!";
	}
	public function abre_fim_era() {
		$bordas = array("1" => array());

		$minhas_bordas = $this->connection->run("SELECT * FROM tb_tripulacao_bordas WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]))->fetch_all_array();

		$bordas_validas = array();
		foreach ($bordas as $id => $borda) {
			
				$found = FALSE;
				foreach ($minhas_bordas as $minha_borda) {
					if ($minha_borda["borda"] == $id) {
						$found = TRUE;
						break;
					}
				}

				if (!$found) {
					$bordas_validas[] = $id;
				}
			
		}

		if (!count($bordas_validas)) {
			$this->protector->exit_error("Você já recebeu todas as bordas. Aguarde até novas sejam adicionadas para tentar novamente.");
		}
		$borda = $bordas_validas[array_rand($bordas_validas)];

		$this->connection->run("INSERT INTO tb_tripulacao_bordas (tripulacao_id, borda) VALUE (?,?)",
			"ii", array($this->userDetails->tripulacao["id"], $borda));

		echo "<img src=\"Imagens/Personagens/Bordas/$borda.png\" />";

		return "Você recebeu uma Borda de Personagem!";
	}
	public function abre_fim_poderes() {
		$bordas = array("3" => array());

		$minhas_bordas = $this->connection->run("SELECT * FROM tb_tripulacao_bordas WHERE tripulacao_id = ?",
			"i", array($this->userDetails->tripulacao["id"]))->fetch_all_array();

		$bordas_validas = array();
		foreach ($bordas as $id => $borda) {
			
				$found = FALSE;
				foreach ($minhas_bordas as $minha_borda) {
					if ($minha_borda["borda"] == $id) {
						$found = TRUE;
						break;
					}
				}

				if (!$found) {
					$bordas_validas[] = $id;
				}
			
		}

		if (!count($bordas_validas)) {
			$this->protector->exit_error("Você já recebeu todas as bordas. Aguarde até novas sejam adicionadas para tentar novamente.");
		}
		$borda = $bordas_validas[array_rand($bordas_validas)];

		$this->connection->run("INSERT INTO tb_tripulacao_bordas (tripulacao_id, borda) VALUE (?,?)",
			"ii", array($this->userDetails->tripulacao["id"], $borda));

		echo "<img src=\"Imagens/Personagens/Bordas/$borda.png\" />";

		return "Você recebeu uma Borda de Personagem!";
	}

	public function abre_bau_pvp_ouro() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$rand = rand(1, 130);

		if ($rand <= 30) {
			$this->userDetails->add_item(189, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Cinza";
		} else if ($rand <= 50) {
			$this->userDetails->add_item(180, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Brancos";
		} else if ($rand < 60) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mergulho > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);

			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(20, 30);
			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);

			return "Você recebeu $quant " . $recompensa["nome"];
		} else if ($rand <= 70) {
			$this->userDetails->add_berries(5000000);

			return "Você recebeu 5 milhões de Berries";
		} else if ($rand <= 100) {
			$this->userDetails->xp_for_all(5000);

			return "Você recebeu 5 mil pontos de Experiência para toda tripulação.";
		} else if ($rand <= 120) {
			$this->userDetails->haki_for_all(7000);

			return "Você recebeu 7 mil pontos de Haki para distribuir.";
		} else {
			$this->userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function abre_bau_pvp_prata() {
		if (!$this->userDetails->can_add_item()) {
			$this->protector->exit_error("Você precisa de 1 espaço vazio no seu inventário para receber a recopensa");
		}

		$rand = rand(1, 130);

		if ($rand <= 30) {
			$this->userDetails->add_item(189, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Cinza";
		} else if ($rand <= 50) {
			$this->userDetails->add_item(180, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu 1 Baú de Equipamentos Brancos";
		} else if ($rand < 60) {
			$recompensa = $this->connection->run("SELECT * FROM tb_item_reagents WHERE mergulho > 0 ORDER BY RAND() LIMIT 1")->fetch_array();

			$reagents_quant_1 = array(170, 171);

			$quant = in_array($recompensa["cod_reagent"], $reagents_quant_1) ? 1 : rand(20, 30);
			$this->userDetails->add_item($recompensa["cod_reagent"], TIPO_ITEM_REAGENT, $quant);

			return "Você recebeu $quant " . $recompensa["nome"];
		} else if ($rand <= 70) {
			$this->userDetails->add_berries(1000000);

			return "Você recebeu 1 milhão de Berries";
		} else if ($rand <= 100) {
			$this->userDetails->xp_for_all(1000);

			return "Você recebeu 1 mil pontos de Experiência para toda tripulação.";
		} else if ($rand <= 120) {
			$this->userDetails->haki_for_all(5000);

			return "Você recebeu 5 mil pontos de Haki para distribuir.";
		} else {
			$this->userDetails->add_item(121, TIPO_ITEM_REAGENT, 1);

			return "Você recebeu uma Akuma no Mi";
		}
	}

	public function abre_contrato_ameaca_fantasma() {
		$count = $this->connection->run("SELECT * FROM tb_pve WHERE id = ? AND zona = ?",
			"ii", array($this->userDetails->tripulacao["id"], 87));

		if (!$count->count() || $count->fetch_array()["quant"] < 20) {
			$this->protector->exit_error("Você precisa derrotar 20 Navios Fantasmas para abrir este pergaminho");
		}

		if (!$this->userDetails->can_add_item(2)) {
			$this->protector->exit_error("Você precisa de 2 espaços vazios no seu inventário para receber a recopensa");
		}

		$this->userDetails->reduz_item(198, TIPO_ITEM_REAGENT, 1);

		$coordenada = get_random_coord_navegavel($this->userDetails->ilha["mar"]);

		$descricao = "Este mapa mostra que em " . get_human_location($coordenada["x"], $coordenada["y"]) . " existe um monstro que supostamente lidera os navios fantasmas."
			. "<br/> Navegue até lá e clique em Usar quando chegar a essa coordenada.";

		$result = $this->connection->run(
			"INSERT INTO tb_item_missao (img, img_format, nome, descricao, tipo_missao, x, y, method, rdm_id) 
			  VALUE (326, 'jpg', 'Mapa do Tesouro Especial', ?, ?, ?, ?, 'completa_missao', ?)",
			"siiii", array($descricao, TIPO_MISSAO_POR_ITEM_LUTAR, $coordenada["x"], $coordenada["y"], 88)
		);

		$this->userDetails->add_item($result->last_id(), TIPO_ITEM_MISSAO, 1);

		$this->userDetails->add_item(155, TIPO_ITEM_REAGENT, 1);

		$this->userDetails->xp_for_all(1000);

		return "Você recebeu um Mapa do Tesouro Especial!";
	}

	public function entrega_cabeca_abobora() {
		if ($this->userDetails->ilha["ilha"] != 40) {
			$this->protector->exit_error("Você precisa estar em Thriller Bark para usar este item");
		}

		$this->userDetails->xp_for_all(1000);

		$rdms = DataLoader::load("rdm");
		$rdm = $rdms[89];

		$this->connection->run("DELETE FROM tb_rotas WHERE id = ?", "i", $this->userDetails->tripulacao["id"]);
		$this->connection->run("DELETE FROM tb_mapa_contem WHERE id = ?", "i", $this->userDetails->tripulacao["id"]);

		$this->connection->run(
			"INSERT INTO tb_combate_npc 
					(id, 
					img_npc,
					nome_npc, 
					hp_npc, hp_max_npc, 
					mp_npc, mp_max_npc, 
					atk_npc, def_npc, agl_npc, res_npc, pre_npc, dex_npc, con_npc, 
					dano, armadura, 
					zona, battle_back)
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
			"iisiiiiiiiiiiiiiii", array(
				$this->userDetails->tripulacao["id"],
				isset($rdm["img"]) ? $rdm["img"] : rand($rdm["img_rand_min"], $rdm["img_rand_max"]),
				$rdm["nome"],
				$rdm["hp"], $rdm["hp"],
				0, 0,
				$rdm["atk"], $rdm["def"], $rdm["agl"], $rdm["res"], $rdm["pre"], $rdm["dex"], $rdm["con"],
				0, 0,
				$rdm["id"], 18
			)
		);

		insert_personagens_combate($this->userDetails->tripulacao["id"], $this->userDetails->personagens, $this->userDetails->vip, "tatic_p", 0, 4);

		return '%combate';
	}

	public function usa_fantasia_halloween() {

		$this->userDetails->buffs->add_buff(28, 60 * 60);

		return array(
			"message" => "Quando entrar em combate, um de seus tripulantes ficará fantasiado!",
			"prevent_remove" => true
		);
	}

	public function obter_animacao_skill_aleatoria() {

		$effects = array(
			"Atingir fisicame",
			"Efeito básico",
			"Golpe de fogo",
			"Golpe de gelo",
			"Golpe de trovão",
			"Slash físico",
			"Efeito slash",
			"Fogo slash",
			"Gelo slash",
			"Trovão slash",
			"Perfuração física",
			"Efeito da perfuração",
			"Perfuração de fogo",
			"Perfuração do gelo",
			"Perfuração de trovão",
			"Garra física",
			"Efeito da garra",
			"Garra de fogo",
			"Garra gelo",
			"Garra trovão",
			"Golpe especial 1",
			"Golpe especial 2",
			"Slash especial 1",
			"Slash especial 2",
			"Slash especial 3",
			"Perfuração especial 1",
			"Perfuração especial 2",
			"Garra especial",
			"Seta especial",
			"Especial físico 1",
			"Especial físico 2",
			"Bafo de fogo",
			"Pólen",
			"Onda sônica",
			"Neblina",
			"Canção",
			"Grito",
			"Varredura",
			"Bodyslam",
			"Flash",
			"Cura 1",
			"Cura 2",
			"Cura 3",
			"Cura 4",
			"Cura 5",
			"Cura 6",
			"Cura 7",
			"Cura 8",
			"Reviver 1",
			"Reviver 2",
			"Energizar 1",
			"Energizar 2",
			"Energizar 3",
			"Debuffar 1",
			"Maldição 1",
			"Maldição 2",
			"Vincular",
			"Absorver",
			"Venenoso",
			"Blind",
			"Silêncio",
			"Suspensão",
			"Confusão",
			"Distorção elétrica",
			"Morte",
			"Incendiar 1",
			"Incendiar 2",
			"Incendiar tudo 1",
			"Incendiar tudo 2",
			"Incendiar tudo 3",
			"Gelar 1",
			"Gelar 2",
			"Gelar todos 1",
			"Gelar todos 2",
			"Gelar todos 3",
			"Trovejar 1",
			"Trovejar 2",
			"Trovejar todos 1",
			"Trovejar todos 2",
			"Trovejar todos 3",
			"Aguar 1",
			"Aguar 2",
			"Aguar todos 1",
			"Aguar todos 2",
			"Aguar todos 3",
			"Aterrar 1",
			"Aterrar 2",
			"Aterrar todos 1",
			"Aterrar todos 2",
			"Aterrar todos 3",
			"Ventanear 1",
			"Ventanear 2",
			"Ventanear todos 1",
			"Ventanear todos 2",
			"Ventanear todos 3",
			"Iluminar 1",
			"Iluminar 2",
			"Iluminar todos 1",
			"Iluminar todos 2",
			"Iluminar todos 3",
			"Escuridão 1",
			"Escuridão 2",
			"Escuridão 3",
			"Escuridão 4",
			"Escuridão 5",
			"Neutro 1",
			"Neutro 2",
			"Neutro 3",
			"Neutro 4",
			"Neutro 5",
			"Disparo normal",
			"Disparo de barragem",
			"Disparar tudo",
			"Disparo especial",
			"Laser um",
			"Laser tudo",
			"Pilar de luz 1",
			"Pilar de luz 2",
			"Bola de luz",
			"Luz brilhante"
		);

		$effect = $effects[array_rand($effects)];
		$this->userDetails->add_effect($effect);

		return "Você recebeu a animação \"$effect\"";
	}
}