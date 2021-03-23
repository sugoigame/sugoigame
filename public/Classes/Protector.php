<?php

/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14/05/2017
 * Time: 08:37
 */
class Protector {
	/**
	 * @var UserDetails
	 */
	private $userDetails;

	/**
	 * @var Response
	 */
	private $response;

	public function __construct($userDetails, $response) {
		$this->userDetails = $userDetails;
		$this->response = $response;
	}

	public function protect($session) {
		switch ($session) {
			case "academia":
			case "equipShop":
			case "estaleiro":
			case "mercado":
			case "materiais":
			case "restaurante":
			case "hospital":
			case "pousada":
			case "profissoesAprender":
			case "upgrader":
			case "tripulantesInativos":
			case "politicaIlha":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_missao_and_recrute();
				break;
			case "combate":
				$this->need_tripulacao();
				$this->must_be_in_any_kind_of_combat();
				break;
			case "coliseu":
			case "localizadorCasual":
			case "localizadorCompetitivo":
			case "respawn":
				$this->need_tripulacao();
				$this->must_be_out_of_any_kind_of_combat();
				break;
			case "missoes":
			case "missoesConcluidas":
			case "incursao":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_recrute();
				break;
			case "missoesR":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_recrute();
				break;
			case "recrutar":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_missao();
				break;
			case "akuma":
			case "akumaComer":
			case "quartos":
			case "forja":
			case "oficina":
			case "profissoes":
			case "maestria":
				$this->need_tripulacao();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_missao_and_recrute();
				break;
			case "statusNavio":
			case "navioSkin":
				$this->need_tripulacao();
				$this->need_navio();
				$this->must_be_out_of_any_kind_of_combat();
				break;
			case "equipamentos":
			case "habilidades":
			case "status":
			case "haki":
			case "listaNegra":
			case "realizacoes":
			case "tatics":
			case "combateLog":
			case "karma":
			case "lojaEvento":
			case "recrutamento":
			case "leiloes":
			case "forum":
			case "forumNewTopic":
			case "forumPosts":
			case "forumTopics":
				$this->need_tripulacao();
				$this->must_be_out_of_any_kind_of_combat();
				break;
			case "expulsar":
			case "missoesCaca":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_out_of_any_kind_of_combat();
				break;
			case "akumaBook":
			case "aliancaLista":
			case "bandeira":
			case "ranking":
			case "kanban":
			case "torneio":
				$this->need_tripulacao();
				break;
			case "aliancaCriar":
				$this->need_tripulacao();
				$this->must_be_out_of_an_ally();
				break;
			case "aliancaBanco":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_in_an_ally();
				break;
			case "alianca":
			case "aliancaDiplomacia":
			case "aliancaCooperacao":
			case "aliancaMissoes":
				$this->need_tripulacao();
				$this->must_be_in_an_ally();
				break;
			case "cadastrosucess":
			case "ativacaosucess":
				break;
			case "cadastro":
				$this->reject_conta();
				break;
			case "conta":
			case "vipComprar":
			case "vipLoja":
				$this->need_conta();
				break;
			case "seltrip":
				$this->reject_tripulacao();
				$this->need_conta();
				break;
			case "transporte":
				$this->need_tripulacao();
				$this->need_navio();
				$this->must_be_out_of_rota();
				$this->must_be_out_of_missao_and_recrute();
				break;
			case "oceano":
			case "amigaveis":
				$this->need_tripulacao();
				$this->must_be_out_of_missao_and_recrute();
				$this->must_be_out_of_any_kind_of_combat();
				$this->need_navio_alive();
				$this->need_tripulacao_alive();
				break;
			case "servicoDenDen":
				$this->need_tripulacao();
				$this->must_be_out_of_any_kind_of_combat();
				$this->must_be_out_of_ilha();
				$this->must_be_out_of_rota();
				break;
			case "arvoreAnM":
				$this->need_tripulacao();
				$this->must_be_in_ilha();
				$this->must_be_in_laftel();
				break;
			case "campanhaImpelDown":
				$this->need_tripulacao();
				$this->need_campanha_impel_down();
				break;
			case "campanhaEniesLobby":
				$this->need_tripulacao();
				$this->need_campanha_enies_lobby();
				break;
			case "admin-estatisticas":
			case "admin-combinacaoferreiro":
			case "admin-combinacaoartesao":
			case "admin-news":
			case "admin-mails":
			case "admin-combinacaocarpinteiro":
			case "admin-combinacaoequips":
			case "admin-batalhas":
			case "combateAssistirAdm":
				$this->must_be_gm();
				break;
			case "eventoPirata":
			case "eventoLadroesTesouro":
			case "eventoChefesIlhas":
			case "boss":
				$this->need_evento_periodico_ativo($session);
				break;
			case "eventoSemanaAmizade":
			case "eventoDiaPais":
			case "eventoIndependencia":
			case "eventoCriancas":
			case "eventoHalloween":
			case "eventoNatal":
			case "eventoAnoNovo":
				$this->exit_error("Evento indisponível");
				break;
			default:
				// np
				break;
		}
	}

	public function is_ful_wide_session($sessao) {
		return $sessao == "combate"
			|| $sessao == "combateAssistir"
			|| $sessao == "combinacaoEditor";
	}

	public function must_be_gm() {
		if (!$this->userDetails->tripulacao["adm"]) {
			$this->exit_error("Você não pode acessar isso.");
		}
	}

	public function need_tripulacao() {
		if (!$this->userDetails->tripulacao) {
			$this->exit_error("Você precisa estar logado.");
		}
	}

	public function need_campanha_impel_down() {
		if (!$this->userDetails->tripulacao["campanha_impel_down"]) {
			$this->exit_error("Você não liberou essa campanha ainda");
		}
	}

	public function need_campanha_enies_lobby() {
		if (!$this->userDetails->tripulacao["campanha_enies_lobby"]) {
			$this->exit_error("Você não liberou essa campanha ainda");
		}
	}

	public function reject_tripulacao() {
		if ($this->userDetails->tripulacao) {
			$this->exit_error("Você não pode estar logado.");
		}
	}

	public function need_conta() {
		if (!$this->userDetails->conta) {
			$this->exit_error("Você precisa estar logado.");
		}
	}

	public function reject_conta() {
		if ($this->userDetails->conta) {
			$this->exit_error("Você já está logado.");
		}
	}

	public function need_navio() {
		if (!$this->userDetails->navio) {
			$this->exit_error("Você precisa de um navio.");
		}
	}

	public function need_navio_alive() {
		if ($this->userDetails->navio["hp"] <= 0) {
			$this->exit_error("Seu navio está destruído, procure um estaleiro para repará-lo.");
		}
	}

	public function need_tripulacao_alive() {
		if (!$this->userDetails->tripulacao_alive) {
			echo "!hospital";
			exit();
		}
	}

	public function need_tripulacao_died() {
		if ($this->userDetails->tripulacao_alive) {
			$this->exit_error("Você não pode fazer isso agora");
		}
	}

	public function must_be_next_to_land() {
		if (!$this->userDetails->has_ilha_or_terra_envolta_me) {
			$this->exit_error("Você precisa estar próximo a uma ilha");
		}
	}

	public function must_be_out_of_any_kind_of_combat() {
		$this->must_be_out_of_combat_pvp();
		$this->must_be_out_of_combat_pve();
		$this->must_be_out_of_combat_bot();
	}

	public function must_be_out_of_combat_pvp() {
		if ($this->userDetails->combate_pvp) {
			echo "%combate";
			exit();
		}
	}

	public function must_be_out_of_combat_pve() {
		if ($this->userDetails->combate_pve) {
			echo "%combate";
			exit();
		}
	}

	public function must_be_out_of_combat_bot() {
		if ($this->userDetails->combate_bot) {
			echo "%combate";
			exit();
		}
	}

	public function must_be_in_any_kind_of_combat() {
		if (!$this->userDetails->combate_pvp && !$this->userDetails->combate_pve && !$this->userDetails->combate_bot) {
			$this->exit_error("Você não está em combate");
		}
	}

	public function must_be_in_combat_pvp() {
		if (!$this->userDetails->combate_pvp) {
			$this->exit_error("Você não está em combate");
		}
	}

	public function must_be_in_combat_pve() {
		if (!$this->userDetails->combate_pve) {
			$this->exit_error("Você não está em combate");
		}
	}

	public function must_be_in_combat_bot() {
		if (!$this->userDetails->combate_bot) {
			$this->exit_error("Você não está em combate");
		}
	}

	public function must_be_out_of_missao_and_recrute() {
		$this->must_be_out_of_missao();
		$this->must_be_out_of_recrute();
	}

	public function must_be_out_of_missao() {
		if ($this->userDetails->missao) {
			$this->exit_error("Você está ocupado em uma missão neste momento.");
		}
	}

	public function must_be_visivel() {
		if (!$this->userDetails->tripulacao["mar_visivel"]) {
			$this->exit_error("Você precisa estar visível no oceano.");
		}
	}

	public function must_be_in_missao() {
		if (!$this->userDetails->missao) {
			$this->exit_error("Você precisa ter iniciado uma missão.");
		}
	}

	public function must_be_out_of_missao_r() {
		if ($this->userDetails->missao_r) {
			$this->exit_error("Você está ocupado em uma missão neste momento.");
		}
	}

	public function must_be_out_of_recrute() {
		if ($this->userDetails->tripulacao["recrutando"]) {
			$this->exit_error("Você está ocupado em uma missão neste momento.");
		}
	}

	public function must_be_in_ilha() {
		if (!$this->userDetails->in_ilha) {
			$this->exit_error("Você precisa estar em uma ilha.");
		}
	}

	public function must_be_dono_ilha() {
		$this->must_be_in_ilha();
		if ($this->userDetails->ilha["ilha_dono"] != $this->userDetails->tripulacao["id"]) {
			$this->exit_error("Você precisa ser o dono dessa ilha.");
		}
	}

	public function must_be_in_laftel() {
		if ($this->userDetails->ilha["ilha"] != 47) {
			$this->exit_error("Você precisa estar em uma ilha.");
		}
	}

	public function must_be_out_of_ilha() {
		if ($this->userDetails->in_ilha) {
			$this->exit_error("Você precisa estar em alto mar.");
		}
	}

	public function must_be_in_coliseu_ilha() {
		if ($this->userDetails->ilha["ilha"] != ILHA_COLISEU && $this->userDetails->ilha["ilha"] != ILHA_COLISEU_2) {
			$this->exit_error("Você precisa estar em uma ilha.");
		}
	}

	public function must_be_in_an_ally() {
		if (!$this->userDetails->ally) {
			$this->exit_error("Você não faz parte de uma aliança.");
		}
	}

	public function must_be_out_of_an_ally() {
		if ($this->userDetails->ally) {
			$this->exit_error("Você já faz parte de uma aliança.");
		}
	}

	public function must_be_out_of_rota() {
		if ($this->userDetails->rota) {
			$this->exit_error("Você não pode fazer isso enquanto está navegando.");
		}
	}

	public function must_add_item() {
		if (!$this->userDetails->can_add_item()) {
			$this->exit_error("Não há espaço suficiente no seu inventário.");
		}
	}

	public function need_gold($quant) {
		if ($this->userDetails->conta["gold"] < $quant) {
			$this->exit_error("Você não possui Moedas de Ouro suficientes");
		}
	}

	public function need_dobroes_criados($quant) {
		if ($this->userDetails->conta["dobroes_criados"] < $quant) {
			$this->exit_error("Você não possui Dobrões de Ouro suficientes");
		}
	}

	public function need_dobroes($quant) {
		if ($this->userDetails->conta["dobroes"] < $quant) {
			$this->exit_error("Você não possui Dobrões de Ouro suficientes");
		}
	}

	public function need_berries($quant) {
		if ($this->userDetails->tripulacao["berries"] < $quant) {
			$this->exit_error("Você não possui Berries suficientes");
		}
	}

	public function need_capitao_in_lvl($lvl) {
		if ($this->userDetails->capitao["lvl"] < $lvl) {
			$this->exit_error("O seu capitão precisa estar no nível $lvl");
		}
	}

	public function get_tripulante_or_exit($cod_key, $all_trip = false) {
		$pers_cod = $this->get_number_or_exit($cod_key);
		$pers = $this->userDetails->get_pers_by_cod($pers_cod, $all_trip);
		if (!$pers) {
			$this->exit_error("Personagem inválido");
		}
		return $pers;
	}

	public function need_gold_or_dobrao($tipo, $quant_gold, $quant_dobrao) {
		if ($tipo == "gold") {
			$this->need_gold($quant_gold);
		} else if ($tipo == "dobrao") {
			$this->need_dobroes($quant_dobrao);
		}
	}

	public function need_evento_periodico_ativo($evento) {
		if (get_value_varchar_variavel_global(VARIAVEL_EVENTO_PERIODICO_ATIVO) != $evento) {
			$this->exit_error("Evento indisponível");
		}
	}

	public function get_number_or_exit($key) {
		if (!isset($_GET[$key]) || !validate_number($_GET[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_GET[$key];
	}

	public function get_alphanumeric_or_exit($key) {
		if (!isset($_GET[$key]) || !validate_alphanumeric($_GET[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_GET[$key];
	}

	public function get_enum_or_exit($key, $enum) {
		if (!isset($_GET[$key]) || !in_array($_GET[$key], $enum)) {
			$this->_exit_with_msg_for_input();
		}
		return $_GET[$key];
	}

	public function get_test_pass_or_exit($key, $test) {
		if (!isset($_GET[$key]) || !preg_match($test, $_GET[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_GET[$key];
	}

	public function post_tripulante_or_exit($cod_key, $all_trip = false) {
		$pers_cod = $this->post_number_or_exit($cod_key);
		$pers = $this->userDetails->get_pers_by_cod($pers_cod, $all_trip);
		if (!$pers) {
			$this->exit_error("Personagem inválido");
		}
		return $pers;
	}

	public function post_enum_or_exit($key, $enum) {
		if (!isset($_POST[$key]) || !in_array($_POST[$key], $enum)) {
			$this->_exit_with_msg_for_input();
		}
		return $_POST[$key];
	}

	public function post_number_or_exit($key) {
		if (!isset($_POST[$key]) || !validate_number($_POST[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_POST[$key];
	}

	public function post_alphanumeric_or_exit($key) {
		if (!isset($_POST[$key]) || !validate_alphanumeric($_POST[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_POST[$key];
	}

	public function post_value_or_exit($key) {
		if (!isset($_POST[$key])) {
			$this->_exit_with_msg_for_input();
		}
		return $_POST[$key];
	}

	public function protect_number($number) {
		if (!validate_number($number)) {
			$this->_exit_with_msg_for_input();
		}
	}

	public function redirect_number_invalid($number) {
		if (!validate_number($number)) {
			$this->_redirect_with_msg_for_input();
		}
	}

	public function redirect_email_invalid($number) {
		if (!validate_email($number)) {
			$this->_redirect_with_msg_for_input();
		}
	}

	public function redirect_alphanumeric_invalid($number) {
		if (!validate_alphanumeric($number)) {
			$this->_redirect_with_msg_for_input();
		}
	}

	private function _exit_with_msg_for_input() {
		$this->exit_error("Você informou algum caracter invalido");
	}

	private function _redirect_with_msg_for_input() {
		$this->redirect_error("Você informou algum caracter inválido.");
	}

	public function exit_error($error) {
		$this->response->error($error);
		exit();
	}

	public function redirect_error($error, $ses = "home") {
		header("location:../../?ses=$ses&msg=$error");
		exit();
	}
}