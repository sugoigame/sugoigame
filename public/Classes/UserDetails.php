<?php

/**
 * Class UserDetails
 *
 * @property array tripulacao
 * @property array personagens
 * @property array rotas
 * @property array vip
 * @property array conta
 * @property array tripulacoes
 * @property array capitao
 * @property int lvl_mais_forte
 * @property int fa_mais_alta
 * @property array lvl_medico
 * @property array medicos
 * @property array navegadores
 * @property int lvl_navegador
 * @property array carpinteiros
 * @property int lvl_carpinteiro
 * @property array artesoes
 * @property array ferreiros
 * @property array mergulhadores
 * @property int lvl_mergulhador
 * @property array cartografos
 * @property int lvl_cartografo
 * @property array arqueologos
 * @property int lvl_arqueologo
 * @property boolean in_ilha
 * @property array ilha
 * @property array navio
 * @property array ally
 * @property array combate_pvp
 * @property array tripulacoes_pvp
 * @property array combate_pve
 * @property array combate_bot
 * @property boolean in_combate
 * @property array missao
 * @property array missao_r
 * @property boolean is_visivel
 * @property boolean has_ilha_envolta_me
 * @property boolean has_ilha_or_terra_envolta_me
 * @property boolean tripulacao_alive
 * @property array fila_coliseu
 * @property number lvl_coliseu
 * @property array alerts_data
 * @property array super_alerts_data
 */
class UserDetails {
	/**
	 * @var mywrap_con
	 */
	private $connection;

	/**
	 * @var BuffTripulacao
	 */
	public $buffs;

	/**
	 * @var Equipamentos
	 */
	public $equipamentos;

	/**
	 * @var Alerts
	 */
	public $alerts;

	public function __construct($connection) {
		$this->connection = $connection;

		$this->_update_last_logon();
		$this->_update_vip();
		$this->_update_mini_eventos();

		$this->buffs = new BuffTripulacao($this, $connection);
		$this->equipamentos = new Equipamentos($connection);
		$this->alerts = new Alerts($this, $connection);
	}

	public function get_time_now() {
		$ano = date("Y", time());
		$mes = date("m", time());
		$dia = date("d", time());
		$hora = date("H", time());
		$min = date("i", time());
		$sec = date("s", time());

		return mktime($hora, $min, $sec, $mes, $dia, $ano);
	}

	public function get_user_ip() {
		//Just get the headers if we can or else use the SERVER global
		if (function_exists('apache_request_headers')) {
			$headers = apache_request_headers();
		} else {
			$headers = $_SERVER;
		}
		//Get the forwarded IP if it exists
		if (array_key_exists('X-Forwarded-For', $headers) && filter_var($headers['X-Forwarded-For'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			$the_ip = $headers['X-Forwarded-For'];
		} elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $headers) && filter_var($headers['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
			$the_ip = $headers['HTTP_X_FORWARDED_FOR'];
		} else {
			$the_ip = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
		}
		return $the_ip;
	}

	protected function _update_last_logon() {
		global $_SERVER;
		if (!$this->tripulacao) {
			return;
		}

		$this->connection->run("UPDATE tb_usuarios SET ip = ?, ultimo_logon = ?, ultima_pagina = ? WHERE id=?",
			"sssi", array($this->get_user_ip(), $this->get_time_now(), $_SERVER["REQUEST_URI"], $this->tripulacao["id"]));

	}

	private function _update_vip() {
		if (!$this->vip) {
			return;
		}
		$tempo = $this->get_time_now();
		if ($this->vip["luneta_duracao"] < $tempo AND $this->vip["luneta_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET luneta = '0', luneta_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
		if ($this->vip["sense_duracao"] < $tempo AND $this->vip["sense_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET sense = '0', sense_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
		if ($this->vip["tatic_duracao"] < $tempo AND $this->vip["tatic_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET tatic = '0', tatic_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
		if ($this->vip["conhecimento_duracao"] < $tempo AND $this->vip["conhecimento_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET conhecimento = '0', conhecimento_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
		if ($this->vip["coup_de_burst_duracao"] < $tempo AND $this->vip["coup_de_burst_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET coup_de_burst = '0', coup_de_burst_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
		if ($this->vip["formacoes_duracao"] < $tempo AND $this->vip["formacoes_duracao"] != 0) {
			$this->connection->run("UPDATE tb_vip SET formacoes = '0', formacoes_duracao = '0' WHERE id= ?",
				"i", $this->tripulacao["id"]);
		}
	}

	private function _update_mini_eventos() {
		$events_details = DataLoader::load("mini_eventos");
		$events = $this->connection->run(
			"SELECT * FROM tb_mini_eventos WHERE fim < NOW()"
		)->fetch_all_array();

		foreach ($events as $event) {
			$event_detail = $events_details[$event["id"]];

			$this->connection->run(
				"UPDATE tb_mini_eventos SET fim = ADDTIME(current_timestamp, ?), inicio = current_timestamp, pack_recompensa = ? WHERE id = ?",
				"sii", array($event_detail["duracao"], array_rand($event_detail["recompensas"]), $event["id"]));

			$this->connection->run("DELETE FROM tb_mapa_rdm WHERE rdm_id IN (" . implode(",", $event_detail["zonas"]) . ")");
			for ($i = 0; $i < $event_detail["quant"]; $i++) {
				spawn_rdm_in_random_coord($event_detail["mares"][array_rand($event_detail["mares"])], $event_detail["zonas"][array_rand($event_detail["zonas"])]);
			}
			$this->connection->run("DELETE FROM tb_mini_eventos_concluidos WHERE mini_evento_id = ?", "i", array($event["id"]));
		}

		foreach ($events_details as $id => $event) {
			$event_in_db = $this->connection->run("SELECT * FROM tb_mini_eventos WHERE id = ?", "i", array($id));
			if (!$event_in_db->count()) {
				$this->connection->run(
					"INSERT INTO tb_mini_eventos (id, fim, pack_recompensa) VALUE (?, ADDTIME(current_timestamp, ?), ?)",
					"isi", array($id, $event["duracao"], array_rand($event["recompensas"])));

				$this->connection->run("DELETE FROM tb_mapa_rdm WHERE rdm_id IN (" . implode(",", $event["zonas"]) . ")");
				for ($i = 0; $i < $event["quant"]; $i++) {
					spawn_rdm_in_random_coord($event["mares"][array_rand($event["mares"])], $event["zonas"][array_rand($event["zonas"])]);
				}
			}
		}
	}

	public function __get($property) {
		$load_method = "_load_$property";
		if (property_exists($this, $property)) {
			return $this->$property;
		} else if (method_exists($this, $load_method)) {
			return $this->$load_method();
		} else {
			return null;
		}
	}

	public function start_session() {
		if (session_status() != PHP_SESSION_ACTIVE) {
			session_start();
		}
	}

	protected function _get_token() {
		$this->start_session();

		global $_SESSION;
		global $_COOKIE;

		if (isset($_COOKIE["sg_c"]) AND isset($_COOKIE["sg_k"])) {
			if (!validate_alphanumeric($_COOKIE["sg_c"]) || !validate_alphanumeric($_COOKIE["sg_k"])) {
				return false;
			}
			$id_encrip = $_COOKIE["sg_c"];
			$cookie = $_COOKIE["sg_k"];

			if (!isset($_SESSION["sg_c"]) OR !isset($_SESSION["sg_k"])) {
				$_SESSION["sg_c"] = $_COOKIE["sg_c"];
				$_SESSION["sg_k"] = $_COOKIE["sg_k"];
			}
		} else if (isset($_SESSION["sg_c"]) AND isset($_SESSION["sg_k"])) {
			if (!validate_alphanumeric($_SESSION["sg_c"]) || !validate_alphanumeric($_SESSION["sg_k"])) {
				return false;
			}
			$id_encrip = $_SESSION["sg_c"];
			$cookie = $_SESSION["sg_k"];
		} else {
			return false;
		}
		return array(
			"id_encrypted" => $id_encrip,
			"token" => $cookie
		);
	}

	public function set_authentication($conta_id) {
		$cookie = md5(uniqid(time()));

		//inicia sessao
		$this->start_session();

		$_SESSION["sg_c"] = $conta_id;
		$_SESSION["sg_k"] = $cookie;

		setcookie("chat",	"0",		time() + 80000, '/', FALSE, TRUE);
		setcookie("sg_c",	$conta_id,	time() + 80000, '/', FALSE, TRUE);
		setcookie("sg_k",	$cookie,	time() + 80000, '/', FALSE, TRUE);

		//atualiza o cookie do bd
		$this->connection->run("UPDATE tb_conta SET cookie = ? WHERE conta_id = ?", "si", array($cookie, $conta_id));
	}

	private function _load_conta() {
		$token = $this->_get_token();

		if (!$token) {
			return ($this->conta = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_conta WHERE conta_id = ? LIMIT 1", "s", array($token["id_encrypted"]));

		if (!$result->count()) {
			return ($this->conta = false);
		}

		$conta = $result->fetch_array();

		if (!$this->_token_matches($conta["cookie"], $token["token"])) {
			return ($this->conta = false);
		}

		//$this->connection->run("INSERT INTO tb_log_acesso (conta_id, tripulacao_id, url) VALUES (?, ?, ?)",
		//   "iis", array($conta["conta_id"], $conta["tripulacao_id"], $_SERVER["REQUEST_URI"]));

		return ($this->conta = $conta);
	}

	private function _token_matches($saved_token, $request_token) {
		return $saved_token == $request_token;
	}

	private function _load_tripulacao() {
		if (!$this->conta) {
			return ($this->tripulacao = false);
		}
		if (!$this->conta["tripulacao_id"]) {
			return ($this->tripulacao = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", array($this->conta["tripulacao_id"]));

		$tripulacao = $result->fetch_array();
		$tripulacao["gold"] = $this->conta["gold"];

		$global = $this->connection->run("SELECT valor_int FROM tb_variavel_global WHERE variavel = ? LIMIT 1", 's', [
			VARIAVEL_TOTAL_HAKI_TREINOS
		])->fetch_array();
		$tripulacao["treinos_haki_disponiveis"] = (int)$global['valor_int'];

		// $attrs = [
		// 	'atk'		=> 0,
		// 	'def'		=> 0,
		// 	'agl'		=> 0,
		// 	'res'		=> 0,
		// 	'pre'		=> 0,
		// 	'dex'		=> 0,
		// 	'con'		=> 0,
		// 	'vit'		=> 0,
		// 	'haki_esq'	=> 0,
		// 	'haki_blo'	=> 0,
		// 	'haki_cri'	=> 0,
		// 	'haki_hdr'	=> 0,
		// ];

		// $result = $this->connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", "i", array($tripulacao['id']));
		// while ($perso = $result->fetch_array()) {
		// 	$bonus = calc_bonus($perso);
		// 	for ($i = 1; $i <= 7; $i++) {
		// 		$attrs[nome_atributo_tabela($i)] += $perso[nome_atributo_tabela($i)];
		// 		$attrs[nome_atributo_tabela($i)] += $bonus[nome_atributo_tabela($i)];
		// 	}

		// 	$attrs['haki_esq']	+= $perso['haki_hdr'];
		// 	$attrs['haki_blo']	+= $perso['haki_hdr'];
		// 	$attrs['haki_cri']	+= $perso['haki_hdr'];
		// 	$attrs['haki_hdr']	+= $perso['haki_hdr'];
		// }

		// $fight_power = 0;
		// $fight_power += ($attrs['vit']) * 200;
		// $fight_power += ($attrs['atk'] + $attrs['con'] + $attrs['def'] + $attrs['res']) * 150;
		// $fight_power += ($attrs['agl'] + $attrs['dex'] + $attrs['pre']) * 100;
		// $fight_power += ($attrs['haki_hdr']) * 500;
		// $fight_power += ($attrs['haki_esq'] + $attrs['haki_blo'] + $attrs['haki_cri']) * 125;

		$tripulacao["poder"] = 0;

		return ($this->tripulacao = $tripulacao);
	}

	private function _load_tripulacoes() {
		if (!$this->conta) {
			return ($this->tripulacoes = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_usuarios WHERE conta_id = ?", "i", array($this->conta["conta_id"]));

		return ($this->tripulacoes = $result->fetch_all_array());
	}

	private function _load_personagens() {
		if (!$this->tripulacao) {
			return ($this->personagens = false);
		}

		$personagens = [];
		$result = $this->connection->run("SELECT * FROM tb_personagens WHERE id = ? AND ativo = 1", "i", array($this->tripulacao["id"]));
		while ($row = $result->fetch_array()) {
			$level	= $row['lvl'];
			$xp		= $row['xp'];
			$xp_max = $row['xp_max'];
			if ($xp >= $xp_max && $level < 50) {
				$runs = 0;
				while ($xp >= $xp_max) {
					if ($level < 50) {
						$xp		-= $xp_max;
						$xp_max = formulaExp($level++);

						++$runs;
					} else {
						break;
					}
				}
				$row['lvl']            = $level;
				$row['xp']             = $xp;
				$row['xp_max']         = $xp_max;
				$row['pts']            += PONTOS_POR_NIVEL * $runs;
				$row['hp_max']         += 100 * $runs;
				$row['hp']             = $row['hp_max'];
				$row['mp_max']         += 7 * $runs;
				$row['mp']             = $row['mp_max'];
				$row['fama_ameaca']    += 20000 * $runs;

				$this->connection->run("UPDATE tb_personagens 
					SET lvl         = ?,
						xp      	= ?,
						xp_max      = ?,
						pts         = ?, 
						hp_max      = ?,
						hp          = ?,
						mp_max      = ?,
						mp          = ?,
						fama_ameaca = ?
					WHERE id = ? AND cod = ?", 'iiiiiiiiiii', [
						$row['lvl'],
						$row['xp'],
						$row['xp_max'],
						$row['pts'],
						$row['hp_max'],
						$row['hp'],
						$row['mp_max'],
						$row['mp'],
						$row['fama_ameaca'],
						$this->tripulacao['id'],
						$row['cod']
					]);
			}

			$personagens[] = $row;
		}

		// return ($this->personagens = $result->fetch_all_array());
		return ($this->personagens = $personagens);
	}

	private function _load_capitao() {
		if (!$this->personagens) {
			return ($this->capitao = false);
		}

		return ($this->capitao = $this->personagens[0]);
	}

	private function _load_lvl_mais_forte() {
		if (!$this->personagens) {
			return ($this->lvl_mais_forte = false);
		}
		$lvl_max = 0;
		foreach ($this->personagens as $pers) {
			if ($pers["lvl"] > $lvl_max) {
				$lvl_max = $pers["lvl"];
			}
		}

		return ($this->lvl_mais_forte = $lvl_max);
	}

	private function _load_fa_mais_alta() {
		if (!$this->personagens) {
			return ($this->fa_mais_alta = false);
		}
		$fa_max = 0;
		foreach ($this->personagens as $pers) {
			if ($pers["fama_ameaca"] > $fa_max) {
				$fa_max = $pers["fama_ameaca"];
			}
		}

		return ($this->fa_mais_alta = $fa_max);
	}

	private function _load_medicos() {
		if (!$this->personagens) {
			return ($this->medicos = false);
		}

		return ($this->medicos = $this->get_personagens_by_profissao(PROFISSAO_MEDICO));
	}

	private function _load_lvl_medico() {
		if (!$this->medicos) {
			return ($this->lvl_medico = false);
		}

		return ($this->lvl_medico = $this->_load_lvl_prof($this->medicos));
	}

	private function _load_navegadores() {
		if (!$this->personagens) {
			return ($this->navegadores = false);
		}

		return ($this->navegadores = $this->get_personagens_by_profissao(PROFISSAO_NAVEGADOR));
	}

	private function _load_lvl_navegador() {
		if (!$this->navegadores) {
			return ($this->lvl_navegador = false);
		}

		return ($this->lvl_navegador = $this->_load_lvl_prof($this->navegadores));
	}

	private function _load_lvl_prof($profissionais) {
		$lvl = 0;
		foreach ($profissionais as $prof) {
			if ($prof["profissao_lvl"] > $lvl) {
				$lvl = $prof["profissao_lvl"];
			}
		}
		return $lvl;
	}

	private function _load_lvl_min_prof($profissionais) {
		$lvl = 1000;
		foreach ($profissionais as $prof) {
			if ($prof["profissao_lvl"] < $lvl) {
				$lvl = $prof["profissao_lvl"];
			}
		}
		return $lvl;
	}

	private function _load_carpinteiros() {
		if (!$this->personagens) {
			return ($this->carpinteiros = false);
		}

		return ($this->carpinteiros = $this->get_personagens_by_profissao(PROFISSAO_CARPINTEIRO));
	}

	private function _load_lvl_carpinteiro() {
		if (!$this->carpinteiros) {
			return ($this->lvl_carpinteiro = false);
		}

		return ($this->lvl_carpinteiro = $this->_load_lvl_prof($this->carpinteiros));
	}

	private function _load_ferreiros() {
		if (!$this->personagens) {
			return ($this->ferreiros = false);
		}

		return ($this->ferreiros = $this->get_personagens_by_profissao(PROFISSAO_FERREIRO));
	}

	private function _load_artesoes() {
		if (!$this->personagens) {
			return ($this->artesoes = false);
		}

		return ($this->artesoes = $this->get_personagens_by_profissao(PROFISSAO_ARTESAO));
	}

	private function _load_mergulhadores() {
		if (!$this->personagens) {
			return ($this->mergulhadores = false);
		}

		return ($this->mergulhadores = $this->get_personagens_by_profissao(PROFISSAO_MERGULHADOR));
	}

	private function _load_lvl_mergulhador() {
		if (!$this->mergulhadores) {
			return ($this->lvl_mergulhador = false);
		}

		return ($this->lvl_mergulhador = $this->_load_lvl_min_prof($this->mergulhadores));
	}

	private function _load_arqueologos() {
		if (!$this->personagens) {
			return ($this->arqueologos = false);
		}

		return ($this->arqueologos = $this->get_personagens_by_profissao(PROFISSAO_ARQUEOLOGO));
	}

	private function _load_lvl_arqueologo() {
		if (!$this->arqueologos) {
			return ($this->lvl_arqueologo = false);
		}

		return ($this->lvl_arqueologo = $this->_load_lvl_min_prof($this->arqueologos));
	}

	private function _load_cartografos() {
		if (!$this->personagens) {
			return ($this->cartografos = false);
		}

		return ($this->cartografos = $this->get_personagens_by_profissao(PROFISSAO_CARTOGRAFO));
	}

	private function _load_lvl_cartografo() {
		if (!$this->cartografos) {
			return ($this->lvl_cartografo = false);
		}

		return ($this->lvl_cartografo = $this->_load_lvl_min_prof($this->cartografos));
	}

	public function get_personagens_by_profissao($prof) {
		$filter = function ($pers) use ($prof) {
			return $pers["profissao"] == $prof;
		};

		$array = array_values(array_filter($this->personagens, $filter));
		return count($array) ? $array : FALSE;
	}

	private function _load_rotas() {
		if (!$this->tripulacao) {
			return ($this->rotas = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_rotas WHERE id = ? ORDER BY indice", "i", $this->tripulacao["id"]);

		if (!$result->count()) {
			return ($this->rotas = false);
		}

		return ($this->rotas = $result->fetch_all_array());
	}

	public function can_access_inpel_down() {
		if ($impel_down_access = get_value_variavel_global(VARIAVEL_IDS_ACESSO_IMPEL_DOWN)) {
			return in_array($this->tripulacao["id"], explode(",", $impel_down_access["valor_varchar"]));
		}
		return false;
	}

	public function can_access_enies_lobby() {
		if ($access = get_value_variavel_global(VARIAVEL_IDS_ACESSO_ENIES_LOBBY)) {
			return in_array($this->tripulacao["id"], explode(",", $access["valor_varchar"]));
		}
		return false;
	}

	private function _verify_in_ilha($ilha) {
		if ($ilha == 0) {
			return FALSE;
		} else if ($ilha == 47) {
			if ($this->tripulacao["faccao"] == FACCAO_PIRATA) {
				$rdp = get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_PIRATA);
				return ($rdp == $this->tripulacao["id"]);
			} else if ($this->tripulacao["faccao"] == FACCAO_MARINHA) {
				$adf = get_value_varchar_variavel_global(VARIAVEL_VENCEDORES_ERA_MARINHA);
				return ($adf == $this->tripulacao["id"]);
			}
		} else if ($ilha == 101) {
			if ($this->can_access_inpel_down()) {
				return TRUE;
			}
		} else if ($ilha == 102) {
			if ($this->can_access_enies_lobby()) {
				return TRUE;
			}
		} else {
			return TRUE;
		}

		return FALSE;
	}

	private function _load_in_ilha() {
		if (!$this->tripulacao) {
			return ($this->in_ilha = false);
		}

		return $this->in_ilha = $this->_verify_in_ilha($this->ilha["ilha"]);
	}

	private function _load_ilha() {
		if (!$this->tripulacao) {
			return ($this->ilha = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_mapa WHERE x = ? AND y = ? LIMIT 1",
			"ii", array($this->tripulacao["x"], $this->tripulacao["y"]));

		if ($result->count()) {
			$this->ilha = $result->fetch_array();
		} else {
			$this->ilha = [
				"ilha" => 0,
				"x" => $this->tripulacao["x"],
				"y" => $this->tripulacao["y"],
				"mar" => get_mar($this->tripulacao["x"], $this->tripulacao["y"])
			];
		}

		if ($this->ilha["ilha"] == 47 && !$this->_verify_in_ilha($this->ilha["ilha"])) {
			$this->ilha["ilha"] = 0;
		}

		return $this->ilha;
	}

	private function _load_navio() {
		if (!$this->tripulacao) {
			return ($this->navio = false);
		}
		$result = $this->connection->run("SELECT
			*,
			(SELECT quant FROM tb_usuario_itens WHERE id = usrnav.id AND tipo_item = 13) AS canhao_balas
			FROM tb_usuario_navio usrnav
			INNER JOIN tb_navio nav
			ON usrnav.cod_navio = nav.cod_navio
			 WHERE usrnav.id = ?", "i", $this->tripulacao["id"]);

		if (!$result->count()) {
			return ($this->navio = false);
		}
		return ($this->navio = $result->fetch_array());
	}

	private function _load_vip() {
		if (!$this->tripulacao) {
			return ($this->vip = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_vip WHERE id = ?", "i", $this->tripulacao["id"]);
		return ($this->vip = $result->fetch_array());
	}

	private function _load_ally() {
		if (!$this->tripulacao) {
			return ($this->ally = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_alianca_membros WHERE id = ?", "i", $this->tripulacao["id"]);

		if (!$result->count()) {
			return ($this->ally = false);
		}

		$ally = $result->fetch_array();
		$result = $this->connection->run("SELECT * FROM tb_alianca WHERE cod_alianca=?", "i", $ally["cod_alianca"]);

		$this->ally = $result->fetch_array();
		$this->ally["autoridade"] = $ally["autoridade"];
		$this->ally["cooperacao"] = $ally["cooperacao"];

		return $this->ally;
	}

	private function _load_in_combate() {
		return $this->combate_pve || $this->combate_pvp || $this->combate_bot;
	}

	private function _load_combate_pvp() {
		if (!$this->tripulacao) {
			return ($this->combate_pvp = false);
		}
		$result = $this->connection->run("SELECT * FROM tb_combate WHERE id_1 =  ? OR id_2 = ?",
			"ii", array($this->tripulacao["id"], $this->tripulacao["id"]));

		if (!$result->count()) {
			return ($this->combate_pvp = false);
		}

		$this->connection->run("DELETE FROM tb_combate_npc WHERE id = ?", "i", $this->tripulacao["id"]);

		return ($this->combate_pvp = $result->fetch_array());
	}

	private function _load_tripulacoes_pvp() {
		if (!$this->combate_pvp) {
			return ($this->tripulacoes_pvp = false);
		}
		$tripulacao["1"] = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $this->combate_pvp["id_1"])
			->fetch_array();
		$tripulacao["2"] = $this->connection->run("SELECT * FROM tb_usuarios WHERE id = ?", "i", $this->combate_pvp["id_2"])
			->fetch_array();

		return ($this->tripulacoes_pvp = $tripulacao);
	}

	private function _load_combate_pve() {
		if (!$this->tripulacao) {
			return ($this->combate_pve = false);
		}
		$result = $this->connection->run("SELECT * FROM tb_combate_npc WHERE id = ?", "i", $this->tripulacao["id"]);

		if (!$result->count()) {
			return ($this->combate_pve = false);
		}
		$combate = $result->fetch_array();
		if ($combate["boss_id"]) {
			$boss = $this->connection->run("SELECT * FROM tb_boss WHERE id = ?", "i", $combate["boss_id"])->fetch_array();
			$combate["hp_npc"] = $boss["hp"];
			$combate["real_boss_id"] = $boss["real_boss_id"];
		}

		return ($this->combate_pve = $combate);
	}

	private function _load_combate_bot() {
		if (!$this->tripulacao) {
			return ($this->combate_bot = false);
		}
		$result = $this->connection->run("SELECT * FROM tb_combate_bot WHERE tripulacao_id = ?", "i", array($this->tripulacao["id"]));

		if (!$result->count()) {
			return ($this->combate_bot = false);
		}

		return ($this->combate_bot = $result->fetch_array());
	}

	private function _load_missao() {
		if (!$this->tripulacao) {
			return ($this->missao = false);
		}
		$result = $this->connection->run(
			"SELECT * FROM tb_missoes_iniciadas misini WHERE misini.id = ?",
			"i", $this->tripulacao["id"]
		);

		if (!$result->count()) {
			return ($this->missao = false);
		}

		$missao_iniciada = $result->fetch_array();

		$missoes = DataLoader::load("missoes");

		return ($this->missao = array_merge($missao_iniciada, $missoes[$missao_iniciada["cod_missao"]]));
	}

	private function _load_missao_r() {
		if (!$this->tripulacao) {
			return ($this->missao_r = false);
		}
		$result = $this->connection->run("SELECT * FROM tb_missoes_r WHERE id = ?", "i", $this->tripulacao["id"]);

		if (!$result->count()) {
			return ($this->missao_r = false);
		}
		return ($this->missao_r = $result->fetch_array());
	}

	private function _load_is_visivel() {
		if (!$this->tripulacao) {
			return ($this->is_visivel = false);
		}

		return ($this->is_visivel = $this->tripulacao["mar_visivel"]);
	}

	private function _load_has_ilha_envolta_me() {
		if (!$this->tripulacao) {
			return ($this->has_ilha_envolta_me = false);
		}

		$my_x = $this->tripulacao["x"];
		$my_y = $this->tripulacao["y"];
		$result = $this->connection->run(
			"SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND ilha <> 0",
			"iiii", array($my_x - 2, $my_x + 2, $my_y - 2, $my_y + 2)
		);

		return ($this->has_ilha_envolta_me = !!$result->count());
	}

	private function _load_has_ilha_or_terra_envolta_me() {
		if ($this->has_ilha_envolta_me) {
			return ($this->has_ilha_or_terra_envolta_me = true);
		} else {
			$ilha_proxima = $this->connection->run("SELECT * FROM tb_mapa WHERE x >= ? AND x <= ? AND y >= ? AND y <= ? AND (navegavel = 0 OR ilha <> 0)",
				"iiii", array(
					$this->tripulacao["x"] - 1,
					$this->tripulacao["x"] + 1,
					$this->tripulacao["y"] - 1,
					$this->tripulacao["y"] + 1
				));
			return ($this->has_ilha_or_terra_envolta_me = !!$ilha_proxima->count());
		}
	}

	private function _load_tripulacao_alive() {
		if (!$this->tripulacao) {
			return ($this->tripulacao_alive = false);
		}

		$this->tripulacao_alive = false;
		foreach ($this->personagens as $pers) {
			if ($pers["hp"] > 0) {
				$this->tripulacao_alive = true;
				break;
			}
		}

		return $this->tripulacao_alive;
	}

	private function _load_fila_coliseu() {
		if (!$this->tripulacao) {
			return ($this->fila_coliseu = false);
		}

		$result = $this->connection->run("SELECT * FROM tb_coliseu_fila WHERE id = ?", "i", array($this->tripulacao["id"]));

		return $this->fila_coliseu = $result->count() ? $result->fetch_array() : null;
	}

	private function _load_lvl_coliseu() {
		if (!$this->tripulacao) {
			return ($this->lvl_coliseu = false);
		}

		return $this->lvl_coliseu = $this->connection->run("SELECT MAX(lvl) AS lvl FROM tb_personagens WHERE id = ? AND time_coliseu = 1",
			"i", array($this->tripulacao["id"]))->fetch_array()["lvl"];
	}

	private function _load_alerts_data() {
		if (!$this->tripulacao) {
			return ($this->alerts_data = array());
		}

		$alerts = array();

		foreach ($this->personagens as $pers) {
			if ($this->alerts->has_alert_trip_sem_distribuir_atributo($pers)) {
				$alerts["status"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.status." . $pers["cod"]] = true;
				$alerts["trip_sem_distribuir_atributo." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_trip_sem_classe($pers)) {
				$alerts["status"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.classe." . $pers["cod"]] = true;
				$alerts["trip_sem_classe." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_trip_sem_profissao($pers)) {
				$alerts["status"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.status." . $pers["cod"]] = true;
				$alerts["trip_sem_profissao." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_trip_sem_distribuir_haki($pers)) {
				$alerts["status"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.status." . $pers["cod"]] = true;
				$alerts["trip_sem_distribuir_haki." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_trip_sem_efeito_especial($pers)) {
				$alerts["status"] = true;
				$alerts["status&nav=habilidades"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.habilidades." . $pers["cod"]] = true;
				$alerts["trip_sem_efeito_especial." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_nova_habilidade_classe($pers)) {
				$alerts["status"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.classe." . $pers["cod"]] = true;
				$alerts["nova_habilidade_classe." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_nova_habilidade_akuma($pers)) {
				$alerts["status"] = true;
				$alerts["status&nav=akuma"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.akuma." . $pers["cod"]] = true;
				$alerts["nova_habilidade_akuma." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_nova_habilidade_profissao($pers)) {
				$alerts["status"] = true;
				$alerts["status&nav=profissao"] = true;
				$alerts["status." . $pers["cod"]] = true;
				$alerts["status.profissao." . $pers["cod"]] = true;
				$alerts["nova_habilidade_profissao." . $pers["cod"]] = true;
			}
			if ($this->alerts->has_alert_sem_equipamento($pers)) {
				$alerts["status"] = true;
				$alerts["status&nav=equipamentos"] = true;
				$alerts["equipamentos." . $pers["cod"]] = true;
				$alerts["status.equipamentos." . $pers["cod"]] = true;
				$alerts["sem_equipamento." . $pers["cod"]] = true;
			}
			if ($this->tripulacao["battle_points"] > PONTOS_POR_NIVEL_BATALHA) {
				$alerts["status"] = true;
			}
		}

		return $this->alerts_data = $alerts;
	}

	private function _load_super_alerts_data() {
		if (!$this->tripulacao) {
			return ($this->super_alerts_data = array());
		}

		$alerts = array();

		foreach ($this->alerts_data as $alert => $bool) {
			$super_menu = get_super_menu($alert);
			if ($super_menu) {
				$alerts[get_super_menu($alert)] = true;
			}
		}

		return $this->super_alerts_data = $alerts;
	}

	public function add_effect($effect, $quant = 1) {
		$animacao = $this->connection->run("SELECT * FROM tb_tripulacao_animacoes_skills WHERE tripulacao_id = ? AND effect = ?",
			"is", array($this->tripulacao["id"], $effect));

		if ($animacao->count()) {
			$this->connection->run("UPDATE tb_tripulacao_animacoes_skills SET quant = quant + ? WHERE tripulacao_id = ? AND effect = ?",
				"iis", array($quant, $this->tripulacao["id"], $effect));
		} else {
			$this->connection->run("INSERT INTO tb_tripulacao_animacoes_skills (tripulacao_id, effect, quant) VALUE (?,?,?)",
				"isi", array($this->tripulacao["id"], $effect, $quant));
		}
	}

	public function remove_skills_classe($pers) {
		global $COD_HAOSHOKU_LVL;
		$skils_nao_resetaveis = array_merge($COD_HAOSHOKU_LVL, array(1, 2));

		$this->restaura_effects($pers, "((tipo='1' AND cod_skil NOT IN (" . implode(",", $skils_nao_resetaveis) . ")) OR tipo='2' OR tipo='3')");

		$this->connection->run(
			"DELETE FROM tb_personagens_skil 
			WHERE cod= ? AND  ((tipo='1' AND cod_skil NOT IN (" . implode(",", $skils_nao_resetaveis) . ")) OR tipo='2' OR tipo='3')",
			"i", array($pers["cod"])
		);
	}

	public function remove_hdr($pers) {
		global $COD_HAOSHOKU_LVL;
		$this->restaura_effects($pers, "(tipo = 1 AND cod_skil IN (" . implode(',', $COD_HAOSHOKU_LVL) . "))");

		$this->connection->run("DELETE FROM tb_personagens_skil WHERE cod = ? AND tipo = ? AND cod_skil IN (" . implode(',', $COD_HAOSHOKU_LVL) . ")",
			"ii", array($pers["cod"], TIPO_SKILL_ATAQUE_CLASSE));
	}

	public function remove_skills_profissao($pers) {
		$this->restaura_effects($pers, "(tipo IN (4,5,6))");

		$this->connection->run(
			"DELETE FROM tb_personagens_skil 
			WHERE cod=? AND tipo IN (4,5,6)",
			"i", array($pers["cod"])
		);
	}

	public function restaura_effects($pers, $where) {
		$effects = $this->connection->run(
			"SELECT effect, count(*) AS quant FROM tb_personagens_skil 
			WHERE cod = ? AND effect <> 'Atingir fisicamente' AND $where
			GROUP BY effect",
			"i", array($pers["cod"]));

		while ($effect = $effects->fetch_array()) {
			$this->add_effect($effect["effect"], $effect["quant"]);
		}
	}

	private function get_all_progress_info() {
		$mar = $this->ilha["mar"] == 4 ? 3 : ($this->ilha["mar"] == 3 ? 4 : $this->ilha["mar"]);
		$segunda_ilha = ($mar - 1) * 7 + 2;
		$segunda_ilha_coord = get_coord_ilha($segunda_ilha);
		$ultima_ilha_blue = ($mar - 1) * 7 + 7;
		$ultima_ilha_blue_coord = get_coord_ilha($ultima_ilha_blue);
		$coord_sabaody = get_coord_ilha(42);
		$coord_mariejois = get_coord_ilha(43);

		return array(
			0 => array(FACCAO_MARINHA => array(
				"title" => "Bem vindo marujo!",
				"description" => "Você foi convocado para se apresentar imediatamente na <u>Base da Marinha</u>!<br/> Clique no menu 'Ilha atual' para encontra-la e volte aqui de novo depois de concluir sua primeira missão.",
				"finished" => "Foi uma primeira tarefa muito simples! Mas não pense que será fácil, existe muito trabalho pela frente."
			), FACCAO_PIRATA => array(
				"title" => "Bem vindo marujo!",
				"description" => "Como um recem chegado, recomendo que você visite o <u>Subúrbio</u> da ilha, lá você encontrará sua primeira aventura!<br/> Clique no menu 'Ilha atual' para encontra-lo e volte aqui de novo depois de concluir sua primeira missão.",
				"finished" => "Foi uma primeira tarefa muito simples! Mas não pense que será fácil, existe muito trabalho pela frente."
			), "next" => 1),
			1 => array(FACCAO_MARINHA => array(
				"title" => "Recuperando as energias",
				"description" => "Caso a sua última batalha tenha te deixado fraco, visite o <u>Hospital</u> da ilha para recuperar suas energias e volte aqui quando estiver 100% outra vez.",
				"finished" => "Espero que não se acostume com esse tipo de folga..."
			), FACCAO_PIRATA => array(
				"title" => "Recuperando as energias",
				"description" => "Caso a sua última batalha tenha te deixado fraco, visite o <u>Hospital</u> da ilha para recuperar suas energias e volte aqui quando estiver 100% outra vez.",
				"finished" => "Espero que não se acostume com esse tipo de folga..."
			), "next" => 2),
			2 => array(FACCAO_MARINHA => array(
				"title" => "Aprendizado inicial",
				"description" => "Todo marinheiro precisa trabalhar muito para ficar forte, realize mais <u>uma missão na Base da Marinha</u> e volte aqui quando terminar.",
				"finished" => "Ufa... Quanta trabalheira..."
			), FACCAO_PIRATA => array(
				"title" => "Aprendizado inicial",
				"description" => "Espero que esteja observando tudo, todo pirata sabe o quão importante é reconhecer o território. Realize mais <u>uma missão no Subúrbio</u> e volte aqui quando terminar.",
				"finished" => "É muita informação pra um dia só?"
			), "next" => 3),
			3 => array(FACCAO_MARINHA => array(
				"title" => "Evoluindo de verdade",
				"description" => "Me parece que você está ficando mais forte. Acesse a <u>Visão Geral da tripulação</u> para evoluir seu capitão.",
				"finished" => "Você realmente está ficando mais forte!"
			), FACCAO_PIRATA => array(
				"title" => "Evoluindo de verdade",
				"description" => "Me parece que você está ficando mais forte. Acesse a <u>Visão Geral da tripulação</u> para evoluir seu capitão.",
				"finished" => "Você realmente está ficando mais forte!"
			), "next" => 4),
			4 => array(FACCAO_MARINHA => array(
				"title" => "Meu barco minha vida",
				"description" => "Bom trabalho marujo! Você já tem a permissão do Oficial local para ter sua própria embarcação. Visite o <u>Estaleiro</u> da Ilha Atual para comprar seu primeiro barco.",
				"finished" => "É uma bela embarcação marujo!"
			), FACCAO_PIRATA => array(
				"title" => "Meu barco minha vida",
				"description" => "Me parece que você já está maluco pra meter a cara em uma nova aventura em alto mar. Agora que conseguiu dinheiro, chegou a hora de comprar uma embarcação. Visite o <u>Estaleiro</u> da Ilha Atual para comprar seu primeiro barco.",
				"finished" => "Que pangaré arrumado o cê arranjou hein?"
			), "next" => 5),
			5 => array(FACCAO_MARINHA => array(
				"title" => "Meu semblante de Honra!",
				"description" => "Um marinheiro que quer fazer renome pelos sete mares não pode viajar por aí sem uma marca que leve seu nome aos céus. Acesse a <u>Visão Geral da tripulação</u> e clique na sua bandeira para personaliza-la.",
				"finished" => "Excelente trabalho marujo!"
			), FACCAO_PIRATA => array(
				"title" => "O meu nome será levao aos céus!",
				"description" => "Um pirata que se preze não pode fazer renome pelos sete mares sem uma marca que leve seu nome aos céus. Acesse a <u>Visão Geral da tripulação</u> e clique na sua bandeira para personaliza-la.",
				"finished" => "Mas você é quase um Picasso!"
			), "next" => 6),
			6 => array(FACCAO_MARINHA => array(
				"title" => "Em busca de companheiros",
				"description" => "Com essa linda bandeira, e com um barco espaçoso, aposto que não faltarão marinheiros querendo se juntar a você. Acesse a opção <u>Recrutar</u> da Ilha atual para encontrar seu primeiro companheiro.",
				"finished" => "Duas cabeças pensam melhor do que uma não é mesmo?"
			), FACCAO_PIRATA => array(
				"title" => "Em busca de companheiros",
				"description" => "Com essa linda bandeira, e com um barco espaçoso, aposto que não faltarão piratas querendo se juntar a você. Acesse a opção <u>Recrutar</u> da Ilha atual para encontrar seu primeiro companheiro.",
				"finished" => "Duas cabeças pensam melhor do que uma não é mesmo?"
			), "next" => 19),
			7 => array(FACCAO_MARINHA => array(
				"title" => "Partindo em alto mar!",
				"description" => "Após concluir todas missões na <u>Base da Marinha</u> será a hora de partir em uma aventura além da compreensão! Assim que estiver pronto clique em <u>Ir para o oceano</u> dentro do menu Oceano, trace uma rota e volte aqui quando já estiver em alto mar!",
				"finished" => "Puxa vida! Quanta água!"
			), FACCAO_PIRATA => array(
				"title" => "Partindo em alto mar!",
				"description" => "Após concluir todas missões no <u>Subúrbio</u> será a hora de partir em uma aventura além da compreensão! Assim que estiver pronto clique em <u>Ir para o oceano</u> dentro do menu Oceano, trace uma rota e volte aqui quando já estiver em alto mar!",
				"finished" => "Puxa vida! Quanta água!"
			), "next" => 8),
			8 => array(FACCAO_MARINHA => array(
				"title" => "Criaturas maritimas",
				"description" => "Dizem por aí que o oceano é infestado de criaturas muito poderosas, será que você consegue derrotar alguma? Navegue com seu barco até encontrar uma criatura marítma e então <u>derrote-a.</u>",
				"finished" => "Ufa... Esse monstro deu trabalho não é mesmo?"
			), FACCAO_PIRATA => array(
				"title" => "Criaturas maritimas",
				"description" => "Dizem por aí que o oceano é infestado de criaturas muito poderosas, será que você consegue derrotar alguma? Navegue com seu barco até encontrar uma criatura marítma e então <u>derrote-a.</u>",
				"finished" => "Puxa vida, esse bichinho deu trabalho não é mesmo?"
			), "next" => 9),
			9 => array(FACCAO_MARINHA => array(
				"title" => "Partiu proxima ilha!",
				"description" => "Chega de enrolação, nos disseram que a próxima ilha é <u>" . nome_ilha($segunda_ilha) . "</u> que fica em " . get_human_location($segunda_ilha_coord["x"], $segunda_ilha_coord["y"]) . ". Volte aqui quando chegar.",
				"finished" => "Terra a vista!"
			), FACCAO_PIRATA => array(
				"title" => "Partiu proxima ilha!",
				"description" => "Chega de enrolação, nos disseram que a próxima ilha é <u>" . nome_ilha($segunda_ilha) . "</u> que fica em " . get_human_location($segunda_ilha_coord["x"], $segunda_ilha_coord["y"]) . ". Volte aqui quando chegar.",
				"finished" => "Terra a vista!"
			), "next" => 11),
			10 => array(FACCAO_MARINHA => array(
				"title" => "Aprendendo a arte do combate",
				"description" => "Visite a <u>Academia</u> da ilha atual e aprenda uma classe no seu capitão.",
				"finished" => "Uau! Que estilo de combate maneiro!"
			), FACCAO_PIRATA => array(
				"title" => "Aprendendo a arte do combate",
				"description" => "Visite a <u>Academia</u> da ilha atual e aprenda uma classe no seu capitão.",
				"finished" => "Uau! Que estilo de combate maneiro!"
			), "next" => 11),
			11 => array(FACCAO_MARINHA => array(
				"title" => "Explorando a ilha",
				"description" => "Realize pelo menos uma missão na <u>Base da marinha</u> dessa ilha.",
				"finished" => "Bom trabalho!"
			), FACCAO_PIRATA => array(
				"title" => "Explorando a ilha",
				"description" => "Realize pelo menos uma missão no <u>Subúrbio</u> dessa ilha.",
				"finished" => "Bom trabalho!"
			), "next" => 13),
			12 => array(FACCAO_MARINHA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "Um chamado de um capitão da marinha solicita seus esforços em <u>" . nome_ilha($ultima_ilha_blue) . "</u> que fica em " . get_human_location($ultima_ilha_blue_coord["x"], $ultima_ilha_blue_coord["y"]) . ". Você precisa atravessar o Blue para chegar até essa ilha, mas não deixe de visitar outras ilhas no caminho para fazer missões e se fortalecer até lá.",
				"finished" => "Foi um longo caminho até aqui..."
			), FACCAO_PIRATA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "De acordo com informações confiáveis, o primeiro passo para chegar até a Grand Line é chegar até <u>" . nome_ilha($ultima_ilha_blue) . "</u> que fica em " . get_human_location($ultima_ilha_blue_coord["x"], $ultima_ilha_blue_coord["y"]) . ". Você precisa atravessar o Blue para chegar até essa ilha, mas não deixe de visitar outras ilhas no caminho para fazer missões e se fortalecer até lá.",
				"finished" => "UAU! Eles disseram que nunca conseguiríamos, mas olha só onde chegamos!"
			), "next" => 14),
			13 => array(FACCAO_MARINHA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "Meus parabéns marujo, seu trabaho na Marinha está dando resultados. Você está se aproximando de ingressar na Grand Line! Mas primeiro precisa que seu capitão alcance o <u>nível 15.</u>",
				"finished" => "Bom trabalho Marujo! Você realmente está ficando muito forte!"
			), FACCAO_PIRATA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "A Grand Line é um lugar perigoso onde só os mais fortes sobrevivem, por isso você precisa que seu capitão alcance primeiro o <u>nível 15</u> antes de continuar.",
				"finished" => "UAU! Você conseguiu! Realizou algo que poucos foram capazes!"
			), "next" => 12),
			14 => array(FACCAO_MARINHA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "Agora sim! Tudo pronto! Viaje até a Reverse Mountain para ingressar na Grand Line.",
				"finished" => "Caramba! Que travessia perigosa! Por pouco seu barco não foi completamente destruído. Mas qui estamos! Bem vindo a Grand Line!"
			), FACCAO_PIRATA => array(
				"title" => "Rumo a Grand Line!",
				"description" => "Agora sim! Tudo pronto! Os rumores dizem que a entrada da Grand Line é através da Reverse Mountain ",
				"finished" => "Caramba! Que travessia perigosa! Por pouco seu barco não foi completamente destruído. Mas qui estamos! Bem vindo a Grand Line!"
			), "next" => 15),
			15 => array(FACCAO_MARINHA => array(
				"title" => "Explorando a Grand Line",
				"description" => "Desde criança você sempre ouviu histórias sobre a famosa cidade de Mariejois, e agora que se tornou um marinheiro você pode realizar seu sonho de conhece-la. Atravesse a Grand Line, viaje para " . get_human_location($coord_mariejois["x"], $coord_mariejois["y"]) . ", lá é seu próximo destino!",
				"finished" => "Quanta aventura! Mas você está preparado pra algo ainda maior?"
			), FACCAO_PIRATA => array(
				"title" => "Explorando a Grand Line!",
				"description" => "As rotas de navegação da Grand Line terminam no Arquipélago de Sabaody que fica em " . get_human_location($coord_sabaody["x"], $coord_sabaody["y"]) . ", lá é seu próximo destino!",
				"finished" => "Quanta aventura! Mas você está preparado pra algo ainda maior?"
			), "next" => 16),
			16 => array(FACCAO_MARINHA => array(
				"title" => "Rumo ao Novo Mundo!",
				"description" => "É lá onde os mais poderosos estão, e pra chegar lá você não pode ficar atrás. Alcance o <u>nível 45</u> com seu capitão para prosseguir.",
				"finished" => "Com grandes poderes vem grandes responsabilidades."
			), FACCAO_PIRATA => array(
				"title" => "Rumo ao Novo Mundo!",
				"description" => "É lá onde os mais poderosos estão, e pra chegar lá você não pode ficar atrás. Alcance o <u>nível 45</u> com seu capitão para prosseguir.",
				"finished" => "Com grandes poderes vem grandes responsabilidades."
			), "next" => 17),
			17 => array(FACCAO_MARINHA => array(
				"title" => "Rumo ao Novo Mundo!",
				"description" => "Agora que chegou até aqui, clique em <u>Ir para o novo mundo</u> na Ilha atual, quando estiver em Mariejois",
				"finished" => "Bem vindo ao Novo Mundo!"
			), FACCAO_PIRATA => array(
				"title" => "Rumo ao Novo Mundo!",
				"description" => "Agora que chegou até aqui, clique em <u>Ir para o novo mundo</u> na Ilha atual, quando estiver em Sabaody",
				"finished" => "Bem vindo ao Novo Mundo!"
			), "next" => 18),
			18 => array(FACCAO_MARINHA => array(
				"title" => "Rumo ao One Piece!",
				"description" => "Sua aventura alcançou patamares épicos, e poucas pessoas conseguirão passar daqui. Como seu último desafio, chegue até <u>Laftel</u>! Apenas o Almirante de Frota consegue por os pés lá...",
				"finished" => "Estou realmente impressionado! Quero ser igual a você quando crescer.",
			), FACCAO_PIRATA => array(
				"title" => "Rumo ao One Piece!",
				"description" => "Sua aventura alcançou patamares épicos, e poucas pessoas conseguirão passar daqui. Como seu último desafio, chegue até <u>Laftel</u>! Apenas o Rei dos Piratas consegue por os pés lá...",
				"finished" => "Estou realmente impressionado! Quero ser igual a você quando crescer."
			), "next" => 1000),
			19 => array(FACCAO_MARINHA => array(
				"title" => "Um desafio de verdade",
				"description" => "Chegou a hora de enfrentar um inimigo em uma batalha de verdade. Acesse o menu <u>Incursão</u> na ilha atual e derrote o primeiro adversário na incursão pela ilha.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), FACCAO_PIRATA => array(
				"title" => "Um desafio de verdade",
				"description" => "Chegou a hora de enfrentar um inimigo em uma batalha de verdade. Acesse o menu <u>Incursão</u> na ilha atual e derrote o primeiro adversário na incursão pela ilha.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), "next" => 20),
			20 => array(FACCAO_MARINHA => array(
				"title" => "A classe de combate",
				"description" => "Para se fortalecer de verdade, você precisa escolher um estilo de jogo para cada um de seus tripulantes. Acesse o menu <u>Academia</u> na ilha atual e aprenda uma classe no seu capitão.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), FACCAO_PIRATA => array(
				"title" => "A classe de combate",
				"description" => "Para se fortalecer de verdade, você precisa escolher um estilo de jogo para cada um de seus tripulantes. Acesse o menu <u>Academia</u> na ilha atual e aprenda uma classe no seu capitão.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), "next" => 21),
			21 => array(FACCAO_MARINHA => array(
				"title" => "Concluindo a incursão",
				"description" => "Agora chegou a hora de você dar tudo de si em um grande desafio! Derrote todos os adversários e complente a <u>Incursão</u> pela Ilha atual",
				"finished" => "Estou impressionado! Você está ficando muito poderoso!"
			), FACCAO_PIRATA => array(
				"title" => "Concluindo a incursão",
				"description" => "Agora chegou a hora de você dar tudo de si em um grande desafio! Derrote todos os adversários e complente a <u>Incursão</u> pela Ilha atual",
				"finished" => "Estou impressionado! Você está ficando muito poderoso!"
			), "next" => 22),
			22 => array(FACCAO_MARINHA => array(
				"title" => "Equipamentos",
				"description" => "Após ter concluido a incursão você recebeu um equipamento. Acesse o menu <u>Equipamentos</u> na visão geral da tripulação e equipe-o em seu capitão.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), FACCAO_PIRATA => array(
				"title" => "Equipamentos",
				"description" => "Após ter concluido a incursão você recebeu um equipamento. Acesse o menu <u>Equipamentos</u> na visão geral da tripulação e equipe-o em seu capitão.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), "next" => 23),
			23 => array(FACCAO_MARINHA => array(
				"title" => "Preparativos para viagem",
				"description" => "Agora chegou a hora de você se jogar ao mar e seguir sua grande aventura. Mas não se esqueça dos preparativos, niguém sobrevive a longas viagens em alto mar se não levar comida. Visite o <u>Restaurante</u> da Ilha atual e compre alguns alimentos para viagem.",
				"finished" => "É isso aí! Saco vazio não para em pé!"
			), FACCAO_PIRATA => array(
				"title" => "Preparativos para viagem",
				"description" => "Agora chegou a hora de você se jogar ao mar e seguir sua grande aventura. Mas não se esqueça dos preparativos, niguém sobrevive a longas viagens em alto mar se não levar comida. Visite o <u>Restaurante</u> da Ilha atual e compre alguns alimentos para viagem.",
				"finished" => "É isso aí! Saco vazio não para em pé!"
			), "next" => 24),
			24 => array(FACCAO_MARINHA => array(
				"title" => "Encontrando uma direção",
				"description" => "Agora uma importante pergunta: Para onde seguir? Os cartógrafos são capazes de ler mapas que irão ajuda-lo a encontrar seu destino. Acesse a <u>Escola de Profissões</u> na Ilha Atual e aprenda a profissão de <u>Cartógrafo</u> com um de seus tripulantes.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), FACCAO_PIRATA => array(
				"title" => "Encontrando uma direção",
				"description" => "Agora uma importante pergunta: Para onde seguir? Os cartógrafos são capazes de ler mapas que irão ajuda-lo a encontrar seu destino. Acesse a <u>Escola de Profissões</u> na Ilha Atual e aprenda a profissão de <u>Cartógrafo</u> com um de seus tripulantes.",
				"finished" => "Muito bem! Você está pegando o jeito!"
			), "next" => 7),
		);
	}

	private $progress_reward = array(
		0 => array("xp" => 0, "berries" => 100),
		1 => array("xp" => 0, "berries" => 100),
		2 => array("xp" => 0, "berries" => 100),
		3 => array("xp" => 0, "berries" => 500),
		4 => array("xp" => 0, "berries" => 500),
		5 => array("xp" => 0, "berries" => 500),
		6 => array("xp" => 0, "berries" => 500),
		7 => array("xp" => 0, "berries" => 1000),
		8 => array("xp" => 0, "berries" => 1000),
		9 => array("xp" => 0, "berries" => 1000),
		10 => array("xp" => 0, "berries" => 1500),
		11 => array("xp" => 0, "berries" => 1500),
		12 => array("xp" => 0, "berries" => 2000),
		13 => array("xp" => 0, "berries" => 2000),
		14 => array("xp" => 1000, "berries" => 5000),
		15 => array("xp" => 2000, "berries" => 10000),
		16 => array("xp" => 3000, "berries" => 10000),
		17 => array("xp" => 5000, "berries" => 10000),
		18 => array("xp" => 0, "berries" => 1000000),
		19 => array("xp" => 0, "berries" => 500),
		20 => array("xp" => 0, "berries" => 500),
		21 => array("xp" => 0, "berries" => 500),
		22 => array("xp" => 0, "berries" => 500),
		23 => array("xp" => 0, "berries" => 500),
		24 => array("xp" => 0, "berries" => 500),
	);

	public function get_progress_info() {
		if (!$this->tripulacao) {
			return NULL;
		}

		$all_progress_info = $this->get_all_progress_info();
		if (isset($all_progress_info[$this->tripulacao["progress"]])) {
			return $all_progress_info[$this->tripulacao["progress"]][$this->tripulacao["faccao"]];
		} else {
			return NULL;
		}
	}

	public function get_progress_reward() {
		return $this->progress_reward[$this->tripulacao["progress"]];
	}

	public function is_progress_finished() {
		if (!$this->tripulacao) {
			return false;
		}
		$func_name = "_check_progress_" . $this->tripulacao["progress"];
		if (!method_exists($this, $func_name)) {
			return false;
		}
		return $this->$func_name();
	}

	public function get_next_progress() {
		if (!$this->tripulacao) {
			return NULL;
		}

		$all_progress_info = $this->get_all_progress_info();
		if (isset($all_progress_info[$this->tripulacao["progress"]])) {
			return $all_progress_info[$this->tripulacao["progress"]]["next"];
		} else {
			return NULL;
		}
	}

	private function _check_progress_0() {
		$missoes_concluidas = $this->connection->run(
			"SELECT count(cod_missao) AS total FROM tb_missoes_concluidas WHERE id = ?",
			"i", $this->tripulacao["id"]
		)->fetch_array()["total"];

		return $missoes_concluidas > 0;
	}

	private function _check_progress_1() {
		foreach ($this->personagens as $pers) {
			if ($pers["hp"] < $pers["hp_max"]) {
				return false;
			}
		}

		return true;
	}

	private function _check_progress_2() {
		// return $this->capitao["xp"] >= $this->capitao["xp_max"];
		return $this->capitao["lvl"] >= 1;
	}

	private function _check_progress_3() {
		return $this->capitao["lvl"] >= 2;
	}

	private function _check_progress_4() {
		return !!$this->navio;
	}

	private function _check_progress_5() {
		return $this->tripulacao["bandeira"] != '010113046758010128123542010115204020';
	}

	private function _check_progress_6() {
		return count($this->personagens) > 1;
	}

	private function _check_progress_7() {
		return $this->ilha["ilha"] == 0;
	}

	private function _check_progress_8() {
		$defeated = $this->connection->run("SELECT count(*) AS total FROM tb_pve WHERE id = ?", "i", $this->tripulacao["id"])
			->fetch_array()["total"];

		return $defeated > 0;
	}

	private function _check_progress_9() {
		$mar = $this->ilha["mar"] == 4 ? 3 : ($this->ilha["mar"] == 3 ? 4 : $this->ilha["mar"]);
		$segunda_ilha = ($mar - 1) * 7 + 2;
		return $this->ilha["ilha"] == $segunda_ilha;
	}

	private function _check_progress_10() {
		return $this->capitao["classe"] != 0;
	}

	private function _check_progress_11() {
		$segunda_ilha = ($this->ilha["mar"] - 1) * 7 + 2;
		$missoes_concluidas = $this->connection->run(
			"SELECT count(missaoc.cod_missao) AS total 
			FROM tb_missoes_concluidas missaoc
			INNER JOIN tb_ilha_missoes imissao ON missaoc.cod_missao = imissao.cod_missao AND imissao.ilha = ?
			WHERE missaoc.id = ?",
			"ii", array($segunda_ilha, $this->tripulacao["id"])
		)->fetch_array()["total"];

		return $missoes_concluidas >= 1;
	}

	private function _check_progress_12() {
		$mar = $this->ilha["mar"] == 4 ? 3 : ($this->ilha["mar"] == 3 ? 4 : $this->ilha["mar"]);
		$ultima_ilha = ($mar - 1) * 7 + 7;
		return $this->ilha["ilha"] == $ultima_ilha;
	}

	private function _check_progress_13() {
		return $this->capitao["lvl"] >= 15;
	}

	private function _check_progress_14() {
		return $this->ilha["mar"] == 5;
	}

	private function _check_progress_15() {
		return $this->tripulacao["faccao"] == FACCAO_PIRATA ? $this->ilha["ilha"] == 42 : $this->ilha["ilha"] == 43;
	}

	private function _check_progress_16() {
		return $this->capitao["lvl"] >= 45;
	}

	private function _check_progress_17() {
		return $this->ilha["mar"] == 6;
	}

	private function _check_progress_18() {
		return $this->ilha["ilha"] == 47;
	}

	private function _check_progress_19() {
		return $this->connection->run("SELECT * FROM tb_incursao_progresso WHERE tripulacao_id = ?",
				"i", array($this->tripulacao["id"]))->count() > 0;
	}

	private function _check_progress_20() {
		return !!$this->capitao["classe"];
	}

	private function _check_progress_21() {
		return $this->connection->run("SELECT * FROM tb_incursao_recompensa_recebida WHERE tripulacao_id = ?",
				"i", array($this->tripulacao["id"]))->count() > 0;
	}

	private function _check_progress_22() {
		return $this->connection->run("SELECT * FROM tb_personagem_equipamentos WHERE cod = ?",
				"i", array($this->capitao["cod"]))->count() > 0;
	}

	private function _check_progress_23() {
		return $this->connection->run("SELECT * FROM tb_usuario_itens WHERE id = ? AND tipo_item = ?",
				"ii", array($this->tripulacao["id"], TIPO_ITEM_COMIDA))->count() > 0;
	}

	private function _check_progress_24() {
		return !!$this->cartografos;
	}

	public function get_pers_by_cod($cod, $fora_barco = false) {
		if (!$fora_barco) {
			foreach ($this->personagens as $pers) {
				if ($pers["cod"] == $cod) {
					return $pers;
				}
			}
		} else {
			$result = $this->connection->run("SELECT * FROM tb_personagens WHERE cod = ? AND id = ?",
				"ii", array($cod, $this->tripulacao["id"]));

			return $result->count() ? $result->fetch_array() : null;
		}
		return NULL;
	}

	public function xp_for_all($quant) {
		if ($bonus = $this->buffs->get_efeito("bonus_xp")) {
			$quant += round($bonus * $quant);
		}

		$quant_lvl_max = $quant;
		if ($bonus = $this->buffs->get_efeito("multiplicador_xp_lvl_max")) {
			$quant_lvl_max *= $bonus;
		}

		$this->connection->run("UPDATE tb_personagens SET xp = xp + ? WHERE id = ? AND lvl < 50 AND ativo = 1",
			"ii", array($quant, $this->tripulacao["id"]));

		$this->connection->run("UPDATE tb_personagens SET xp = xp + ? WHERE id = ? AND lvl >= 50 AND ativo = 1",
			"ii", array($quant_lvl_max, $this->tripulacao["id"]));
	}

	public function xp_for_profissao($quant, $prof) {
		$this->connection->run("UPDATE tb_personagens SET profissao_xp = LEAST(profissao_xp + ?, profissao_xp_max) WHERE id = ? AND profissao = ? AND ativo = 1",
			"iii", array($quant, $this->tripulacao["id"], $prof));
	}

	public function haki_for_all($quant) {
		if ($bonus = $this->buffs->get_efeito("bonus_haki")) {
			$quant += round($bonus * $quant);
		}

		$this->connection->run("UPDATE tb_usuarios SET haki_xp = haki_xp + ? WHERE id = ?",
			"ii", array($quant, $this->tripulacao["id"]));
	}

	public function add_berries($quant) {
		$this->connection->run("UPDATE tb_usuarios SET berries = berries + ? WHERE id = ?",
			"ii", array($quant, $this->tripulacao["id"]));
	}

	public function add_haki($pers, $quant) {
		if ($pers["haki_lvl"] >= HAKI_LVL_MAX) {
			return;
		}

		$haki_xp = $pers["haki_xp"] + $quant;
		if ($haki_xp >= $pers["haki_xp_max"]) {
			$haki_xp = $haki_xp - $pers["haki_xp_max"];
			$lvl = $pers["haki_lvl"] + 1;
			$pts = $pers["haki_pts"] + 1;
			$haki_max = $pers["haki_xp_max"] + 1000;
		} else {
			$lvl = $pers["haki_lvl"];
			$pts = $pers["haki_pts"];
			$haki_max = $pers["haki_xp_max"];
		}

		$this->connection->run(
			"UPDATE tb_personagens
			SET haki_xp='$haki_xp', haki_lvl='$lvl', haki_pts='$pts', haki_xp_max='$haki_max'
			WHERE  cod = ?",
			"i", $pers["cod"]
		);

	}

	public function can_add_item($quant = 1, $id = null) {
		if (!$id) {
			$id = $this->tripulacao["id"];
		}
		$item_count = $this->connection->run("SELECT count(id) AS total FROM tb_usuario_itens WHERE id = ?",
			"i", array($id))->fetch_array()["total"];
		$navio = $this->connection->run("SELECT * FROM tb_usuario_navio WHERE id = ?", "i", array($id))->fetch_array();
		return ($item_count + $quant) <= $navio["capacidade_inventario"];
	}

	public function add_equipamento($equipamento) {
		$id = $this->equipamentos->create_equipamento($equipamento);

		return $this->add_item($id, TIPO_ITEM_EQUIPAMENTO, 1, true);
	}

	public function add_equipamento_by_cod($cod_equipamento) {
		$result = $this->connection->run("SELECT * FROM tb_equipamentos WHERE item = ?",
			"i", array($cod_equipamento));

		if (!$result->count()) {
			return false;
		}

		$equipamento = $result->fetch_array();

		return $this->add_equipamento($equipamento);
	}

	public function get_item($cod_item, $tipo_item, $id = null) {
		if (!$id) {
			$id = $this->tripulacao["id"];
		}
		$exist = $this->connection->run("SELECT * FROM tb_usuario_itens WHERE tipo_item = ? AND cod_item = ? AND id = ?",
			"iii", array($tipo_item, $cod_item, $id));
		return $exist->count() ? $exist->fetch_array() : NULL;
	}

	public function add_item($cod_item, $tipo_item, $quant, $unique = false, $id = null) {
		if (!$id) {
			$id = $this->tripulacao["id"];
		}
		if (!$this->can_add_item(1, $id)) {
			return false;
		}

		$item = $this->get_item($cod_item, $tipo_item, $id);

		if ($unique || !$item) {
			$this->connection->run("INSERT INTO tb_usuario_itens (id, cod_item, tipo_item, quant) VALUES (?, ?, ?, ?)",
				"iiii", array($id, $cod_item, $tipo_item, $quant));
		} else {
			$quant += $item["quant"];

			$this->connection->run("UPDATE tb_usuario_itens SET quant = ?, novo = 1 WHERE cod_item = ? AND tipo_item = ? AND id = ?",
				"iiii", array($quant, $cod_item, $tipo_item, $id));
		}

		return true;
	}

	public function reduz_item($cod_item, $tipo_item, $quant, $unique = false) {
		if ($unique) {
			$this->connection->run("DELETE FROM tb_usuario_itens WHERE cod_item = ? AND tipo_item = ? AND id = ? LIMIT 1",
				"iii", array($cod_item, $tipo_item, $this->tripulacao["id"]));
		} else {
			$item = $this->get_item($cod_item, $tipo_item);

			if ($item) {
				$nQuant = $item["quant"] - $quant;

				if ($nQuant <= 0) {
					$this->connection->run("DELETE FROM tb_usuario_itens WHERE cod_item = ? AND tipo_item = ? AND id = ?",
						"iii", array($cod_item, $tipo_item, $this->tripulacao["id"]));
				} else {
					$this->connection->run("UPDATE tb_usuario_itens SET quant = ? WHERE cod_item = ? AND tipo_item = ? AND id = ?",
						"iiii", array($nQuant, $cod_item, $tipo_item, $this->tripulacao["id"]));
				}
			}
		}
	}


	public function reduz_gold_or_dobrao($tipo, $quant_gold, $quant_dobrao, $scrit) {
		if ($tipo == "gold") {
			$this->reduz_gold($quant_gold, $scrit);
		} else if ($tipo == "dobrao") {
			$this->reduz_dobrao($quant_dobrao, $scrit);
		}
	}

	public function reduz_gold($quant, $script) {
		if ($this->conta["gold"] < $quant) {
			return false;
		}

		$this->connection->run(
			"UPDATE tb_conta SET gold = gold - ? WHERE conta_id = ?",
			"ii", array($quant, $this->conta["conta_id"])
		);

//        $gasto = $this->connection->run("SELECT sum(quant) AS total FROM tb_gold_log WHERE user_id = ? AND quando > '2017-11-23 00:00:00' AND quando <'2017-11-25 00:00:00'",
//            "i", array($this->tripulacao["id"]))->fetch_array()["total"];
//
//        $gasto = $gasto % 50;
//
//        $bonus = floor(($gasto + $quant) / 50);
//
//        if ($bonus) {
//            $this->connection->run("UPDATE tb_usuarios SET free_reset_atributos = free_reset_atributos + ? WHERE id = ?",
//                "ii", array($bonus, $this->tripulacao["id"]));
//        }

		$this->connection->run(
			"INSERT INTO tb_gold_log (user_id, quant, script) VALUES (?, ? ,?)",
			"iis", array($this->tripulacao["id"], $quant, $script)
		);

		return true;
	}

	public function reduz_dobrao_criado($quant, $script) {
		if ($this->conta["dobroes_criados"] < $quant) {
			return false;
		}

		$this->connection->run(
			"UPDATE tb_conta SET dobroes_criados = dobroes_criados - ? WHERE conta_id = ?",
			"ii", array($quant, $this->conta["conta_id"])
		);

		$this->connection->run(
			"INSERT INTO tb_dobroes_log (conta_id, tripulacao_id, quant, script) VALUES (?, ?, ? ,?)",
			"iiis", array($this->conta["conta_id"], $this->tripulacao["id"], $quant, $script)
		);

		return true;
	}

	public function reduz_dobrao($quant, $script) {
		if ($this->conta["dobroes"] < $quant) {
			return false;
		}

		$this->connection->run(
			"UPDATE tb_conta SET dobroes = dobroes - ? WHERE conta_id = ?",
			"ii", array($quant, $this->conta["conta_id"])
		);

		$this->connection->run(
			"INSERT INTO tb_dobroes_log (conta_id, tripulacao_id, quant, script) VALUES (?, ?, ? ,?)",
			"iiis", array($this->conta["conta_id"], $this->tripulacao["id"], $quant, $script)
		);

		return true;
	}

	public function reduz_berries($quant) {
		if ($this->tripulacao["berries"] < $quant) {
			return false;
		}
		$this->connection->run("UPDATE tb_usuarios SET berries = berries - ? WHERE id = ?",
			"ii", array($quant, $this->tripulacao["id"]));

		return true;
	}

	public function has_alert($menu) {
		return isset($this->alerts_data[$menu]);
	}

	public function render_alert($menu, $classe = null) {
		if ($this->has_alert($menu)) {
			echo $this->alerts->get_alert($classe);
		}
	}

	public function has_super_alert($menu) {
		return isset($this->super_alerts_data[$menu]);
	}
}